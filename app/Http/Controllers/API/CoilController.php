<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CoilCollection;
use App\coil_detail;
use App\coil_location;


class coilController extends Controller
{
    public function index()
    {
        $coil = coil_detail::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $coil = $coil->where('serial_Code', 'LIKE', '%' . request()->q . '%')->orWhere('item_code', 'LIKE', '%' . request()->q . '%');
        }
        return new CoilCollection($coil->paginate(10));
    }

    public function coilnoplace()
    {
        $coil = coil_detail::with('location')->orderBy('created_at', 'DESC')->where('location_id', null);

        if (request()->q != '') {
            $coil = $coil->where('serial_Code', 'LIKE', '%' . request()->q . '%')->orWhere('item_code', 'LIKE', '%' . request()->q . '%')->get();    
        } else {
            $coil = $coil->get();    
        }

       //return new CoilCollection($coil->paginate(10));
        return response()->json(['status' => 'success', 'data' => $coil], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'item_category' => 'required|exists:coils,code',
            'item_type' => 'required|string|max:100',
            'item_code' => 'required|string',
            'item_description' => 'required|max:13',
            'serial_Code' => 'required|string|unique',
            'id_coil' => 'required|string',
            'balance' => 'required|string',
        ]);

        coil_detail::create($request->all());

        $coil = coil_detail::where('id_coil', $request->id_coil)->first();
       
        $id_coil_detail = $coil->id;

        coil_location::create([
            'coil_id' => $id_coil_detail
        ]);


        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $coil = coil_detail::whereid($id)->first();
        return response()->json(['status' => 'success', 'data' => $coil], 200);
    }

    public function detail($id)
    {
        $coil = coil_detail::where('serial_code', $id)->first();
        return response()->json(['status' => 'success', 'data' => $coil], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'item_category' => 'required',
            'item_type' => 'required|max:100',
            'item_code' => 'required',
            'item_description' => 'required|max:13',
            'serial_Code' => 'required|exists:coil_details,serial_Code',
            'id_coil' => 'required|',
            'balance' => 'required|',

        ]);

        $coil = coil_detail::whereid($id)->first();
        $coil->update($request->except('id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $coil = coil_detail::find($id);
        $coil->delete();
        return response()->json(['status' => 'success'], 200);
    }

}
