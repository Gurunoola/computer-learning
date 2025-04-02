<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearningContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Change this if you need authentication checks
    }

    public function rules(): array
    {
        return [
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,video,link',
            'content' => 'nullable|string',
            'video_link' => 'nullable|url',
            'reference_link' => 'nullable|url',
        ];
    }
}
