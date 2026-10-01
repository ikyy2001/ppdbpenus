<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbFeeSetting extends Model
{
    use HasFactory;

    protected $table = 'ppdb_fee_settings';

    protected $guarded = ['id'];

    /**
     * Dapatkan nilai pengaturan berdasarkan key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting || $setting->value === null) {
            return $default;
        }

        // Coba decode JSON jika nilainya merupakan JSON valid
        $decoded = json_decode($setting->value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $setting->value;
    }

    /**
     * Simpan atau perbarui nilai pengaturan
     */
    public static function set(string $key, mixed $value, ?string $label = null, string $group = 'general'): self
    {
        $stringValue = is_array($value) || is_object($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;

        return static::updateOrCreate(
            ['key' => $key],
            [
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
                'value' => $stringValue,
                'group' => $group,
            ]
        );
    }

    /**
     * Dapatkan seluruh pengaturan berdasarkan group
     */
    public static function getGroup(string $group): array
    {
        $items = static::where('group', $group)->get();
        $result = [];
        foreach ($items as $item) {
            $decoded = json_decode($item->value, true);
            $result[$item->key] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item->value;
        }
        return $result;
    }
}
