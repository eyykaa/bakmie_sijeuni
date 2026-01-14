<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    // halaman pilih meja
    public function index()
    {
        // daftar meja (ubah kalau kamu mau lebih banyak)
        $tables = range(1, 10);

        // ambil meja dari session, default 1
        $selected = session('table_no', 1);

        return view('customer.select_table', compact('tables', 'selected'));
    }

    // simpan meja terpilih
    public function store(Request $request)
    {
        $request->validate([
            'table_no' => 'required|integer|min:1|max:100',
        ]);

        session(['table_no' => (int)$request->table_no]);

        return redirect()->route('menu.index');
    }
}