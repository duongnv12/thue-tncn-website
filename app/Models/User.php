<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address', // Thêm
        'phone_number', // Thêm
        'tax_id_number', // Thêm
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Mối quan hệ với IncomeSource
    public function incomeSources()
    {
        return $this->hasMany(IncomeSource::class);
    }

    // Mối quan hệ với Dependent
    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }

    // Mối quan hệ với TaxDeclaration
    public function taxDeclarations()
    {
        return $this->hasMany(TaxDeclaration::class);
    }
}