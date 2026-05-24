<?php

namespace App\Http\Controllers;

use App\Http\Requests\PegawaiRequest;
use App\Http\Requests\ProfilPegawaiRequest;
use App\Http\Resources\PegawaiResource;
use App\Services\ExceptionService;
use App\Services\PegawaiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PegawaiController extends Controller
{
    public function __construct(
        protected PegawaiService $pegawaiService,
        protected ExceptionService $exceptionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->pegawaiService->getData($request);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => PegawaiResource::collection($data->items()),
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
    public function store(PegawaiRequest $request): JsonResponse
    {
        try {
            $data = $this->pegawaiService->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => new PegawaiResource($data),
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
            $data = $this->pegawaiService->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new PegawaiResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function showProfil(): JsonResponse
    {
        try {
            $data = $this->pegawaiService->findByUserId(Auth::user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new PegawaiResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function simpanProfil(ProfilPegawaiRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $payload = [
                ...$request->validated(),
                'user_id' => $userId,
            ];

            $dataPegawai = $this->pegawaiService->findByUserId($userId);

            $data = $this->pegawaiService->update($dataPegawai->id, $payload);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new PegawaiResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PegawaiRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->pegawaiService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new PegawaiResource($data),
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
            $this->pegawaiService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }
}
