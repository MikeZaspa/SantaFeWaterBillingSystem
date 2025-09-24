<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin_plumbers';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'contact_number',
        'address',
        'username',
        'password',
        'status'
    ];

    protected $hidden = [
        'password', // Hide password when returning JSON
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
