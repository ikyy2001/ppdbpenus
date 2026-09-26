<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $table = 'ppdb_registrations';

    protected $guarded = ['id'];

    protected $casts = [
        'jenis_layanan' => 'array',
        'sumber_info' => 'array',
    ];

    /**
     * Helper formatting tanggal lahir
     */
    public function getTanggalLahirFormattedAttribute(): string
    {
        $bulanMap = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $bulan = $bulanMap[$this->tanggal_lahir_bulan] ?? $this->tanggal_lahir_bulan;
        return "{$this->tanggal_lahir_hari} {$bulan} {$this->tanggal_lahir_tahun}";
    }

    /**
     * Helper status badge
     */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'terverifikasi' => [
                'label' => 'Terverifikasi',
                'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500',
            ],
            'lulus_seleksi' => [
                'label' => 'Lulus Seleksi',
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'dot' => 'bg-blue-500',
            ],
            default => [
                'label' => 'Menunggu Verifikasi',
                'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500',
            ]
        };
    }
}
