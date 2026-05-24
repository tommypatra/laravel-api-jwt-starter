<?php

namespace App\Http\Controllers;

class WebController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function user()
    {
        return view('dashboard.user');
    }

    public function role()
    {
        return view('dashboard.role');
    }

    public function identitas()
    {
        return view('dashboard.identitas');
    }

    public function pegawaiData()
    {
        return view('dashboard.pegawai_data');
    }
}
