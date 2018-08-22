<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Offers extends Model
{

    protected $table = 'offers';
    public $timestamps = false;

    public function products() {
        return $this->belongsToMany(Product::class, 'products_offers');
    }

}
