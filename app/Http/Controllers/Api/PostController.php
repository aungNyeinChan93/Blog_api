<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostDetailResource;
use Exception;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResourse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MediaResourse;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostListsResource;

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


            $post = Post::create([
                "user_id" => Auth::user()->id,
                "category_id" => $request->category_id,
                "title" => $request->title,
                "description" => $request->description,
            ]);

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

    // posts lists
    public function lists(Request $request)
    {
        $posts = Post::with("category","user","media")
            ->orderBy("created_at", "desc")
            ->when($request->category_id, function ($query) use ($request) {
                $query->where("category_id", "=", $request->category_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereAny(["title", "description"], "like", "%" . $request->search . "%");
            })
            ->paginate();
        return PostListsResource::collection($posts)->additional(["message" => "success"]);
    }

    // detail page
    public function detail(Post $post)
    {
        return ResponseHelper::success(new PostDetailResource($post));
    }
}
