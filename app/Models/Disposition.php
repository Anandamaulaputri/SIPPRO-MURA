<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'version_id',
        'petugas_id',
        'pejabat_disposisi',
        'nomor_surat',
        'tanggal_surat',
        'asal_surat',
        'tujuan_disposisi',
        'tanggal_disposisi',
        'catatan_disposisi',
        'file_bukti_disposisi',
        'status_disposisi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_disposisi' => 'date',
        ];
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(ProposalVersion::class, 'version_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_bukti_disposisi ? asset('storage/'.$this->file_bukti_disposisi) : null;
    }
}
