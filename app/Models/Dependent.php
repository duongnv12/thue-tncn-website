<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'citizen_id',
        'relationship',
        'months_registered',
        'notes',
    ];

    /**
     * Get the user that owns the dependent.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}