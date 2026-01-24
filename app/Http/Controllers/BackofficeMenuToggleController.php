<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackofficeMenuToggleController extends Controller
{
    public function index()
    {
        // PAKAI MODEL YANG SUDAH ADA DI PROJECT KAMU
        // di screenshot kamu ada Models/Menu.php, jadi harusnya:
        $menus = \App\Models\Menu::orderBy('id')->get();

        return view('backoffice.menu', compact('menus'));
    }

    public function toggle($menu)
    {
        $m = \App\Models\Menu::findOrFail($menu);

        $m->update([
            'is_active' => ! (bool) $m->is_active
        ]);

        return back();
    }
}