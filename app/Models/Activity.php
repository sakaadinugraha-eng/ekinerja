<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import ini

class Activity extends Model
{
    use HasFactory;

    protected $guarded = []; // Atau list fillable kamu

    /**
     * Relasi ke tabel User
     * Activity ini dimiliki oleh (belongs to) User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}