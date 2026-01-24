<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackofficeAuthSimpleController extends Controller
{
    public function showLogin()
    {
        return view('backoffice.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = env('BACKOFFICE_USER', 'kasir');
        $pass = env('BACKOFFICE_PASS', '123456');

        if ($data['identity'] !== $user || $data['password'] !== $pass) {
            return back()->withErrors([
                'identity' => 'Username/email atau password salah.',
            ])->withInput();
        }

        session([
            'backoffice_logged_in' => true,
            'backoffice_name' => $data['identity'],
        ]);

        return redirect()->route('backoffice.dashboard');
    }

    public function logout()
    {
        session()->forget(['backoffice_logged_in', 'backoffice_name']);
        return redirect()->route('backoffice.login');
    }
}