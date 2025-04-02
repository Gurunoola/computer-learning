<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTestResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'score' => $this->score,
            'total_questions' => $this->total_questions,
            'correct_answers' => $this->correct_answers,
            'attempted_at' => $this->attempted_at->toDateTimeString(),
        ];
    }
}
