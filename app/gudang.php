<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gudang extends Model
{
    protected $guarded = [];

    
    public function blok()
    {
        return $this->hasMany('App\blok');
       
    }
}
