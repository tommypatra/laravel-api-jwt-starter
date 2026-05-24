<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\ExceptionService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ExceptionService $exceptionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data_respon = $this->userService->getData($request);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => UserResource::collection($data_respon->items()),
                'pagination' => [
                    'current_page' => $data_respon->currentPage(),
                    'last_page' => $data_respon->lastPage(),
                    'per_page' => $data_respon->perPage(),
                    'total' => $data_respon->total(),
                    'from' => $data_respon->firstItem(),
                    'to' => $data_respon->lastItem(),
                    'next_page_url' => $data_respon->nextPageUrl(),
                    'prev_page_url' => $data_respon->previousPageUrl(),
                ],
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => new UserResource($data),
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
            $data = $this->userService->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new UserResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->userService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new UserResource($data),
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
            $this->userService->destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function updateRoles(RoleUserRequest $request, $id): JsonResponse
    {
        $data = $this->userService->updateRoles(
            $id,
            $request->roles
        );

        return response()->json([
            'success' => true,
            'message' => 'Role user berhasil diperbarui.',
            'data' => new UserResource($data),
        ]);
    }

    /**
     * Identitas
     */
    public function dataIdentitas(): JsonResponse
    {
        try {
            $role = $this->userService->findById(Auth::user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil diambil',
                'data' => new UserResource($role),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }

    }

    public function ubahDataIdenitas(UserRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->update(Auth::user()->id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => new UserResource($data),
            ]);

        } catch (Throwable $e) {
            return $this->exceptionService->handle($e);
        }
    }
}
