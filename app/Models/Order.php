<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'order_code','table_no','status',
    'payment_status','payment_method',
    'subtotal','tax','total'
  ];

  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }
}