<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // =========================
    // PILIH METODE PEMBAYARAN
    // =========================
    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu.index');

        [$subtotal, $tax, $total] = $this->calcTotals($cart);

        $selected = session('payment_method', 'transfer'); // transfer | cash
        return view('customer.payment', compact('subtotal', 'tax', 'total', 'selected'));
    }

    public function store(Request $request)
    {
        $method = $request->input('method', 'transfer');
        if (!in_array($method, ['transfer', 'cash'])) $method = 'transfer';

        session(['payment_method' => $method]);
        return redirect()->route('pembayaran.show');
    }

    public function lanjut()
    {
        $method = session('payment_method', 'transfer');
        return $method === 'cash'
            ? redirect()->route('pembayaran.cash')
            : redirect()->route('pembayaran.transfer');
    }

    // =========================
    // TRANSFER PAGE
    // =========================
    public function transfer()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu.index');

        [$subtotal, $tax, $total] = $this->calcTotals($cart);

        // bikin order code (masih di session, belum ke DB)
        $orderCode = session('order_code');
        if (!$orderCode) {
            $orderCode = '#SJ-' . rand(10000, 99999);
            session(['order_code' => $orderCode]);
        }

        // countdown 15 menit
        $deadlineAt = session('transfer_deadline_at');
        $nowTs = now()->timestamp;
        if (!$deadlineAt || (int)$deadlineAt <= $nowTs) {
            $deadlineAt = now()->addMinutes(15)->timestamp;
            session(['transfer_deadline_at' => $deadlineAt]);
        }
        $deadlineSeconds = max(0, (int)$deadlineAt - $nowTs);

        $methods = [
            ['key' => 'bca',     'name' => 'BCA Transfer',        'number' => '1234567890',     'holder' => 'Bakmie Sijeuni', 'logo' => 'bca.jpg'],
            ['key' => 'mandiri', 'name' => 'Mandiri Transfer',    'number' => '0987654321',     'holder' => 'Bakmie Sijeuni', 'logo' => 'mandiri.jpg'],
            ['key' => 'dana',    'name' => 'DANA (E-Wallet)',     'number' => '081234567890',   'holder' => 'Bakmie Sijeuni', 'logo' => 'dana.jpg'],
        ];

        $selected = session('transfer_selected', 'bca');

        // token anti dobel submit
        $submitToken = session('submit_token_transfer');
        if (!$submitToken) {
            $submitToken = (string) Str::uuid();
            session(['submit_token_transfer' => $submitToken]);
        }

        return view('customer.payment_transfer', compact(
            'total',
            'orderCode',
            'deadlineSeconds',
            'methods',
            'selected',
            'submitToken'
        ));
    }

    public function transferConfirm(Request $request)
{
    $order = $this->createOrderFromCart('transfer');

    session(['payment_status' => 'Dibayar']);

    // ✅ HAPUS PENGUNCI AGAR BISA ORDER LAGI
    session()->forget([
        'last_order_id',
        'last_order_code',
        'order_code',
        'submit_token_transfer',
    ]);

    session()->forget([
        'cart',
        'payment_method',
        'transfer_deadline_at',
    ]);

    return redirect()->route('thankyou.show')
        ->with('success', 'Pesanan berhasil dibuat.');
}

    // =========================
    // CASH PAGE
    // =========================
    public function cash()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu.index');

        [$subtotal, $tax, $total] = $this->calcTotals($cart);

        $tableNo = (int) session('table_no');
        if (!$tableNo) return redirect()->route('tables.index');

        $orderCode = session('order_code');
        if (!$orderCode) {
            $orderCode = '#SJ-' . rand(10000, 99999);
            session(['order_code' => $orderCode]);
        }

        $barcodeText = session('cash_barcode');
        if (!$barcodeText) {
            $barcodeText = 'SJ-' . rand(10000, 99999) . '-' . $tableNo . '-CASH';
            session(['cash_barcode' => $barcodeText]);
        }

        $status = session('payment_status', 'waiting_cashier');

        // token anti dobel submit
        $submitToken = session('submit_token_cash');
        if (!$submitToken) {
            $submitToken = (string) Str::uuid();
            session(['submit_token_cash' => $submitToken]);
        }

        return view('customer.payment_cash', compact(
            'cart',
            'subtotal',
            'tax',
            'total',
            'tableNo',
            'orderCode',
            'barcodeText',
            'status',
            'submitToken'
        ));
    }

    public function cashDone()
{
    $order = $this->createOrderFromCart('cash');

    session(['payment_status' => 'Dibayar']);

    // ✅ HAPUS PENGUNCI
    session()->forget([
        'last_order_id',
        'last_order_code',
        'order_code',
        'submit_token_cash',
        'cash_barcode',
    ]);

    session()->forget([
        'cart',
        'payment_method',
    ]);

    return redirect()->route('thankyou.show')
        ->with('success', 'Pesanan berhasil dibuat.');
}

    // =========================
    // HELPERS
    // =========================
    private function normalizeOrderCode(string $orderCodeWithHash): string
    {
        // "#SJ-12345" -> "SJ-12345"
        return ltrim(trim($orderCodeWithHash), '#');
    }

    private function createOrderFromCart(string $paymentMethod): Order
    {
       
        $cart = session('cart', []);
        if (empty($cart)) abort(400, 'Cart kosong.');

        $tableNo = (int) session('table_no');
        if (!$tableNo) abort(400, 'Meja belum dipilih.');

        [$subtotal, $tax, $total] = $this->calcTotals($cart);

        // order code dari session 
        $orderCode = session('order_code') ?: ('#SJ-' . rand(10000, 99999));
        session(['order_code' => $orderCode]);

        $orderCode = $this->normalizeOrderCode($orderCode);

        // pastikan unique
        while (Order::where('order_code', '=', $orderCode)->exists()) {
            $orderCode = 'SJ-' . rand(10000, 99999);
        }

        return DB::transaction(function () use ($cart, $tableNo, $paymentMethod, $subtotal, $tax, $total, $orderCode) {

            $order = Order::create([
                'order_code'     => $orderCode,
                'table_no'       => $tableNo,
                'status'         => 'baru',
                'payment_status' => 'Dibayar', 
                'payment_method' => $paymentMethod, // 'transfer' / 'cash'
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $total,
            ]);

            foreach ($cart as $row) {
                $qty   = (int) ($row['qty'] ?? 0);
                $price = (int) ($row['price'] ?? 0);
                if ($qty <= 0) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $row['id'] ?? null,
                    'name'     => $row['name'] ?? ($row['title'] ?? 'Item'),
                    'price'    => $price,
                    'qty'      => $qty,
                    'subtotal' => $qty * $price,
                ]);
            }

            // simpan id agar dobel klik tidak bikin order baru
            session([
                'last_order_id'   => $order->id,
                'last_order_code' => $order->order_code,
            ]);

            return $order;
        });
    }

    private function calcTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $row) {
            $qty = (int) ($row['qty'] ?? 0);
            $price = (int) ($row['price'] ?? 0);
            $subtotal += $qty * $price;
        }

        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;

        return [$subtotal, $tax, $total];
    }
}