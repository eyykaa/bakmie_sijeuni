<?php

namespace App\Http\Controllers;

use App\Models\Order;

class BackofficeDashboardController extends Controller
{
    public function index()
    {
        $countBaru = Order::where('status', 'baru')->count();
        $countDiproses = Order::where('status', 'diproses')->count();
        $countSelesai = Order::where('status', 'selesai')->count();

        $totalSales = (int) Order::sum('total');

        $latestOrders = Order::orderByDesc('created_at')->limit(10)->get();

        return view('backoffice.dashboard', compact(
            'countBaru',
            'countDiproses',
            'countSelesai',
            'totalSales',
            'latestOrders'
        ));
    }
}