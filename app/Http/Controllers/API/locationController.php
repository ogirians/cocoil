<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\location;
use App\coil_location;
use App\coil_detail;

class locationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $location = coil_location::with('coil')->orderBy('updated_at', 'ASC');
        if (request()->q != '') {
            $location = $location->where('blok_id', request()->q)->get();
            //dd($location->coil->item_description);
        } else {
            $location = $location->get();
        }
        return response()->json(['status' => 'success', 'data' => $location], 200);
        //return new location($location);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'coil_id' =>'',
            'gudang_id' => 'required',
            'height' => 'required',
            'width' => 'required',
            'nameRect' => 'required',
            'x' => 'required',
            'y' => 'required',
            'scaleX' => 'required',
            'scaleY' => 'required',
            'rotation' => 'required',
            'slot_id' => 'required',
            'slot_name' => 'required',
        ]);

        coil_location::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //dd($request->x);
       
        //dd($coil->id);

        $gudang = coil_location::where('slot_id', $id)->first();
        $gudang->update([
            'x' => $request->x,
            'y' => $request->y,
            'scaleX' => $request->scaleX,
            'scaleY' => $request->scaleY,
            'rotation' => $request->rotation,
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    public function SetCoil(Request $request, $id)
    {   
        //dd($request->serial_code);
        $gudang = coil_location::where('slot_id', $id)->first();
        
        
        if($request->serial_code != ''){
            $coil= coil_detail::where('serial_Code', $request->serial_code)->first();
            //dd($coil->id);
            $gudang->update([
                'coil_id' => $coil->id,
            ]);
            $coil->update([
                'location_id' => $gudang->id,
            ]);
        } 
        
        if ($request->delete == 'true'){
            $coil= coil_detail::where('serial_Code', $request->serial_code)->first();
            $gudang->update([
                'coil_id' => null,
            ]);
            $coil->update([
                'location_id' => null,
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slot = coil_location::where('slot_id', $id);
       
        $slot->delete();
        return response()->json(['status' => 'success']);
    }
}
