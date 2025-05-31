<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'bracket_number',
        'min_income',
        'max_income',
        'tax_rate',
    ];

    // Ép kiểu các cột số thành float
    protected $casts = [
        'min_income' => 'float',
        'max_income' => 'float',
        'tax_rate' => 'float',
    ];
}