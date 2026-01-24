<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function show()
    {

        $title = 'Terima kasih! Pembayaran berhasil';
        $subtitle = 'Pesananmu sedang diproses. Mohon tunggu ya 😊';

        return view('customer.thankyou', compact('title', 'subtitle'));
    }

    public function done()
    {
        // ✅ reset SEMUA transaksi, kecuali nomor meja
        session()->forget([
            'cart',
            'payment_method',
            'transfer_selected',
            'transfer_deadline_at',
            'payment_status',
            'order_code',
        ]);

        return redirect()->route('menu.index');
    }
}