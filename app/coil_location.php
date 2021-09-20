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

        public function coil_not_empty()
        {
            return $this->belongsTo('App\coil_detail','coil_id','id');  
        
        }

        public function blok()
        {
            return $this->belongsTo('App\blok', 'blok_id','id');  
        
        }
}
