<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{

    /**
     * Gets the cart items and redirect towards a specific page
     * @param $content is the landing page where it will be redirected
     */
    public function getCartItems($content = 'cart')
    {

        $cartItems = $this->cartContents();

        return Inertia::render('Dashboard', compact('content', 'cartItems'));
    }
    /**
     * Returns the collection of the products
     */
    public function cartContents()
    {
        return CartItem::with('product')->where('user_id', auth()->id())->get();
    }
    /**
     * Returns the JSON of the products
     */
    public function getCartItemsJSON()
    {
        $cartItems = $this->cartContents();

        return response()->json(['cartItem' => $cartItems]);
    }


    public function addExistingItem(Request $request)
    {
        // Check if the product already exists in the cart
        $existingCartItem = CartItem::where('product_id', $request->productId)
            ->where('user_id', $request->user_id)
            ->with('product')
            ->first();
        if ($existingCartItem) {
            // Product already exists in cart, update the quantity
            $existingCartItem->quantity += $request->quantity;

            $this->reduceStock($request->productId, $request->quantity);

            $existingCartItem->save();
        }
        return $existingCartItem;
    }
    /**
     * Add products to the cart
     * @param Request $request is the instance
     */
    public function addToCart(Request $request)
    {

        // Validate the request
        $rules = [
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1', // Validate quantity
            'stock' => 'required| min:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails() || $request->quantity > $request->stock) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($this->addExistingItem($request)) {
            return $this->getCartItems();
        }

        $this->reduceStock($request->productId, $request->quantity);
        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->product_id = $request->productId;
        $cartItem->user_id =  $request->user_id;
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['cartItem' => $this->getCartItemsJSON()]);
    }

    /**
     * Reduce the stock of the product depending on the quantity in cart
     * @param Request $request data where quantity and id are stored
     */
    public function reduceStock($productId, $quantity)
    {

        // Find the related product
        $product = Product::findOrFail($productId);
        // Update the product stock
        $product->stock -= $quantity;
        // Save  the product 
        $product->save();
    }

    /**
     * Update products to the cart
     * @param Request $request is the instance
     * @param $id is the id reference 
     */
    public function updateCartItem(Request $request, $id)
    {

        try {
            $cartItem = CartItem::where('id', $id)
                ->where('user_id', $request->user_id)
                ->firstOrFail();

            $this->increaseStock($request->product_id, $cartItem->quantity - $request->quantity);

            $cartItem->quantity = $request->quantity;

            $cartItem->save();

            return response()->json(['cartItem' => $cartItem]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /** 
     * Removes an item in the cart
     * @param Request $request request instance  
     * @param $id id of the cart item
     */
    public function removeCartItem(Request $request, $id)
    {

        // Find the cart item by ID and delete it
        $cartItem =  CartItem::where('id', $id)
            ->where('user_id',  $request->user_id)
            ->firstOrFail();

        $this->increaseStock($cartItem->product_id, $cartItem->quantity);

        $cartItem->delete();

        return $this->getCartItems();
    }

    /**
     * increase the stock in products based on the quantity in the cart
     * @param $product_id is the id of the product
     * @param quantity is the amount in cart
     */
    public function increaseStock($product_id, $quantity)
    {
        // Find the related product
        $product = Product::findOrFail($product_id);

        // Update the product stock
        $product->stock += $quantity;
        $product->save();
    }

    /**
     * empties the cart of all items
     */
    public function emptyCart()
    {
        CartItem::where('user_id', auth()->id())->delete();
        return Inertia::render('Dashboard', ['content' => 'success']);
    }
}
