<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GudangCollection;
use App\gudang;
use App\blok;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\gudangNotification;
use App\User;

class GudangController extends Controller
{
    public function index()
    {
        $gudang = gudang::orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $gudang = $gudang->where('name', 'LIKE', '%' . request()->q . '%');
        }

        return new GudangCollection($gudang->paginate(10));
       // return response()->json(['status' => 'success', 'data' => $gudang], 200);
    }

    public function getData()
    {
        $gudang = gudang::with('blok.coil_location.coil')->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $gudang = $gudang->where('name', 'LIKE', '%' . request()->q . '%');
        }else {
            $gudang = $gudang->get();
        }

        //return new GudangCollection($gudang->paginate(10));
        return response()->json(['status' => 'success', 'data' => $gudang], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:gudangs,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ]);

       //$user = Auth::user();
       $user = $request->user();

        //dd($user);

        $dataGudang = gudang::create($request->all());

        $tipe = 'gudang';

        //GET USER YANG ROLE-NYA SUPERADMIN DAN FINANCE
        //KENAPA? KARENA HANYA ROLE ITULAH YANG AKAN MENDAPATKAN NOTIFIKASI
        $users = User::whereIn('role', [0, 1])->get();
        //KIRIM NOTIFIKASINYA MENGGUNAKAN FACADE NOTIFICATION
        Notification::send($users, new gudangNotification($dataGudang, $user,  $tipe));
        
       
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
