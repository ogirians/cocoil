<?php

namespace App\Imports;

use App\coil_detail;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CoilsImport implements ToModel,WithHeadingRow,WithValidation,SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
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

    public function rules(): array
    {
        return [
            'serial_code' => Rule::unique('coil_details','serial_Code'), // Table name, field in your db
        ];
    }

    public function customValidationMessages()
    {
        return [
            'serial_code.unique' => 'duplicate',
        ];
    }

    public function customValidationAttributes()
    {
        return 
        [
            'serial_code' => 'Serial code nya'
        ];
    }
}
