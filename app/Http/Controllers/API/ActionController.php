<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\actionNotification;

use App\Action;
use App\coil_detail;
use App\coil_location;
use App\User;

class ActionController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->jenis_aksi);

        $this->validate($request, [
            'action_tipe' => 'required',
            'no_dokumen' => 'required',
            'password' => 'required',    
        ]);

        if($request->password == '14045'){
            //get id coil
            $id_coil = coil_detail::where('serial_code', $request->serial_code)->first();
            $request['coil_id'] = $id_coil->id;
           // $request['user_id'] = $request->user()->id;

            //dd($request['user_id']);
            

            //cek apakah ada coil yg sama belum dikonfirmasi
            $cekAction = Action::where('coil_id', $id_coil)->where('action_status', null)->get();

            //if($cekAction){
            //    $belum_konfirmasi = true;

            //} else {
                $dataAction = Action::create($request->except('serial_code','password'));
                $dataAction->setAttribute('serial_code', $request->serial_code);

                $serial_code = $request->serial_code;
                //dd($dataAction);
                $tipe = 'action';
        
                $user = 'bagian gudang';
                //GET USER YANG ROLE-NYA SUPERADMIN DAN FINANCE
                //KENAPA? KARENA HANYA ROLE ITULAH YANG AKAN MENDAPATKAN NOTIFIKASI
                $users = User::whereIn('role', [0, 1])->get();
                //KIRIM NOTIFIKASINYA MENGGUNAKAN FACADE NOTIFICATION
                Notification::send($users, new actionNotification($dataAction, $user,  $tipe, $serial_code));

                $belum_konfirmasi = false;
                $password = true;
        // }    
        } else {
                $belum_konfirmasi = false;
                $password = false;
        }

        return response()->json(['status' => 'success','password' => $password, 'belum_konfirmasi' => $belum_konfirmasi], 200);
    }

    public function getAction(Request $request)
    {
        return response()->json(['status' => 'success', 'status_action' => 'belum di konfirmasi'], 200);
    }

    public function getActionDetail($id)
    {
        $data = Action::with('coil.location.gudang','coil.location.blok','user')->where('id', $id)->first();

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function confirm(Request $request)
    {
        //dd($request->id_notif);
        $user = $request->user();

        //dd($user->id);


        $act =  Action::where('id', $request->id_action)->first();

        /*coil_location::where('coil_id', $act->coil_id)->update(
            [
                'coil_id' => null,
            ]
        );*/


        $location =  coil_location::where('coil_id', $act->coil_id)->first();
        $location->timestamps = false;
        $location->coil_id = null;
        $location->save();

        coil_detail::where('id', $act->coil_id)->update(
            [
                'location_id' => null,
                'status' => $request->action_tipe
            ]
        );


        Action::where('id', $request->id_action)->update(
            [
                'action_status' => 'Disetujui',
                'user_id' => $user->id,
            ]
        );

        //NOTIFIKASI YANG SUDAH/BELUM DIREAD DITANDAI DENGAN read_at YANG MASIH NULL
        $user->unreadNotifications()->where('id', $request->id_notif)->update(['read_at' => now()]);
       
        return response()->json(['status' => 'success'], 200);
    }

    public function tolak(Request $request)
    {

        $user = $request->user();

        Action::where('id', $request->id_action)->update(
            [
                'action_status' => 'Ditolak',
            ]
        );
        
        
        $user->unreadNotifications()->where('id', $request->id_notif)->update(['read_at' => now()]);

        return response()->json(['status' => 'success'], 200);
    }

    public function baca(Request $request)
    {

        $user = $request->user();

        $user->unreadNotifications()->where('id', $request->id_notif)->update(['read_at' => now()]);

        return response()->json(['status' => 'success'], 200);
    }
}
