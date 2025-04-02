<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProgress extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_progress'; // Explicitly set the table name

    protected $fillable = ['user_id', 'topic_id', 'is_completed', 'last_accessed'];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_accessed' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
