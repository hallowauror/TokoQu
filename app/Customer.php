<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $table = 'customers';

    protected $primaryKey = 'id_customer';

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
