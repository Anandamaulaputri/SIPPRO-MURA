<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'nomor_versi',
        'latar_belakang',
        'tujuan',
        'lokasi_kegiatan',
        'tanggal_kegiatan',
        'rincian_rab',
        'file_proposal',
        'catatan_revisi_pemohon',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
            'nomor_versi' => 'integer',
        ];
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ProposalAttachment::class, 'version_id');
    }

    public function reviewDecisions(): HasMany
    {
        return $this->hasMany(ReviewDecision::class, 'version_id');
    }
}
