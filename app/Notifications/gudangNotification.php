<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;


class gudangNotification extends Notification implements ShouldQueue
{
    use Queueable;

		//DEFINSIKAN GLOBAL VARIABLE
    protected $dataNotif;
    protected $user;
    protected $tipe;
    public function __construct($dataNotif, $user, $tipe)
    {
        //ASSIGN DATA YANG DITERIMA KE DALAM GLOBAL VARIABLE
        $this->dataNotif = $dataNotif;
        $this->user = $user;
        $this->tipe = $tipe;
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
            'sender_id' => !empty($this->user->id) ? $this->user->id : '-' ,
            'sender_name' => !empty($this->user->name) ? $this->user->name : 'bagian gudang',
            'dataNotif' => $this->dataNotif
        ];
    }

    //FORM DATA YANG AKAN DI BROADCAST
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'tipe' => $this->tipe,
            'sender_id' => !empty($this->user->id) ? $this->user->id : '-',
            'sender_name' => !empty($this->user->name) ? $this->user->name : 'bagian gudang' ,
            'dataNotif' => $this->dataNotif
        ]);
    }
  
    //SEBENARNYA JIKA TIDAK ADA PERBEDAAN FORM DATA, KITA BISA LANGSUNG MENGGUNAKAN SATU METHOD SAJA YAKNI toArray()
}