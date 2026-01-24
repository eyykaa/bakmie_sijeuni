<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class BackofficeOrdersController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'baru');   // baru | diproses | selesai
        $q   = trim((string) $request->get('q', ''));

        $query = Order::query();

        // filter tab -> status
        if ($tab === 'diproses') {
            $query->where('status', 'diproses');
        } elseif ($tab === 'selesai') {
            $query->where('status', 'selesai');
        } else {
            $tab = 'baru';
            $query->where('status', 'baru');
        }

        // search meja / order_code
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('order_code', 'like', "%{$q}%")
                  ->orWhere('table_no', 'like', "%{$q}%");
            });
        }

        $orders = $query->latest()->paginate(9)->withQueryString();

        return view('backoffice.orders', compact('tab', 'q', 'orders'));
    }

 public function show(Order $order)
    {
        // pastikan items terbaca
        $order->load('items');

        return view('backoffice.order_show', compact('order'));
    }

    // tombol "Verifikasi & Konfirmasi" (baru -> diproses)
    public function verify(Order $order)
    {
        if ($order->status === 'baru') {
            $order->update(['status' => 'diproses']);
        }

        return redirect()->route('backoffice.orders.show', $order)
            ->with('success', 'Pesanan dipindahkan ke DIPROSES.');
    }

    // tombol "Selesai" (diproses -> selesai)
    public function done(Order $order)
    {
        if ($order->status === 'diproses') {
            $order->update(['status' => 'selesai']);
        }

        return redirect()->route('backoffice.orders.show', $order)
            ->with('success', 'Pesanan dipindahkan ke SELESAI.');
    }

    public function printReceipt(Order $order)
    {
        $order->load('items');
        return view('backoffice.print_receipt', compact('order'));
    }

    public function printKitchen(Order $order)
    {
        $order->load('items');
        return view('backoffice.print_kitchen', compact('order'));
    }
}