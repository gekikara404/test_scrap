<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scrape extends Model
{
    protected $fillable = [
        'manufacturer','model','carrier','price'
    ];

    protected $table = 'scrape';
    public $timestamps = false;
}
