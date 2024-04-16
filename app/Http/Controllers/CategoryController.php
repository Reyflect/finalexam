<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function categoryPage()
    {
        return Inertia::render('Dashboard', ['content' => 'category']);
    }

    public function categoryForm()
    {
        return Inertia::render('Dashboard', ['content' => 'addcategory']);
    }


    public function categoryForm2(Category $id)
    {
        return Inertia::render('Dashboard', ['content' => 'editcategory', 'category' => $id]);
    }



    /**
     * returns all categories
     */
    public function categories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function addCategory(Request $request)
    {

        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // if ($request->hasFile('image')) {
        //     $this->handleImages($category, $request->file('image'));
        // }

        $category->save();
        return Inertia::render('Dashboard', ['content' => 'category']);
        //return response()->json(['redirect_url' => "/category"], 200);
        //return $this->saveCategory($request);
    }


    public function editCategory(Request $request, Category $id)
    {
        return $this->saveCategory($request, $id);
    }
    /**
     * Validates all inputs from the form
     * @param Request $request of the instance
     */
    public function validationRules(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $rules;
    }

    /**
     * Saves product details in database
     * @param Request $request form request
     * @param $id id of the product if editing
     */
    public function saveCategory(Request $request, $id = null)
    {

        try {
            $this->validationRules($request);

            $category = $id ? Category::findOrFail($id) : new Category();
            $category->name = $request->input('name');
            $category->description = $request->input('description');

            if ($request->hasFile('image')) {
                $this->handleImages($category, $request->file('image'));
            }

            $category->save();

            return response()->json(['redirect_url' => "/category"], 200);
        } catch (ValidationException $e) {
            return response()->json(['form_error' => $e->validator->errors()], 400);
        }
    }


    public function deleteCategory(Category $category)
    {
        //    $this->deleteImage($category);
        $category->delete();
    }

    private function deleteImage(Category $category)
    {
        $imageName = $category->image;
        if ($imageName) {
            $imagePath = public_path("storage/{$imageName}");
            // Delete everything except the default photo
            if (file_exists($imagePath) && $imageName !== 'images/default.png') {
                unlink($imagePath);
            }
        }
    }
}
