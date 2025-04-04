<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class QnA extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qnas'; // Explicitly set the table name

    protected $fillable = [
        'question',
        'topic_id',
        'answer',
        'options',
        'video_link',
        'description',
        'link',
        'randomize'
    ];

    protected $casts = [
        'randomize' => 'boolean'
    ];
}

