<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'address', 'city', 'notes'
    ];

    public function empname()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
