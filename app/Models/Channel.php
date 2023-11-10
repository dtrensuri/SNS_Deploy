<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';

    protected $fillable = [
        'name_channel',
        'id_channel',
        'user_id',
        'status',
        'platform',
        'access_token',
        'refresh_token',
        'token_expiration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
