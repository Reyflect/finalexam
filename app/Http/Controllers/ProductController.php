<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{


    /**
     * Validates all inputs from the form
     * @param Request $request of the instance
     */
    public function validationRules(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|int|min:1',
            'stock' => 'required|int|min:1',
            'category' => 'required|int',
            'description' => 'required|string',
            'datetime' => 'required|date',
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
    public function saveProduct(Request $request, $id = null)
    {
        try {
            $this->validationRules($request);

            $product = $id ? Product::findOrFail($id) : new Product();
            $product->name = $request->input('name');
            $product->category_id = $request->input('category');
            $product->description = $request->input('description');
            $product->datetime = $request->input('datetime');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');


            if ($request->hasFile('images')) {
                $this->handleImages($product, $request->file('images'));
            }

            $product->save();
            $totalRecords = Product::count();

            // Calculate the page number for the last record
            $pageNumber = ceil($totalRecords / 5);

            return response()->json(['redirect_url' => "/dashboard?page={$pageNumber}"], 200);
        } catch (ValidationException $e) {
            return response()->json(['form_error' => $e->validator->errors()], 400);
        }
    }

    /**
     * Adds a new product
     * @param Request $request of the instance
     */
    public function addNewProduct(Request $request)
    {
        return $this->saveProduct($request);
    }

    /**
     * Handles all product images 
     * @param $product is a product instance to check if there is already an existing record
     * @param $newImages is the new image that will be added to the database
     */
    private function handleImages($product, $newImages)
    {
        $existingImages = json_decode($product->images, true) ?? [];

        foreach ($existingImages as $imageName) {
            $imagePath = public_path("storage/{$imageName}");
            if (file_exists($imagePath) && $imageName !== 'images/default.png') {
                unlink($imagePath);
            }
        }

        $newImagePaths = [];
        foreach ($newImages as $newImage) {
            $filename = uniqid() . '.' . $newImage->getClientOriginalExtension();
            $newImagePath = $newImage->storeAs('public/images', $filename);
            $newImagePaths[] = 'images/' . basename($newImagePath);
        }

        $product->images = json_encode($newImagePaths);
    }


    /**
     * Returns all producuts
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return $products;
    }

    /**
     * Searches for a product
     * @param Request $request of the instance
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $category = $request->input('category');

        $query = Product::with('category');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%$searchTerm%")
                    ->orWhere('description', 'like', "%$searchTerm%");
            });
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        $filteredProducts = $query->get();
        return response()->json($filteredProducts);
    }

    /**
     * Retruns the product details based on id
     * @param Product $product is the product id
     */
    public function getProductById(Product $product)
    {
        return response()->json(['product' => $product], 200);
    }


    /**
     * Returns the view of the product page
     * @param Product $product is the id instance
     */
    public function showEditProduct(Product $product)
    {
        return Inertia::render('Dashboard', ['content' => 'editProduct', 'product' => $product]);
    }

    /**
     * Updates product details
     * @param Request $request is the request details
     * @param $id is the product id
     */
    public function updateProduct(Request $request, $id)
    {
        return $this->saveProduct($request, $id);
    }


    /**
     * Returns a new page number
     * @param $id 
     */
    public function getIndex($id)
    {
        // Calculate the page number based on the index of the row
        $index = Product::orderBy('id')->pluck('id')->search($id);
        $pageNumber = ceil(($index + 1) / 5);
        return $pageNumber;
    }

    /**
     * deletes a product
     * @param Product $product of the instance
     */
    public function deleteProduct(Product $product)
    {
        $this->deleteImages($product->images);
        $product->delete();
    }

    /**
     * Deletes images in the database
     * 
     * @param $imagesJson is a json of all product images
     */
    private function deleteImages($imagesJson)
    {
        $imagesArray = json_decode($imagesJson, true);

        if (!is_null($imagesArray)) {
            foreach ($imagesArray as $imageName) {
                $imagePath = public_path("storage/{$imageName}");
                // Delete everything except the default photo
                if (file_exists($imagePath) && $imageName !== 'images/default.png') {
                    unlink($imagePath);
                }
            }
        }
    }
}
