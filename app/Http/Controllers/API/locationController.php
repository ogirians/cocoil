<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\location;
use App\coil_location;

class locationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $location = coil_location::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $location = $location->where('blok_id', request()->q)->get();
            //dd($location);
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
        

        $gudang = coil_location::whereCode($id)->first();
        $gudang->update($request);
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
        //
    }
}
