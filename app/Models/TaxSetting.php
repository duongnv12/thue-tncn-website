<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];

    // Ép kiểu 'setting_value' thành float để dễ dàng tính toán
    protected $casts = [
        'setting_value' => 'float',
    ];
}