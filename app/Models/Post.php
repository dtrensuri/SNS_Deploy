<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'total_impressions',
        'total_engaged',
        'total_reactions',
        'total_shares',
        'platform',
        'scheduled_time',
        'status',
        'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
