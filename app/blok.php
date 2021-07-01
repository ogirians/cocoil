<?php

namespace App;
use App\gudang;

use Illuminate\Database\Eloquent\Model;

class blok extends Model
{
    protected $guarded = [];

    public function gudang()
    {
        return $this->belongsTo('App\gudang');
       
    }
}
