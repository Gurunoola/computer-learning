<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topic_id',
        'title',
        'type',
        'content',
        'video_link',
        'reference_link'
    ];
}
