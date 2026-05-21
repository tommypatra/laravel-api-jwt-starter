<?php

namespace App\Http\Controllers;

use App\Libraries\Sevima;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index(Request $request, Sevima $sevima, $api_keyword, $id = null, $id2 = null)
    {
        $url = 'siakadcloud/v1/'.$api_keyword;

        if ($id) {
            $url .= '/'.$id;
        }
        if ($id2) {
            $url .= '/'.$id2;
        }

        $response = $sevima->get(
            $url,
            $request->query()
        );

        return response()->json(
            $response['data'],
            $response['status']
        );
    }

    public function loginSevima(Request $request, Sevima $sevima)
    {
        $url = 'siakadcloud/v1/user/login';

        $akun = [
            [
                'email' => 'tommyirawan.patra@gmail.com',
                'password' => '12345678',
            ],
            [
                'email' => 'ismaun85.iainkdi@gmail.com',
                'password' => '85032885@Sia',
            ],
            [
                'email' => 'windawnd809@gmail.com',
                'password' => 'Winda016',
            ],
            [
                'email' => 'jokoaziswetomi@gmail.com',
                'password' => '2024101001',
            ],
            [
                'email' => 'andisakri.p75@gmail.com',
                'password' => 'tabeandi75',
            ],
            [
                'email' => 'Rianaldisupri@gmail.com',
                'password' => '24102008',
            ],
        ];

        $response = $sevima->post(
            $url,
            $akun[0] // $request->all(),
        );

        return response()->json(
            $response['data'],
            $response['status']
        );
    }

    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function periodeMengajar()
    {
        return view('periode-mengajar.index');
    }

    public function distribusiMengajar()
    {
        return view('distribusi-mengajar.index');
    }

    public function jadwal()
    {
        return view('jadwal.index');
    }

    public function dosen()
    {
        return view('master.dosen.index');
    }

    public function programStudi()
    {
        return view('master.program-studi.index');
    }

    public function mataKuliah()
    {
        return view('master.mata-kuliah.index');
    }

    public function ruangan()
    {
        return view('master.ruangan.index');
    }
}
