<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaService
{
    public function getData(Request $request)
    {
        $search = $request->search;
        $perPage = min((int) ($request->per_page ?? 10), 100);
        $filter_program_studi = $request->filter_program_studi;

        $query = Mahasiswa::query()
            ->leftJoin('users', 'users.id', '=', 'mahasiswa.user_id')
            ->select('mahasiswas.*')
            ->with('user');
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        /*
            |--------------------------------------------------------------------------
            | Filter status
            |--------------------------------------------------------------------------
            */
        if ($filter_program_studi) {
            $query->where(function ($q) use ($filter_program_studi) {
                $q->where('program_studi', $filter_program_studi);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | return sorting and paging
        |--------------------------------------------------------------------------
        */
        return $query
            ->orderBy('users.name', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById($id)
    {
        return Mahasiswa::with(['user'])->findOrFail($id);
    }

    public function findByUserId($id)
    {
        return Mahasiswa::with(['user'])->where('user_id', $id)->firstOrFail();
    }

    public function store(array $data)
    {
        return Mahasiswa::create($data);
    }

    public function update($id, array $data)
    {
        $Mahasiswa = Mahasiswa::findOrFail($id);
        $Mahasiswa->update($data);

        return $Mahasiswa->fresh();
    }

    public function destroy($id)
    {
        $Mahasiswa = Mahasiswa::findOrFail($id);

        return $Mahasiswa->delete();
    }
}
