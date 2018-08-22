<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Categories extends Model
{

    protected $table = 'categories';
    public $timestamps = false;

    public function products() {
        return $this->belongsToMany(Product::class, 'products_categories');
    }

}
