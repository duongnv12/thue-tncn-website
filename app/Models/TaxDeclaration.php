<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDeclaration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'declaration_year',
        'total_gross_income',
        'total_taxable_income',
        'total_deductions',
        'personal_deduction',
        'dependent_deduction',
        'insurance_deduction',
        'charitable_deduction',
        'tax_base_income',
        'total_tax_payable',
        'total_tax_withheld',
        'tax_difference',
        'status',
        'details_json',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details_json' => 'array', // Tự động cast JSON field thành mảng PHP
    ];

    /**
     * Get the user that owns the tax declaration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}