<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class QnAResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'type' => 'QnA',
            'question' => $this->question,
            'answer' => $this->answer,
            'options' => $this->options,
            'video_link' => $this->video_link,
            'description' => $this->description,
            'link' => $this->link,
            'randomize' => $this->randomize,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at          
            
        ];
    }
}
