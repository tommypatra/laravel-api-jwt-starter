<?php

namespace App\Services;

use App\Libraries\Sevima;
use App\Models\Fakultas;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FakultasService
{
    public function __construct(
        protected Sevima $sevima
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(array $filters = []): LengthAwarePaginator
    {
        $query = Fakultas::query();

        $query->when(
            ! empty($filters['search']),
            function ($q) use ($filters) {
                $search = $filters['search'];

                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_fakultas', 'like', "%{$search}%")
                        ->orWhere('fakultas_singkatan', 'like', "%{$search}%")
                        ->orWhere('fakultas_siakad_id', 'like', "%{$search}%");
                });
            }
        );

        $query->when(
            isset($filters['is_aktif']),
            fn ($q) => $q->where('is_aktif', $filters['is_aktif'])
        );

        $query->when(
            isset($filters['fakultas_singkatan']),
            fn ($q) => $q->where('fakultas_singkatan', $filters['fakultas_singkatan'])
        );

        $query->when(
            ! empty($filters['fakultas_siakad_id']),
            fn ($q) => $q->where('fakultas_siakad_id', $filters['fakultas_siakad_id'])
        );

        return $query
            ->orderBy('nama_fakultas')
            ->paginate($filters['per_page'] ?? 10);
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function show(int $id): Fakultas
    {
        return Fakultas::findOrFail($id);
    }

    public function store(array $data): Fakultas
    {
        return Fakultas::create($data);
    }

    public function update(int $id, array $data): Fakultas
    {
        $fakultas = Fakultas::findOrFail($id);

        $fakultas->update($data);

        return $fakultas->refresh();
    }

    public function destroy(int $id): bool
    {
        $fakultas = Fakultas::findOrFail($id);

        return $fakultas->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW SYNC
    |--------------------------------------------------------------------------
    */

    public function previewSyncFromSiakad(): array
    {
        $response = $this->sevima->get('siakadcloud/v1/fakultas');

        if (! $response['success']) {
            throw new \Exception($response['message']);
        }

        $items = $response['data'];

        $insert = 0;
        $update = 0;
        $skip = 0;

        foreach ($items as $item) {
            $local = Fakultas::where(
                'fakultas_siakad_id',
                $item['id']
            )->first();

            if (! $local) {
                $insert++;

                continue;
            }

            if ($local->updated_at_siakad != $item['updated_at']) {
                $update++;

                continue;
            }

            $skip++;
        }

        return [
            'total' => count($items),
            'insert' => $insert,
            'update' => $update,
            'skip' => $skip,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTE SYNC
    |--------------------------------------------------------------------------
    */

    public function syncFromSiakad(): array
    {
        $response = $this->sevima->get('siakadcloud/v1/fakultas');

        if (! $response['success']) {
            throw new \Exception($response['message']);
        }

        $items = $response['data'];

        $successInsert = 0;
        $successUpdate = 0;
        $skip = 0;
        $failed = 0;
        $errors = [];

        foreach ($items as $item) {
            try {
                $local = Fakultas::where(
                    'fakultas_siakad_id',
                    $item['id']
                )->first();

                $payload = [
                    'nama_fakultas' => $item['nama_fakultas'],
                    'fakultas_singkatan' => $item['fakultas_singkatan'],
                    'is_aktif' => true,
                    'updated_at_siakad' => $item['updated_at'],
                ];

                if (! $local) {
                    Fakultas::create([
                        'fakultas_siakad_id' => $item['id'],
                        ...$payload,
                    ]);

                    $successInsert++;

                    continue;
                }

                if ($local->updated_at_siakad == $item['updated_at']) {
                    $skip++;

                    continue;
                }

                $local->update($payload);

                $successUpdate++;

            } catch (\Throwable $e) {
                $failed++;

                $errors[] = [
                    'fakultas_siakad_id' => $item['id'] ?? null,
                    'nama_fakultas' => $item['nama_fakultas'] ?? null,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return [
            'total' => count($items),
            'success_insert' => $successInsert,
            'success_update' => $successUpdate,
            'skip' => $skip,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }
}
