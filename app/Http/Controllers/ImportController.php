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

            $import = new CoilsImport();
            $import->import($path);

            $error_excel=[];
            //dd($import->errors());
            if($import->failures()){
               // dd($import->failures()->);
               foreach ($import->failures() as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
                $pesan = "pada baris ke-". $failure->row()." ". $failure->attribute()." ".$failure->errors()[0];
                array_push($error_excel, $pesan);  

               
            }
            
            //dd($error_excel);
            }  
                //Excel::import(new CoilsImport, $path);
      
        }

        return response()->json(['message' => 'uploaded successfully', 'error_excell' => !empty($error_excel) ? $error_excel : null], 200);
        //return back()->with('success', 'Excel Data Imported successfully');
    }
}
