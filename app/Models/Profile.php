<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_pemohon',
        'nama_lembaga',
        'nomor_identitas',
        'alamat',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
