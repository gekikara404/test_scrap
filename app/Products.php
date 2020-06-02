<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'model','brand_name','price'
    ];

    protected $table = 'products';
    public $timestamps = false;
}
