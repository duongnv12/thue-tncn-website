<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'date_of_birth',
        'relationship',
        'tax_code', // Thêm tax_code
    ];

    protected $casts = [
        'date_of_birth' => 'date', // Ép kiểu ngày sinh thành Carbon instance
    ];

    /**
     * Định nghĩa mối quan hệ với User.
     * Một người phụ thuộc thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}