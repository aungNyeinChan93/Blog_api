<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "file_name"=>$this->file_name,
            "file_type"=>$this->file_type,
            "post_id"=>$this->model_id,
            "model_type"=>$this->type,
        ];
    }
}
