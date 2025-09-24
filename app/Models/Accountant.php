<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accountant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin_accountants';

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
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}