<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostListsResource;
use App\Http\Resources\UserProfileResource;

class UserProfileController extends Controller
{
    // User Profile
    public function profile()
    {
        // $user = User::where("id",Auth::user()->id)->first();
        $user = Auth::user();
        return ResponseHelper::success(new UserProfileResource($user));
    }

    // user posts
    public function profilePosts(Request $request)
    {
        $posts = Post::with("category", "user", "media")->where("user_id", Auth::user()->id)
            ->when($request->category_id, function ($query) use ($request) {
                $query->where("category_id", $request->category_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereAny(["title", "description", "category_id"], "like", "%$request->search%");
            })
            ->get();

        return ResponseHelper::success(PostListsResource::collection($posts));
    }
}
