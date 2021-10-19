<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;


class actionNotification extends Notification implements ShouldQueue
{
    use Queueable;

		//DEFINSIKAN GLOBAL VARIABLE
    protected $dataNotif;
    protected $user;
    protected $tipe;
    protected $serial_code;
    public function __construct($dataNotif, $user, $tipe, $serial_code)
    {
        //ASSIGN DATA YANG DITERIMA KE DALAM GLOBAL VARIABLE
        $this->dataNotif = $dataNotif;
        $this->user = $user;
        $this->tipe = $tipe;
        $this->serial_code = $serial_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //JADI KITA MENGGUNAKAN DUA METHOD, SIMPAN KE DATABASE DAN BROADCAST KE PUSHER
        //PUSHER ADALAH PIHAK KETIGA YANG AKAN DIGUNAKAN UNTUK REALTIME NOTIFICATION
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
  
    //FORM DATA YANG DISIMPAN KE DALAM DATABASE
    public function toDatabase($notifiable)
    {
        return [
            'tipe' => $this->tipe,
            'sender_id' =>  '-' ,
            'sender_name' => 'bagian gudang',
            'serial_code' =>  $this->serial_code,
            'dataNotif' => $this->dataNotif
        ];
    }

    //FORM DATA YANG AKAN DI BROADCAST
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'tipe' => $this->tipe,
            'sender_id' => '-',
            'sender_name' => 'bagian gudang' ,
            'serial_code' =>   $this->serial_code,
            'dataNotif' => $this->dataNotif
        ]);
    }
  
    //SEBENARNYA JIKA TIDAK ADA PERBEDAAN FORM DATA, KITA BISA LANGSUNG MENGGUNAKAN SATU METHOD SAJA YAKNI toArray()
}