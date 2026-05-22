<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleService
{
    public function getData(Request $request)
    {
        $search = $request->search;
        $perPage = min((int) $request->per_page ?: 10, 100);
        $perPage = min((int) ($request->per_page ?? 10), 100);
        $isAdmin = $request->is_admin;

        $query = Role::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */
        if (! is_null($isAdmin) && $isAdmin !== '') {
            $query->where('is_admin', $isAdmin);
        }

        /*
        |--------------------------------------------------------------------------
        | return sorting and paging
        |--------------------------------------------------------------------------
        */
        return $query
            ->orderBy('nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById($id)
    {
        return Role::findOrFail($id);
    }

    public function store(array $data)
    {
        return Role::create($data);
    }

    public function update($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);

        return $role->fresh();
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        return $role->delete();
    }
}
