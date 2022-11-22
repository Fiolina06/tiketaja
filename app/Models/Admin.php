<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends  Authenticatable
{

    use HasFactory, HasApiTokens;

    protected $guards = 'admin';

    protected $fillable = [
        'name','email', 'no_hp','username','tgl_lahir','password'
    ];
}
