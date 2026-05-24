<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaRequest;
use App\Http\Requests\ProfilMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Services\ExceptionService;
use App\Services\MahasiswaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class MahasiswaController extends Controller
{
    public function __construct(
        protected MahasiswaService $mahasiswaService,
        protected ExceptionService $exceptionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->mahasiswaService->getData($request);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => MahasiswaResource::collection($data->items()),
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
    public function store(MahasiswaRequest $request): JsonResponse
    {
        try {
            $data = $this->mahasiswaService->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => new MahasiswaResource($data),
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
            $data = $this->mahasiswaService->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new MahasiswaResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function showProfil(): JsonResponse
    {
        try {
            $data = $this->mahasiswaService->findByUserId(Auth::user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new MahasiswaResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function simpanProfil(ProfilMahasiswaRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $payload = [
                ...$request->validated(),
                'user_id' => $userId,
            ];

            $dataMahasiswa = $this->mahasiswaService->findByUserId($userId);

            $data = $this->mahasiswaService->update($dataMahasiswa->id, $payload);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new MahasiswaResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MahasiswaRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->mahasiswaService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new MahasiswaResource($data),
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
            $this->mahasiswaService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }
}
