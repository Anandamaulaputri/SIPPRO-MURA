<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

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
        'batas_waktu',
    ];

    protected function casts(): array
    {
        return [
            'total_anggaran' => 'decimal:2',
            'tanggal_kirim' => 'datetime',
            'batas_waktu' => 'date',
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

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }

    public function isDisposed(): bool
    {
        $activeVer = $this->relationLoaded('versions')
            ? $this->versions->firstWhere('nomor_versi', $this->versi_aktif)
            : $this->versions()->where('nomor_versi', $this->versi_aktif)->first();

        if (! $activeVer) {
            return false;
        }

        if ($activeVer->relationLoaded('disposition')) {
            return $activeVer->disposition?->status_disposisi === 'selesai';
        }

        if ($this->relationLoaded('dispositions')) {
            return $this->dispositions
                ->where('version_id', $activeVer->id)
                ->where('status_disposisi', 'selesai')
                ->isNotEmpty();
        }

        return $this->dispositions()
            ->where('version_id', $activeVer->id)
            ->where('status_disposisi', 'selesai')
            ->exists();
    }

    public function getActiveDispositionAttribute(): ?Disposition
    {
        $activeVer = $this->relationLoaded('versions')
            ? $this->versions->firstWhere('nomor_versi', $this->versi_aktif)
            : $this->versions()->where('nomor_versi', $this->versi_aktif)->first();

        if (! $activeVer) {
            return null;
        }

        if ($activeVer->relationLoaded('disposition')) {
            return $activeVer->disposition;
        }

        return $this->dispositions()->where('version_id', $activeVer->id)->first();
    }

    public function getFormattedAnggaranAttribute(): string
    {
        return 'Rp '.number_format($this->total_anggaran, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'diajukan') {
            return $this->isDisposed() ? 'Disposisi Selesai (Siap Ditelaah)' : 'Menunggu Disposisi';
        }

        return match ($this->status) {
            'draft' => 'Draft (Draf Awal)',
            'perlu_perbaikan' => 'Perlu Perbaikan / Revisi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        if ($this->status === 'diajukan') {
            return $this->isDisposed()
                ? 'bg-blue-950/60 text-blue-400 border-blue-500/40'
                : 'bg-amber-950/60 text-amber-400 border-amber-500/40';
        }

        return match ($this->status) {
            'draft' => 'bg-[#1a1a1a] text-[#a3a3a3] border-[#2a2a2a]',
            'perlu_perbaikan' => 'bg-amber-950/60 text-amber-400 border-amber-500/40',
            'disetujui' => 'bg-emerald-950/60 text-emerald-400 border-emerald-500/40',
            'ditolak' => 'bg-rose-950/60 text-rose-400 border-rose-500/40',
            default => 'bg-[#1a1a1a] text-[#a3a3a3] border-[#2a2a2a]',
        };
    }

    public function scopeDisposed($query)
    {
        return $query->whereExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('dispositions')
                ->join('proposal_versions', 'proposal_versions.id', '=', 'dispositions.version_id')
                ->whereColumn('proposal_versions.proposal_id', 'proposals.id')
                ->whereColumn('proposal_versions.nomor_versi', 'proposals.versi_aktif')
                ->where('dispositions.status_disposisi', 'selesai');
        });
    }

    public function scopeWaitingDisposition($query)
    {
        return $query->whereNotExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('dispositions')
                ->join('proposal_versions', 'proposal_versions.id', '=', 'dispositions.version_id')
                ->whereColumn('proposal_versions.proposal_id', 'proposals.id')
                ->whereColumn('proposal_versions.nomor_versi', 'proposals.versi_aktif')
                ->where('dispositions.status_disposisi', 'selesai');
        });
    }

    public function getSisaHariAttribute(): ?int
    {
        if (! $this->batas_waktu) {
            return null;
        }

        $today = now()->startOfDay();
        $deadline = $this->batas_waktu->copy()->startOfDay();

        return (int) $today->diffInDays($deadline, false);
    }

    public function getDeadlineStatusAttribute(): string
    {
        $sisa = $this->sisa_hari;
        if ($sisa === null) {
            return 'belum_diatur';
        }

        if ($sisa > 7) {
            return 'aman';
        }

        if ($sisa >= 3) {
            return 'mendekati';
        }

        if ($sisa >= 0) {
            return 'mendesak';
        }

        return 'terlewat';
    }

    public function getDeadlineLabelAttribute(): string
    {
        $sisa = $this->sisa_hari;
        if ($sisa === null) {
            return 'Belum Ditentukan';
        }

        if ($sisa > 7) {
            return "Aman (Sisa {$sisa} hari)";
        }

        if ($sisa >= 3) {
            return "Mendekati Batas Waktu ({$sisa} hari lagi)";
        }

        if ($sisa >= 0) {
            return $sisa === 0 ? 'Mendesak (Hari Ini)' : "Mendesak ({$sisa} hari lagi)";
        }

        return 'Terlewat / Perlu Perhatian';
    }

    public function getDeadlineColorAttribute(): string
    {
        return match ($this->deadline_status) {
            'aman' => 'bg-emerald-950/60 text-emerald-400 border-emerald-500/30',
            'mendekati' => 'bg-[#D4AF37]/15 text-[#E6C65C] border-[#D4AF37]/40',
            'mendesak' => 'bg-orange-950/70 text-orange-400 border-orange-500/40',
            'terlewat' => 'bg-rose-950/70 text-rose-400 border-rose-500/40',
            default => 'bg-[#1a1a1a] text-[#737373] border-[#2a2a2a]',
        };
    }

    public function getFormattedBatasWaktuAttribute(): string
    {
        return $this->batas_waktu ? $this->batas_waktu->translatedFormat('d F Y') : '-';
    }

    public function scopeOrderByDeadline($query, $direction = 'asc')
    {
        return $query->orderByRaw("CASE WHEN batas_waktu IS NULL THEN 1 ELSE 0 END, batas_waktu {$direction}");
    }
}
