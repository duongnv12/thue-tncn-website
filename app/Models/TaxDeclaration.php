<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_income',
        'total_deductions',
        'taxable_income',
        'calculated_tax',
        'declaration_month', // Thêm declaration_month
        'declaration_year',  // Thêm declaration_year
    ];

    protected $casts = [
        'total_income' => 'float',
        'total_deductions' => 'float',
        'taxable_income' => 'float',
        'calculated_tax' => 'float',
        'declaration_month' => 'integer',
        'declaration_year' => 'integer',
    ];

    /**
     * Định nghĩa mối quan hệ với User.
     * Một khai báo thuế thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}