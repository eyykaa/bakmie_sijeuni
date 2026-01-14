<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);

        $count = 0;
        $subtotal = 0;

        foreach ($cart as $row) {
            $qty = (int)($row['qty'] ?? 0);
            $price = (int)($row['price'] ?? 0);

            $count += $qty;
            $subtotal += $qty * $price;
        }

        $tax = (int) round($subtotal * 0.10);
        $total = $subtotal + $tax;

        $tableNo = session('table_no');  
        $mejaText = session('meja');    

        $tableNoFinal = $tableNo;

        if (!$tableNoFinal && $mejaText) {
            if (preg_match('/\d+/', $mejaText, $m)) {
                $tableNoFinal = (int) $m[0];
            }
        }

        return view('customer.order_detail', compact(
            'cart','count','subtotal','tax','total',
            'tableNoFinal'
        ));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'id'    => 'required',
            'name'  => 'required|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|string',
            'note'  => 'nullable|string|max:255',
            'qty'   => 'required|integer|min:1|max:99',
        ]);

        $cart = session('cart', []);
        $id = (string) $data['id'];

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = (int)$cart[$id]['qty'] + (int)$data['qty'];

            $newNote = trim((string)($data['note'] ?? ''));
            if ($newNote !== '') {
                $cart[$id]['note'] = $newNote;
            }
        } else {
            $cart[$id] = [
                'id'    => $id,
                'name'  => $data['name'],
                'price' => (int)$data['price'],
                'image' => $data['image'] ?? null,
                'note'  => trim((string)($data['note'] ?? '')),
                'qty'   => (int)$data['qty'],
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Item ditambahkan ke pesanan.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id'  => 'required',
            'qty' => 'required|integer|min:1|max:99',
            'note' => 'nullable|string|max:255',
        ]);

        $cart = session('cart', []);
        $id = (string) $data['id'];

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = (int) $data['qty'];
            $cart[$id]['note'] = trim((string)($data['note'] ?? ''));
            session(['cart' => $cart]);
        }

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $data = $request->validate(['id' => 'required']);

        $cart = session('cart', []);
        $id = (string) $data['id'];

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Pesanan dikosongkan.');
    }
}