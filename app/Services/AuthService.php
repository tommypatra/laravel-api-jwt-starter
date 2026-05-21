<?php

namespace App\Services;

use App\Libraries\Sevima;
use App\Models\Dosen;
use App\Models\RoleUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $sevima;

    public function __construct(Sevima $sevima)
    {
        $this->sevima = $sevima;
    }

    public function authWeb(array $payload): array
    {
        if (! Auth::attempt([
            'email' => $payload['email'],
            'password' => $payload['password'],
        ])) {
            throw new Exception('Email atau password salah');
        }

        $user = Auth::user();

        return $this->returnAuthenticatedUser($user);

    }

    public function authByEmail(string $email): array
    {
        $response = $this->sevima->get(
            'siakadcloud/v1/user',
            [
                'f-email' => $email,
            ]);

        if ($response['status'] !== 200) {
            throw new Exception(
                $response['data']['errors']['detail'] ?? 'Login gagal'
            );
        }

        $dataResponse = $response['data']['data'];

        if (count($dataResponse) < 1) {
            throw new Exception(
                $response['data']['errors']['detail'] ?? 'Email '.$email.' tidak terdaftar'
            );

        }

        $data_user = $dataResponse[0]['attributes'];
        $data_roles = $data_user['relation']['user-role'];

        return $this->processAuthenticatedUser($data_user, $data_roles);

    }

    public function authCheck(array $payload): array
    {
        $response = $this->sevima->post(
            'siakadcloud/v1/user/login',
            $payload
        );

        // dd($response);

        if ($response['status'] !== 200) {
            throw new Exception(
                $response['data']['errors']['detail'] ?? 'Login gagal'
            );
        }

        $data_user = $response['data']['attributes'];
        $data_roles = $data_user['role'];

        return $this->processAuthenticatedUser($data_user, $data_roles);
    }

    public function processAuthenticatedUser(array $data_user, array $data_roles): array
    {
        $roles = collect($data_roles);

        $allowedRoles = [
            'adfak',
            'admak',
            'peg',
            'adpro',
            'dosen',
        ];

        $userRoles = $roles
            ->pluck('id_role')
            ->toArray();

        if (empty(array_intersect($userRoles, $allowedRoles))) {
            throw new Exception(
                'Anda tidak memiliki akses ke aplikasi ini'
            );
        }

        $isDosen = in_array('dosen', $userRoles);

        $pegawai = $roles->first(function ($role) {
            return ! empty($role['nip']);
        });

        $nip = $pegawai['nip'] ?? null;

        DB::beginTransaction();

        try {
            $user = User::firstOrCreate(
                [
                    'user_siakad_id' => $data_user['user_id'],
                ],
                [
                    'name' => $data_user['nama'],
                    'email' => $data_user['email'],
                    'password' => bcrypt(str()->random(32)),
                ]
            );

            $roleAplikasi = [2];

            if ($isDosen) {
                $roleAplikasi[] = 4;

                Dosen::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'nip' => $nip,
                    ]
                );
            }

            foreach ($roleAplikasi as $roleId) {
                RoleUser::firstOrCreate([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::commit();

            return $this->returnAuthenticatedUser($user);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function returnAuthenticatedUser($data_user): array
    {

        $user = User::where('id', $data_user->id)->first();

        $roles_user = RoleUser::with('role')
            ->where('user_id', $user->id)
            ->orderBy('role_id')
            ->get();

        $defaultRole = $roles_user->first();

        $token = JWTAuth::claims([
            'user_id' => $user->id,
            'user_siakad_id' => $user['user_siakad_id'],
            'email' => $user['email'],
        ])->fromUser($user);

        return [
            'token' => $token,
            'user_id' => $user->id,
            'user_siakad_id' => $user['user_siakad_id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'roles' => $roles_user,
            'role_default' => $defaultRole?->role?->nama,
            'role_id_default' => $defaultRole?->role_id,
        ];
    }
}
