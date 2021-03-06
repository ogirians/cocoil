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

        public function gudang()
        {
            return $this->belongsTo('App\gudang', 'gudang_id','id');  
        
        }

        
        public function blok()
        {
            return $this->belongsTo('App\blok', 'blok_id','id');  
        
        }
}
