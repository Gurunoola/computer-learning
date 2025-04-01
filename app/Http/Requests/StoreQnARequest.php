<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQnARequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'question' => [$this->isPostRequest(), 'string', 'max:1000'],
            'answer' => [$this->isPostRequest(), 'string', 'max:500'],
            'options' => [$this->isPostRequest(), 'string', 'regex:/^(?:[^,]+,){3}[^,]+$/'], // Must be exactly 4 comma-separated values
            'deleted' => ['boolean'],
            'video_link' => ['nullable', 'string', 'url'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'url'],
            'randomize' => ['boolean'],
        ];
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}
