<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);

        // meja dari session: contoh "MEJA 8"
        $tableText = session('meja', 'MEJA ?');
        $tableNo = (int) preg_replace('/\D+/', '', $tableText);

        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $row) {
            $qty   = (int) ($row['qty'] ?? 1);
            $price = (int) ($row['price'] ?? 0);

            $lineSubtotal = $price * $qty;
            $subtotal += $lineSubtotal;

            $items[] = [
                'key' => $key, // ini penting buat hapus
                'name' => $row['name'] ?? '-',
                'price' => $price,
                'qty' => $qty,
                'note' => $row['note'] ?? null,
                'image' => $row['image'] ?? null,
                'line_subtotal' => $lineSubtotal,
            ];
        }

        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;
        $count = collect($items)->sum('qty');

        return view('customer.order_detail', compact(
            'items',
            'subtotal',
            'tax',
            'total',
            'count',
            'tableText',
            'tableNo'
        ));
    }

    public function remove(Request $request)
    {
        $key = $request->input('key'); // sesuai hidden input di blade

        $cart = session('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.show')->with('success', 'Item berhasil dihapus.');
    }
}