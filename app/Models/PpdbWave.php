<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbWave extends Model
{
    use HasFactory;

    protected $table = 'ppdb_waves';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'kuota' => 'integer',
        'biaya_formulir' => 'decimal:2',
    ];

    /**
     * Relasi ke pendaftar di gelombang ini
     */
    public function pendaftar(): HasMany
    {
        return $this->hasMany(PpdbRegistration::class, 'gelombang_id');
    }

    /**
     * Hitung sisa kuota pendaftar
     */
    public function getSisaKuotaAttribute(): int
    {
        $terpakai = $this->pendaftar()->count();
        return max(0, $this->kuota - $terpakai);
    }

    /**
     * Persentase kuota terisi
     */
    public function getPersentaseTerisiAttribute(): int
    {
        if ($this->kuota <= 0) return 0;
        $terpakai = $this->pendaftar()->count();
        return min(100, (int) round(($terpakai / $this->kuota) * 100));
    }

    /**
     * Scope gelombang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Format tanggal rentang Indonesia
     */
    public function getPeriodeFormattedAttribute(): string
    {
        $mulai = $this->tanggal_mulai ? $this->tanggal_mulai->locale('id')->isoFormat('D MMMM Y') : '-';
        $selesai = $this->tanggal_selesai ? $this->tanggal_selesai->locale('id')->isoFormat('D MMMM Y') : '-';
        return "{$mulai} s.d. {$selesai}";
    }

    /**
     * Format biaya formulir rupiah
     */
    public function getBiayaFormulirFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_formulir, 0, ',', '.');
    }
}
