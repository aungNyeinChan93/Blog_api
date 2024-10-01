<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    // User Profile
    public function profile(){

        $user = Auth::user();
        return ResponseHelper::success(new UserProfileResource($user));
    }
}
