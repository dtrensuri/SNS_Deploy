<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $tableName = 'channels';
    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name_channel',
        'id_channel',
        'add_id',
        'status',
        'platform',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'add_id');
    }
}