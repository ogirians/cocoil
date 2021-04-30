<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GudangCollection;
use App\gudang;

class GudangController extends Controller
{
    public function index()
    {
        $gudang = gudang::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $gudang = $gudang->where('name', 'LIKE', '%' . request()->q . '%');
        }
        return new GudangCollection($gudang->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:gudangs,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ]);

        gudang::create($request->all());
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $gudang = gudang::whereCode($id)->first();
        return response()->json(['status' => 'success', 'data' => $gudang], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|exists:gudangs,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ]);

        $gudang = gudang::whereCode($id)->first();
        $gudang->update($request->except('code'));
        return response()->json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $gudang = gudang::find($id);
        $gudang->delete();
        return response()->json(['status' => 'success'], 200);
    }

}
