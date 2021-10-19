<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function coil()
    {
        return $this->belongsTo('App\coil_detail','coil_id', 'id');
       
    }
}
