<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // Thêm is_admin vào fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // Ép kiểu is_admin về boolean
        ];
    }

    /**
     * Định nghĩa mối quan hệ với IncomeSource.
     * Một người dùng có nhiều nguồn thu nhập.
     */
    public function incomeSources()
    {
        return $this->hasMany(IncomeSource::class);
    }

    /**
     * Định nghĩa mối quan hệ với Dependent.
     * Một người dùng có nhiều người phụ thuộc.
     */
    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }

    /**
     * Định nghĩa mối quan hệ với TaxDeclaration.
     * Một người dùng có nhiều khai báo thuế.
     */
    public function taxDeclarations()
    {
        return $this->hasMany(TaxDeclaration::class);
    }
}