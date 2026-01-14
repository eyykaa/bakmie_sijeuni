<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('customer.payment', compact('subtotal','tax','total','selected'));
    }

    public function store(Request $request)
    {
        $method = $request->input('method', 'transfer');
        if (!in_array($method, ['transfer','cash'])) $method = 'transfer';

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

    // hitung total (+ pajak 10%)
    [$subtotal, $tax, $total] = $this->calcTotals($cart);

    // order code simulasi
    $orderCode = session('order_code');
    if (!$orderCode) {
        $orderCode = '#SJ-' . rand(10000, 99999);
        session(['order_code' => $orderCode]);
    }

    // countdown 15 menit (kalau sudah lewat, reset lagi)
    $deadlineAt = session('transfer_deadline_at');
    $nowTs = now()->timestamp;

    if (!$deadlineAt || (int)$deadlineAt <= $nowTs) {
        $deadlineAt = now()->addMinutes(15)->timestamp;
        session(['transfer_deadline_at' => $deadlineAt]);
    }

    $deadlineSeconds = max(0, (int)$deadlineAt - $nowTs);

    // metode transfer yang ditampilkan
    $methods = [
        [
            'key'    => 'bca',
            'name'   => 'BCA Transfer',
            'number' => '1234567890',
            'holder' => 'Bakmie Sijeuni',
            'logo'   => 'bca.jpg',
        ],
        [
            'key'    => 'mandiri',
            'name'   => 'Mandiri Transfer',
            'number' => '0987654321',
            'holder' => 'Bakmie Sijeuni',
            'logo'   => 'mandiri.jpg',
        ],
        [
            'key'    => 'dana',
            'name'   => 'DANA (E-Wallet)',
            'number' => '081234567890',
            'holder' => 'Bakmie Sijeuni',
            'logo'   => 'dana.jpg',
        ],
    ];

    $selected = session('transfer_selected', 'bca');

    return view('customer.payment_transfer', compact(
        'total',
        'orderCode',
        'deadlineSeconds',
        'methods',
        'selected'
    ));
}

public function transferConfirm(Request $request)
{
    $method = $request->input('method', 'bca');
    session(['transfer_selected' => $method]);

    // status bayar
    session(['payment_status' => 'paid']);

    session()->forget('cart');
    session()->forget('payment_method');
    session()->forget('transfer_deadline_at');

    return redirect()->route('thankyou.show')
        ->with('success', 'Pembayaran berhasil dikonfirmasi.');
}

    // =========================
    // CASH PAGE
    // =========================
    public function cash()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('menu.index');

        [$subtotal, $tax, $total] = $this->calcTotals($cart);

        $tableNo = session('table_no'); // integer
        if (!$tableNo) return redirect()->route('tables.index');

        $orderCode = session('order_code');
        if (!$orderCode) {
            $orderCode = '#SJ-' . rand(10000, 99999);
            session(['order_code' => $orderCode]);
        }

        $barcodeText = 'SJ-' . (string)rand(10000, 99999) . '-' . $tableNo . '-CASH';
        session(['cash_barcode' => session('cash_barcode', $barcodeText)]);

        // status cash (untuk badge “menunggu kasir”)
        $status = session('payment_status', 'waiting_cashier');

        return view('customer.payment_cash', [
            'cart'        => $cart,
            'subtotal'    => $subtotal,
            'tax'         => $tax,
            'total'       => $total,'tableNo'     => $tableNo,
            'orderCode'   => $orderCode,
            'barcodeText' => session('cash_barcode'),
            'status'      => $status,
        ]);
    }

  public function cashDone()
{
    session(['payment_status' => 'paid']);

    // ✅ kosongkan cart & reset pilihan bayar
    session()->forget('cart');
    session()->forget('payment_method');

    return redirect()->route('thankyou.show');
}

    // =========================
    // HELPER
    // =========================
    private function calcTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $row) {
            $qty = (int)($row['qty'] ?? 0);
            $price = (int)($row['price'] ?? 0);
            $subtotal += $qty * $price;
        }

        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;

        return [$subtotal, $tax, $total];
    }

}