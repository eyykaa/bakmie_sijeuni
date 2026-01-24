<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(Request $request, $category = 'makanan')
    {
        // ROUTE kamu nyimpen session: session(['meja' => 'MEJA '.$no])
        if (!session()->has('meja')) {
            return redirect()->route('tables.index');
        }

        $categories = [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'dessert' => 'Dessert',
        ];

        if (!isset($categories[$category])) $category = 'makanan';
        $active = $category;

        $all = [
            'makanan' => [
                ['id'=>1,'name'=>'Mie Cinta','price'=>35000,'image'=>'mie1.jpg'],
                ['id'=>2,'name'=>'Mie Spesial','price'=>28000,'image'=>'mie2.jpg'],
                ['id'=>3,'name'=>'Mie Jumbo','price'=>32000,'image'=>'mie3.jpg'],
                ['id'=>4,'name'=>'Mie Komplit','price'=>40000,'image'=>'mie4.jpg'],
                ['id'=>5,'name'=>'Mie Biasa','price'=>20000,'image'=>'mie5.jpg'],
                ['id'=>6,'name'=>'Mie Sederhana','price'=>25000,'image'=>'mie6.jpg'],
            ],
            'minuman' => [
                ['id'=>7,'name'=>'Matcha Love','price'=>10000,'image'=>'matcha.jpg'],
                ['id'=>8,'name'=>'Chocolate Cute','price'=>12000,'image'=>'coco.jpg'],
                ['id'=>9,'name'=>'Lemon Pretty','price'=>8000,'image'=>'lemon.jpg'],
                ['id'=>10,'name'=>'Es Teh','price'=>6000,'image'=>'teh.jpg'],
                ['id'=>11,'name'=>'Coffee','price'=>10000,'image'=>'coffee.jpg'],
                ['id'=>12,'name'=>'Air Mineral','price'=>4000,'image'=>'air.jpg'],
            ],
            'dessert' => [
                ['id'=>13,'name'=>'Cake Purple','price'=>18000,'image'=>'cake.jpg'],
                ['id'=>14,'name'=>'Banana EsCream','price'=>15000,'image'=>'banana.jpg'],
                ['id'=>15,'name'=>'Sweet EsCream','price'=>17000,'image'=>'sweet.jpg'],
            ],
        ];

        $items = $all[$active];

        $cart = session('cart', []);
        $cartCount = 0;
        $cartTotal = 0;

        foreach ($cart as $row) {
            $qty = (int)($row['qty'] ?? 0);
            $price = (int)($row['price'] ?? 0);
            $cartCount += $qty;
            $cartTotal += $qty * $price;
        }

        return view('customer.menu', compact('categories','active','items','cartCount','cartTotal'));
    }
}