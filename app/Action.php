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

    public function user()
    {
        return $this->belongsTo('App\User','user_id', 'id');
       
    }
}
