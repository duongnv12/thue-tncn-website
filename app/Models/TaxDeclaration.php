<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'declaration_year',
        'declaration_month',
        'total_income',
        'total_deductions',
        'mandatory_insurance_deductions', // <-- Thêm vào
        'personal_deduction_amount',    // <-- Thêm vào
        'dependent_deduction_amount',   // <-- Thêm vào
        'taxable_income',               // <-- Thêm vào
        'calculated_tax',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}