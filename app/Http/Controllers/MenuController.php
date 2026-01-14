<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, $category = 'makanan')
    {
        if (!session()->has('table')) {
    return redirect()->route('tables.index');}
        // 3 kategori saja
        $categories = [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'dessert' => 'Dessert',
        ];

        if (!isset($categories[$category])) $category = 'makanan';
        $active = $category;

        // Data dummy (nanti bisa diganti dari DB)
        $all = [
            'makanan' => [
                ['id'=>1,'name'=>'Nasi Goreng Sijeuni','price'=>35000,'image'=>'nasi-goreng.jpg'],
                ['id'=>2,'name'=>'Mie Ayam Spesial','price'=>28000,'image'=>'mie-ayam.jpg'],
                ['id'=>3,'name'=>'Ayam Geprek Sijeuni','price'=>32000,'image'=>'ayam-geprek.jpg'],
            ],
            'minuman' => [
                ['id'=>4,'name'=>'Es Teh Manis','price'=>10000,'image'=>'es-teh.jpg'],
                ['id'=>5,'name'=>'Es Jeruk','price'=>12000,'image'=>'es-jeruk.jpg'],
                ['id'=>6,'name'=>'Air Mineral','price'=>8000,'image'=>'air-mineral.jpg'],
            ],
            'dessert' => [
                ['id'=>7,'name'=>'Mochi','price'=>18000,'image'=>'mochi.jpg'],
                ['id'=>8,'name'=>'Puding Coklat','price'=>15000,'image'=>'puding.jpg'],
                ['id'=>9,'name'=>'Ice Cream','price'=>17000,'image'=>'ice-cream.jpg'],
            ],
        ];

        $items = $all[$active];

        // hitung cart untuk bottom bar
        $cart = session('cart', []);
        $cartCount = 0;
        $cartTotal = 0;
        foreach ($cart as $row) {
            $cartCount += (int)($row['qty'] ?? 0);
            $cartTotal += ((int)($row['qty'] ?? 0)) * ((int)($row['price'] ?? 0));
        }

        return view('customer.menu', compact('categories','active','items','cartCount','cartTotal'));
    }
}