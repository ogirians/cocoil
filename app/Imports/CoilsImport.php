<?php

namespace App\Imports;

use App\coil_detail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoilsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new coil_detail([    
           'item_category'      => $row['item_category'],
           'item_type'          => $row['item_type'], 
           'item_code'          => $row['item_code'],
           'item_description'   => $row['item_description'],
           'serial_code'        => $row['serial_code'],
           'id_coil'            => $row['id_coil'],
           'balance'            => $row['balance'],
        ]);
    }
}
