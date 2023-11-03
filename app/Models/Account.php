<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        "user_id",
        "platform",
        "access_token",
        "refresh_token",
        "token_expiration",
        "created_at",
        "updated_at",
    ];
}