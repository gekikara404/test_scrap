<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iphone_prod extends Model
{
    protected $fillable = [
        'device','condition','model','network','size','price'
    ];

    protected $table = 'iphone_prod';
    public $timestamps = false;
}
