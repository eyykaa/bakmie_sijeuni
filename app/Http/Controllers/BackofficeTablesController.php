<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use Illuminate\Http\Request;

class BackofficeTablesController extends Controller
{
    public function index()
    {
       $tables = DiningTable::orderByRaw('CAST(table_no AS UNSIGNED)')->get();
        return view('backoffice.tables', compact('tables'));
    }

    public function toggle(DiningTable $table)
    {
        $table->update([
            'is_active' => ! $table->is_active,
        ]);

        return back();
    }
}