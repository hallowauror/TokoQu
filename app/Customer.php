<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $table = 'customers';

    protected $primaryKey = 'id_customer';

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

}
