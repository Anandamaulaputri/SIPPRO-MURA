<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_registrasi',
        'user_id',
        'category_id',
        'judul_proposal',
        'total_anggaran',
        'status',
        'versi_aktif',
        'tanggal_kirim',
    ];

    protected function casts(): array
    {
        return [
            'total_anggaran' => 'decimal:2',
            'tanggal_kirim' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProposalVersion::class);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(ProposalVersion::class)->latestOfMany('nomor_versi');
    }

    public function reviewDecisions(): HasMany
    {
        return $this->hasMany(ReviewDecision::class);
    }

    public function getFormattedAnggaranAttribute(): string
    {
        return 'Rp '.number_format($this->total_anggaran, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft (Draf Awal)',
            'diajukan' => 'Diajukan (Menunggu Telaah)',
            'perlu_perbaikan' => 'Perlu Perbaikan / Revisi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-[#1a1a1a] text-[#a3a3a3] border-[#2a2a2a]',
            'diajukan' => 'bg-[#d4af37]/15 text-[#e6c65c] border-[#d4af37]/40',
            'perlu_perbaikan' => 'bg-amber-950/60 text-amber-400 border-amber-500/40',
            'disetujui' => 'bg-emerald-950/60 text-emerald-400 border-emerald-500/40',
            'ditolak' => 'bg-rose-950/60 text-rose-400 border-rose-500/40',
            default => 'bg-[#1a1a1a] text-[#a3a3a3] border-[#2a2a2a]',
        };
    }
}
