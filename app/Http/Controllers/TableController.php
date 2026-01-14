<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    // halaman pilih meja
    public function index()
    {
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

        $no = (int) $request->table_no;

        // ✅ KEY UTAMA
        session()->put('table_no', $no);

        session()->put('meja', 'MEJA ' . $no);

        return redirect()->route('menu.index');
    }
}