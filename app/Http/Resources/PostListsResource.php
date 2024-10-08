<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostListsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "user_name"=>optional($this->user)->name ?? "unknow user",
            "category_name"=>optional($this->category)->name?? "unknow Category",
            "title"=>$this->title,
            "description"=>Str::limit($this->description,100,"..."),
            "create_at"=>Carbon::parse($this->created_at)->format("Y-m-d H-m-s"),
            "create_readable"=>Carbon::parse($this->created_at)->diffForHumans(),
            "image_path"=>$this->media ? asset("/storage/image/".$this->media->file_name): null,
        ];
    }
}
