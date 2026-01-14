<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Halaman pilih metode pembayaran (ambil total dari cart session)
    public function show(Request $request)
    {
        $cart = $request->session()->get('cart', []); // pastikan cart kamu memang di session('cart')
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += ((int)$item['price']) * ((int)$item['qty']);
        }

        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;

        // default selected
        $selected = $request->session()->get('payment_method', 'transfer');

        return view('customer.payment', compact('total', 'subtotal', 'tax', 'selected'));
    }

    // Klik "Lanjutkan Pembayaran" -> buat order + items, lalu redirect sesuai metode
    public function store(Request $request)
    {
        $request->validate([
            'metode' => 'required|in:transfer,cash'
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Keranjang kosong.');
        }

        $tableNo = $request->session()->get('table_no'); // <<<<< ganti kalau session kamu beda (misal 'meja')
        $metode = $request->input('metode');

        // hitung total
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ((int)$item['price']) * ((int)$item['qty']);
        }
        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;

        $order = DB::transaction(function () use ($tableNo, $metode, $subtotal, $tax, $total, $cart) {
            // buat order dulu
            $o = Order::create([
                'order_code'     => 'TEMP', // nanti diupdate setelah dapat id
                'table_no'       => $tableNo,
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $total,
                'payment_method' => $metode,
                'status'         => $metode === 'cash' ? 'waiting_cashier' : 'pending',
            ]);

            // order_code otomatis dari id
            $o->order_code = 'SJN-' . str_pad((string)$o->id, 6, '0', STR_PAD_LEFT);
            $o->save();

            // simpan items
            foreach ($cart as $item) {
                $price = (int)$item['price'];
                $qty   = (int)$item['qty'];

                OrderItem::create([
                    'order_id'    => $o->id,
                    'name'        => $item['name'],
                    'price'       => $price,
                    'qty'         => $qty,
                    'line_total'  => $price * $qty,
                ]);
            }

            return $o;
        });

        // optional: kosongkan cart biar tidak double order
        $request->session()->forget('cart');

        return redirect()->route('pembayaran.lanjut', $order->id);
    }

    // Router lanjutan: kalau transfer -> halaman transfer, kalau cash -> receipt cash
    public function lanjut(Order $order)
    {
        if ($order->payment_method === 'cash') {
            return redirect()->route('pembayaran.cash', $order->id);
        }
        return redirect()->route('pembayaran.transfer', $order->id);
    }

    // Halaman transfer
    public function transfer(Order $order)
    {
        $order->load('items');

        // dummy metode transfer (contoh seperti UI kamu)
        $methods = [
            ['name' => 'BCA Transfer', 'number' => '1234567890', 'label' => 'a/n Sijeuni Resto'],
            ['name' => 'Mandiri Transfer', 'number' => '0987654321', 'label' => 'a/n Sijeuni Resto'],
            ['name' => 'DANA (E-Wallet)', 'number' => '081234567890', 'label' => 'a/n Sijeuni Resto'],
        ];

        return view('customer.payment_transfer', compact('order', 'methods'));
    }

    // Tombol "Saya Sudah Bayar" (transfer)
    public function transferConfirm(Order $order)
    {// kalau kamu mau auto paid:
        $order->status = 'paid';
        $order->save();

        return redirect()->route('pembayaran.transfer', $order->id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // Halaman struk cash
    public function cash(Order $order)
    {
        $order->load('items');
        return view('customer.payment_cash_receipt', compact('order'));
    }

    // Tombol "Selesai" cash (anggap selesai)
    public function cashDone(Order $order)
    {
        $order->status = 'waiting_cashier';
        $order->save();

        return redirect()->route('pembayaran.cash', $order->id);
    }
}