<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Http\Resources\UploadResource;
use App\Services\ExceptionService;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class UploadController extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
        protected ExceptionService $exceptionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->uploadService->getData($request);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => UploadResource::collection($data->items()),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                    'next_page_url' => $data->nextPageUrl(),
                    'prev_page_url' => $data->previousPageUrl(),
                ],
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadRequest $request): JsonResponse
    {
        try {
            $data = $this->uploadService->store($request);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => new UploadResource($data),
            ], 201);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->uploadService->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new UploadResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->uploadService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function view(string $uuid)
    {
        try {
            return $this->uploadService->view($uuid);
        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }
}
