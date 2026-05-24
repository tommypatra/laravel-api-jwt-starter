<?php

namespace App\Services;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiService
{
    public function getData(Request $request)
    {
        $search = $request->search;
        $perPage = min((int) ($request->per_page ?? 10), 100);
        $filter_status = $request->filter_status;

        $query = Pegawai::query()
            ->leftJoin('users', 'users.id', '=', 'pegawai.user_id')
            ->select('pegawais.*')
            ->with('user');
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
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
        if ($filter_status) {
            $query->where(function ($q) use ($filter_status) {
                $q->where('status', $filter_status);
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
        return Pegawai::with(['user'])->findOrFail($id);
    }

    public function findByUserId($id)
    {
        return Pegawai::with(['user'])->where('user_id', $id)->firstOrFail();
    }

    public function store(array $data)
    {
        return Pegawai::create($data);
    }

    public function update($id, array $data)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($data);

        return $pegawai->fresh();
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        return $pegawai->delete();
    }
}
