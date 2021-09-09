<?php

namespace App;
use App\coil_detail;

use Illuminate\Database\Eloquent\Model;

class coil_location extends Model
{
    protected $guarded = [];  
    
    
    public function coil()
    {
        return $this->belongsTo('App\coil_detail','coil_id','id');  
       
    }
}
