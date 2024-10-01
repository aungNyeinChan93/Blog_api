<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Container\Attributes\Storage;

class CategoryController extends Controller
{
    //categories Lists
    public function list(){
        $categories = Category::all();
        return ResponseHelper::success(CategoryResource::collection($categories));
    }
}
