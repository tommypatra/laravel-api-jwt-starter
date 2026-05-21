<?php

namespace App\Libraries;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Sevima
{
    protected string $baseUrl;

    protected array $headers;

    protected int $timeout;

    protected int $maxRequests = 30;

    protected int $windowSeconds = 60;

    protected string $lockKey = 'sevima-api-lock';

    protected string $counterKey = 'sevima-api-counter';

    protected string $windowKey = 'sevima-api-window-start';

    protected string $cooldownKey = 'sevima-api-cooldown';

    public function __construct()
    {
        $config = config('sevima');
        $this->baseUrl = rtrim($config['base_url'], '/').'/';
        $this->timeout = $config['timeout'] ?? 30;
        $this->headers = $config['headers'];
    }

    /*
    |--------------------------------------------------------------------------
    | HTTP METHODS
    |--------------------------------------------------------------------------
    */

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, $query);
    }

    public function post(string $endpoint, array $body = []): array
    {
        return $this->request('POST', $endpoint, $body);
    }

    public function put(string $endpoint, array $body = []): array
    {
        return $this->request('PUT', $endpoint, $body);
    }

    public function delete(string $endpoint, array $body = []): array
    {
        return $this->request('DELETE', $endpoint, $body);
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN REQUEST
    |--------------------------------------------------------------------------
    */

    protected function request(
        string $method,
        string $endpoint,
        array $payload = []
    ): array {
        return Cache::lock($this->lockKey, 120)
            ->block(120, function () use ($method, $endpoint, $payload) {
                /*
                |--------------------------------------------------------------------------
                | GLOBAL COOLDOWN
                |--------------------------------------------------------------------------
                */
                $cooldownUntil = Cache::get($this->cooldownKey);
                if ($cooldownUntil && now()->timestamp < $cooldownUntil) {
                    $wait = $cooldownUntil - now()->timestamp;
                    logger()->warning('SEVIMA cooldown active', [
                        'wait' => $wait,
                    ]);
                    sleep($wait);
                }

                /*
                |--------------------------------------------------------------------------
                | WINDOW TRACKING
                |--------------------------------------------------------------------------
                */

                $windowStart = Cache::get($this->windowKey);
                if (! $windowStart) {
                    $windowStart = now()->timestamp;
                    Cache::put(
                        $this->windowKey,
                        $windowStart,
                        $this->windowSeconds
                    );
                    Cache::put(
                        $this->counterKey,
                        0,
                        $this->windowSeconds
                    );
                }
                $elapsed = now()->timestamp - $windowStart;

                /*
                |--------------------------------------------------------------------------
                | RESET WINDOW
                |--------------------------------------------------------------------------
                */
                if ($elapsed >= $this->windowSeconds) {
                    $windowStart = now()->timestamp;
                    $elapsed = 0;
                    Cache::put(
                        $this->windowKey,
                        $windowStart,
                        $this->windowSeconds
                    );
                    Cache::put(
                        $this->counterKey,
                        0,
                        $this->windowSeconds
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | LOCAL RATE LIMIT PREVENTION
                |--------------------------------------------------------------------------
                */
                $count = Cache::increment($this->counterKey);
                if ($count > $this->maxRequests) {
                    $wait = max(1, $this->windowSeconds - $elapsed);
                    logger()->warning('SEVIMA local rate limit wait', [
                        'count' => $count,
                        'wait' => $wait,
                    ]);
                    Cache::put(
                        $this->cooldownKey,
                        now()->timestamp + $wait,
                        $wait
                    );
                    sleep($wait);

                    return $this->request(
                        $method,
                        $endpoint,
                        $payload
                    );
                }

                try {
                    $url = $this->baseUrl.ltrim($endpoint, '/');
                    $http = Http::withHeaders($this->headers)
                        ->timeout($this->timeout);
                    $response = match ($method) {
                        'POST' => $http->post($url, $payload),
                        'PUT' => $http->put($url, $payload),
                        'DELETE' => $http->delete($url, $payload),
                        default => $http->get($url, $payload),
                    };
                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */
                    if ($response->successful()) {
                        return $this->formatResponse($response);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | REMOTE RATE LIMIT
                    |--------------------------------------------------------------------------
                    */
                    if ($response->status() === 429) {
                        $wait = max(1, $this->windowSeconds - $elapsed);
                        logger()->warning('SEVIMA remote rate limit hit', [
                            'endpoint' => $endpoint,
                            'wait' => $wait,
                        ]);

                        Cache::put(
                            $this->cooldownKey,
                            now()->timestamp + $wait,
                            $wait
                        );
                        sleep($wait);

                        return $this->request(
                            $method,
                            $endpoint,
                            $payload
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | OTHER ERROR
                    |--------------------------------------------------------------------------
                    */
                    return $this->formatResponse($response);

                } catch (\Throwable $e) {
                    report($e);

                    return [
                        'success' => false,
                        'status' => 500,
                        'message' => $e->getMessage(),
                        'data' => null,
                    ];
                }
            });
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT RESPONSE
    |--------------------------------------------------------------------------
    */
    protected function formatResponse(Response $response): array
    {
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'message' => $response->successful()
                ? 'Success'
                : $response->body(),
            'data' => $response->json(),
        ];
    }
}
