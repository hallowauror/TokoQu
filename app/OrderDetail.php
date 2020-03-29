<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded = [];
​
    //Model relationships ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
