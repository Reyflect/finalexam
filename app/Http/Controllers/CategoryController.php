<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * returns all categories
     */
    public function categories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
}
