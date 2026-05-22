<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getData(Request $request)
    {
        $search = $request->search;
        $perPage = min((int) ($request->per_page ?? 10), 100);
        $filter_role = $request->filter_role;

        $query = User::with(['roleUser.role']);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        /*
            |--------------------------------------------------------------------------
            | Filter Role
            |--------------------------------------------------------------------------
            */
        if ($filter_role) {
            $query->whereHas('roleUser.role', function ($q) use ($filter_role) {
                $q->where('nama', $filter_role);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | return sorting and paging
        |--------------------------------------------------------------------------
        */
        return $query
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById($id)
    {
        return User::with(['roleUser.role'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 2,
            ]);

            return $user->load('roleUser.role');
        });
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return $user->fresh();
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $user->roleUser()->delete();

            return $user->delete();
        });
    }

    public function updateRoles($id, array $roles)
    {
        $user = User::findOrFail($id);
        $user->roles()->sync($roles);

        return $user->load('roleUser.role');
    }
}
