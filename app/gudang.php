<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gudang extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'id';
    
    public function blok()
    {
        return $this->hasMany('App\blok');
       
    }

   /* public function coil_location()
    {
    return $this->hasManyThrough('App\coil_location', 'App\blok');
    }*/

  
}
