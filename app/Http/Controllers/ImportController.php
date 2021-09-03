<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use App\coil_detail;
use App\Imports\CoilsImport;


use Illuminate\Http\Request;

class ImportController extends Controller
{
    function import(Request $request)
    {
     $this->validate($request, [
      'import_file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('import_file')->getRealPath();

     if($request->hasFile('import_file')){

            try{
                Excel::import(new CoilsImport, $path);
            }
            catch (\Exception $e) {
                Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                    foreach ($reader->toArray() as $key => $row) {
                        if(!empty($row)) {
                            $baris = new coil_detail;
                            //$baris->name = $request->name;
                            //DB::table('customers')->insert($data);
                                $baris->item_category = $row['item_category'];
                                $baris->item_type = $row['item_type'];
                                $baris->item_code = $row['item_code'];
                                $baris->item_description = $row['item_description'];
                                $baris->serial_Code = $row['serial_code'];
                                $baris->id_coil = $row['id_coil'];
                                $baris->balance = $row['balance'];

                            $baris->save();
                        }
                    }
                });
            }
            
        }

        return response()->json(['message' => 'uploaded successfully'], 200);
        //return back()->with('success', 'Excel Data Imported successfully');
    }
}
