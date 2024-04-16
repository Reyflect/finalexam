<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\ProductService;


class ProductController extends Controller
{
    public function __construct(
        public ProductService $services
    ) {
    }
    /**
     * Adds a new product
     * @param Request $request of the instance
     */
    public function addNewProduct(Request $request)
    {
        return $this->services->saveProduct($request);
    }

    /**
     * Returns all producuts
     */
    public function index()
    {

        return $this->services->getProducts();
    }

    /**
     * Searches for a product
     * @param Request $request of the instance
     */
    public function search(Request $request)
    {
        return response()->json($this->services->search($request));
    }

    /**
     * Retruns the product details based on id
     * @param Product $product is the product id
     */
    public function getProductById(Product $product)
    {
        return response()->json($this->services->getProductDetails($product), 200);
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
        return $this->services->saveProduct($request, $id);
    }
    /**
     * deletes a product
     * @param Product $product of the instance
     */
    public function deleteProduct(Product $product)
    {
        $this->services->delete($product);
    }
}
