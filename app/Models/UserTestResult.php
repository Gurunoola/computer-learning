<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTestResult extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_test_result'; // Explicitly set the table name

    protected $fillable = ['user_id', 'topic_id', 'score', 'total_questions', 'correct_answers', 'attempted_at'];

    protected $casts = [
        'attempted_at' => 'datetime',
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
