<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserProgressRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Update this if you need specific authorization logic
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'topic_id' => 'required|exists:topics,id',
            'status' => 'required|in:in-progress,completed,failed',
        ];
    }
}
