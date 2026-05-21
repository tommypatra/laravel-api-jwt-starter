<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class ExceptionService
{
    public function handle(Throwable $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return $this->response(
                'Data tidak ditemukan.',
                404
            );
        }

        if ($e instanceof ValidationException) {
            return $this->response(
                'Data yang dikirim tidak valid.',
                422,
                $e->errors()
            );
        }

        if ($e instanceof QueryException) {
            return $this->handleQueryException($e);
        }

        return $this->response(
            app()->environment('local')
                ? $e->getMessage()
                : 'Terjadi kesalahan sistem.',
            500
        );
    }

    protected function handleQueryException(
        QueryException $e
    ): JsonResponse {
        $errorCode = $e->errorInfo[1] ?? null;

        return match ($errorCode) {

            /*
            |--------------------------------------------------------------------------
            | Constraint / Integrity
            |--------------------------------------------------------------------------
            */

            1062 => $this->response(
                'Data sudah ada (duplikat).',
                409
            ),

            1451 => $this->response(
                'Data tidak dapat dihapus atau diperbarui karena masih digunakan data lain.',
                409
            ),

            1452 => $this->response(
                'Relasi data tidak valid.',
                422
            ),

            /*
            |--------------------------------------------------------------------------
            | Required / Validation-like
            |--------------------------------------------------------------------------
            */

            1048 => $this->response(
                'Ada field wajib yang kosong.',
                422
            ),

            1364 => $this->response(
                'Field wajib belum diisi.',
                422
            ),

            1406 => $this->response(
                'Data terlalu panjang.',
                422
            ),

            1264 => $this->response(
                'Nilai numerik di luar batas.',
                422
            ),

            1366 => $this->response(
                'Format data tidak valid.',
                422
            ),

            1292 => $this->response(
                'Format tanggal atau waktu tidak valid.',
                422
            ),

            /*
            |--------------------------------------------------------------------------
            | Schema / Developer Errors
            |--------------------------------------------------------------------------
            */

            1054 => $this->response(
                app()->environment('local')
                    ? 'Kolom database tidak ditemukan.'
                    : 'Terjadi kesalahan sistem.',
                500
            ),

            1146 => $this->response(
                app()->environment('local')
                    ? 'Tabel database tidak ditemukan.'
                    : 'Terjadi kesalahan sistem.',
                500
            ),

            1064 => $this->response(
                app()->environment('local')
                    ? 'Query database tidak valid.'
                    : 'Terjadi kesalahan sistem.',
                500
            ),

            /*
            |--------------------------------------------------------------------------
            | Authentication / Permission
            |--------------------------------------------------------------------------
            */

            1045 => $this->response(
                'Autentikasi database gagal.',
                503
            ),

            1044 => $this->response(
                'Akses database ditolak.',
                503
            ),

            /*
            |--------------------------------------------------------------------------
            | Connection / Infrastructure
            |--------------------------------------------------------------------------
            */

            1040 => $this->response(
                'Server database sedang sibuk. Silakan coba lagi.',
                503
            ),

            2002 => $this->response(
                'Tidak dapat terhubung ke server database.',
                503
            ),

            2003 => $this->response(
                'Server database tidak dapat dijangkau.',
                503
            ),

            2006 => $this->response(
                'Koneksi database terputus. Silakan coba lagi.',
                503
            ),

            2013 => $this->response(
                'Koneksi ke database terputus saat proses berjalan.',
                503
            ),

            /*
            |--------------------------------------------------------------------------
            | Concurrency
            |--------------------------------------------------------------------------
            */

            1205 => $this->response(
                'Data sedang digunakan proses lain. Silakan coba lagi.',
                409
            ),

            1213 => $this->response(
                'Terjadi konflik proses database. Silakan coba lagi.',
                409
            ),

            /*
            |--------------------------------------------------------------------------
            | Storage / Resource
            |--------------------------------------------------------------------------
            */

            1021 => $this->response(
                'Storage database penuh atau tidak tersedia.',
                503
            ),

            1037 => $this->response(
                'Memori database tidak mencukupi.',
                503
            ),

            1038 => $this->response(
                'Sort buffer database tidak mencukupi.',
                503
            ),

            1153 => $this->response(
                'Ukuran data yang dikirim terlalu besar.',
                413
            ),

            /*
            |--------------------------------------------------------------------------
            | Fallback
            |--------------------------------------------------------------------------
            */

            default => $this->response(
                app()->environment('local')
                    ? $e->getMessage()
                    : 'Terjadi kesalahan database.',
                500
            ),
        };
    }

    protected function response(
        string $message,
        int $status,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
