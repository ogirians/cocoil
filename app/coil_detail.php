<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class coil_detail extends Model
{
    protected $guarded = [];  
    
    
    public function location()
    {
        return $this->belongsTo('App\coil_location','location_id','id');  
       
    }  
    //public $timestamps = false;
}
