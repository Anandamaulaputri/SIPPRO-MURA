<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'jenis_lampiran',
        'nama_file',
        'file_path',
        'ukuran_file',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(ProposalVersion::class, 'version_id');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }
}
