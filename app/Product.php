<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'id_product';

    protected $guarded = [];

    // Model product realtionship to category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
