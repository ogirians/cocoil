<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationGudangController extends Controller
{
    public function index()
    {
        $user = request()->user(); //AMBIL USER YANG SEDANG LOGIN

        $data = $user->unreadNotifications;
        
        $data_gudang = [];

        foreach ($user->unreadNotifications as $notification) {
            
            if ($notification->type == 'App\Notifications\gudangNotification'){
                //$data_gudang.push($notification);
                array_push($data_gudang, $notification);
            }
        }

       // dd($data_gudang);
        //dd($data);
        //$user->where('type','App\Notifications\gudangNotification');
        //KEMUDIAN YANG DI READ ADALAH HANYA NOTIFIKASI YANG STATUSNYA BELUM DIREAD
        //SECARA LANGSUNG KITA DAPAT MENGAMBIL DATA NYA MELALUI USER DENGAN MENGAKSES PROPERTY unreadNotifications.
        return response()->json(['status' => 'success', 'data' => $data_gudang]);
    }

    public function store(Request $request)
    {
        $user = $request->user(); //AMBIL USER YANG SEDANG LOGIN
        //EDIT RECORD NOTIFIKASI BERDASARKAN ID YANG DITERIMA
        //NOTIFIKASI YANG SUDAH/BELUM DIREAD DITANDAI DENGAN read_at YANG MASIH NULL
        $user->unreadNotifications()->where('id', $request->id)->update(['read_at' => now()]);
        return response()->json(['status' => 'success']);
    }
}