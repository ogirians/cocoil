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

    public function gudang()
    {
        return $this->belongsTo('App\gudang','gudang_id','id');  
       
    }  

    public function blok()
    {
        return $this->belongsTo('App\blok','blok_id','id');  
       
    }  
    //public $timestamps = false;
}
