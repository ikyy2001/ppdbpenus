<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'ppdb_announcements';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope pengumuman aktif dan terpublikasi
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Format tanggal Indonesia
     */
    public function getTanggalFormattedAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->locale('id')->isoFormat('D MMMM Y') : '-';
    }

    /**
     * URL file lampiran lengkap
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return asset('storage/' . $this->file_path);
    }
}
