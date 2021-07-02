<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\blokCollection;
use App\blok;
use App\gudang;

class BlokController extends Controller
{
    public function index()
    {
        $blok = blok::with('gudang')->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $blok = $blok->where('name', 'LIKE', '%' . request()->q . '%');
        }
        return new blokCollection($blok->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'panjang' => 'required|integer',    
            'lebar' => 'required|integer'
        ]);

        blok::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $blok = blok::where('id',$id)->first();
        return response()->json(['status' => 'success', 'data' => $blok], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'panjang' => 'required|integer',    
            'lebar' => 'required|integer'
        ]);

        $blok = blok::where('id',$id)->first();
        $blok->update($request->except('gudang_id'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $blok = blok::find($id);
        $blok->delete();
        return response()->json(['status' => 'success'], 200);
    }

}
