<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addNewProduct(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'datetime' => 'required|date',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a new product and save it to the database
        $product = new Product();
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->description = $request->input('description');
        $product->datetime = $request->input('datetime');

        $product->save();


        // Handle image upload
        if ($request->hasFile('images')) {
            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                // Generate a unique filename
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();


                $image->storeAs('public/images', $filename);

                // Save the image path to the array
                $imagePaths[] = 'images/' . $filename;
            }

            // Save the array of image paths to the product
            $product->update(['images' => json_encode($imagePaths)]);
        }

        $totalRecords = Product::count();

        // Calculate the page number for the last record
        $pageNumber = ceil($totalRecords / 5);
        return response()->json(['redirect_url' => "/dashboard?page={$pageNumber}"], 200);
    }
    public function index()
    {
        $products = Product::all();
        return $products;
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $category = $request->input('category');

        $query = Product::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%$searchTerm%")
                    ->orWhere('description', 'like', "%$searchTerm%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $filteredProducts = $query->get();
        return response()->json($filteredProducts);
    }

    public function getDistinctCategories()
    {
        $categories = array('Foods', 'Clothes', 'Toys');

        return response()->json($categories);
    }

    public function getProductById(Product $product)
    {
        if (!$product) {
            abort(404);
        }
        return response()->json(['product' => $product], 200);
    }

    public function showEditProduct(Product $product)
    {
        if (!$product) {
            abort(404);
        }

        return view('dashboard', ['product' => $product]);
    }

    public function updateProduct(Request $request, $id)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'datetime' => 'required|date',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        // Update the product details
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->description = $request->input('description');
        $product->datetime = $request->input('datetime');

        // Handle image updates
        $existingImages = json_decode($product->images, true) ?? [];

        // Add new images
        $newImages = $request->file('images');
        if ($newImages) {
            foreach ($existingImages as $imageName) {

                $imagePath = public_path("storage/{$imageName}");

                if (file_exists($imagePath) && $imageName !== 'images/default.png') {
                    unlink($imagePath);
                }
            }

            $newImagePaths = [];
            foreach ($newImages as $newImage) {
                $newImagePath = $newImage->store('images', 'public');
                $newImagePaths[] = $newImagePath;
            }
            $existingImages = $newImagePaths;
        }

        // Save the updated images
        $product->images = json_encode($existingImages);

        // Save the changes
        $product->save();
        $pageNumber = $this->getIndex($id);
        return response()->json(['redirect_url' => "/dashboard?page={$pageNumber}"], 200);
    }

    public function getIndex($id)
    {
        // Calculate the page number based on the index of the row
        $index = Product::orderBy('id')->pluck('id')->search($id);
        $pageNumber = ceil(($index + 1) / 5);
        return $pageNumber;
    }
    public function deleteProduct(Product $product)
    {
        // Gets the images in the database
        $imagesJson = $product->images;

        // Gets all the images in an array 
        $imagesArray = json_decode($imagesJson, true);

        // Deletes all images if it exists
        if (!is_null($imagesArray)) {
            foreach ($imagesArray as $imageName) {
                $imagePath = public_path("storage/{$imageName}");
                // Delete everything except the default photo
                if (file_exists($imagePath) && $imageName !== 'images/default.png') {
                    unlink($imagePath);
                }
            }
        }


        $product->delete();
    }
}
