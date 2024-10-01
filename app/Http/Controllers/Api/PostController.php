<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MediaResourse;
use App\Http\Resources\PostResourse;
use Exception;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Post Create
    public function create(Request $request)
    {

        try {
            $validated = $request->validate([
                "title" => "required",
                "description" => "required",
                "category_id" => "required",
            ]);
            DB::beginTransaction();
            if ($request->hasFile("image")) {
                $file = $request->file("image");
                $file_Name = uniqid() . $request->file("image")->getClientOriginalExtension();
                Storage::put("/image/" . $file_Name, file_get_contents($file));
            }

            $post = Post::create($validated);

            $media = Media::create([
                "file_name" => $file_Name,
                "file_type" => "image",
                "model_id" => $post->id,
                "type" => Post::class,
            ]);
            DB::commit();

            return ResponseHelper::success([
                "post" => new PostResourse($post),
                "media" => new MediaResourse($media),
            ], "post create success!");

        } catch (Exception $err) {
            DB::rollBack();
            return ResponseHelper::fail($err->getMessage());
        }
    }
}
