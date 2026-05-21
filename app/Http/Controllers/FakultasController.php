<?php

namespace App\Http\Controllers;

use App\Http\Requests\FakultasRequest;
use App\Http\Resources\FakultasResource;
use App\Services\ExceptionService;
use App\Services\FakultasService;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function __construct(
        protected FakultasService $fakultasService,
        protected ExceptionService $exceptionService
    ) {}

    public function index(Request $request)
    {
        try {
            $data = $this->fakultasService->index(
                $request->only([
                    'search',
                    'per_page',
                    'is_aktif',
                    'fakultas_siakad_id',
                    'fakultas_singkatan',
                ])
            );

            return FakultasResource::collection($data);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->fakultasService->show($id);

            return new FakultasResource($data);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function store(FakultasRequest $request)
    {
        try {
            $data = $this->fakultasService->store(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => new FakultasResource($data),
            ], 201);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function update(FakultasRequest $request, int $id)
    {
        try {
            $data = $this->fakultasService->update(
                $id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'data' => new FakultasResource($data),
            ]);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->fakultasService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function previewSync()
    {
        try {
            $data = $this->fakultasService->previewSyncFromSiakad();

            return response()->json([
                'success' => true,
                'message' => 'Preview sinkronisasi berhasil.',
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    public function sync()
    {
        try {
            $data = $this->fakultasService->syncFromSiakad();

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi selesai.',
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }
}
