<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{

    protected $table = 'products';
    public $timestamps = false;

    public function offers() {
        return $this->belongsToMany(Offers::class, 'products_offers');
    }


    public function categories() {
        return $this->belongsToMany(Categories::class, 'products_categories');
    }
}
