<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KinerjaBaruNotification extends Notification
{
    use Queueable;

    // Properti untuk menampung data dinamis
    public $data;

    /**
     * Kita buat constructor agar bisa menerima array data dari Livewire
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Ubah channel pengiriman dari ['mail'] menjadi ['database']
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Method ini yang akan memasukkan isi pesan ke kolom 'data' di tabel SQL
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => $this->data['title'],
            'message'    => $this->data['message'],
            'action_url' => $this->data['action_url'] ?? '#',
        ];
    }
}