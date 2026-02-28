<?php

// app/Models/Activity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Fitur Tambahan: Hitung durasi otomatis (Accessor)
    public function getDurasiMenitAttribute()
    {
        return \Carbon\Carbon::parse($this->jam_selesai)
            ->diffInMinutes(\Carbon\Carbon::parse($this->jam_mulai));
    }
}
