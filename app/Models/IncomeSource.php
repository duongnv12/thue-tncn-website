<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'frequency', // Thêm frequency
        'description', // Thêm description
    ];

    protected $casts = [
        'amount' => 'float', // Ép kiểu amount thành float
    ];

    /**
     * Định nghĩa mối quan hệ với User.
     * Một nguồn thu nhập thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}