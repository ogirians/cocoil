<?php

namespace App;
use App\gudang;

use Illuminate\Database\Eloquent\Model;

class blok extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function gudang()
    {
        return $this->belongsTo('App\gudang');
       
    }

    public function coil_location()
    {
        return $this->hasMany('App\coil_location','blok_id','id');
       
    }
}
