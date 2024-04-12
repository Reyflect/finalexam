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

    public function add(Request $request)
    {
        dd(auth()->id());
        $rules = [
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1', // Validate quantity
            'stock' => 'required| min:1'
        ];
        $validator = Validator::make($request->all(), $rules);
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

        // Check if the product already exists in the cart
        $existingCartItem = CartItem::where('product_id', $request->productId)
            ->where('user_id', auth()->id())
            ->with('product')
            ->first();

        if ($existingCartItem) {
            // Product already exists in cart, update the quantity
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->product->stock -= $request->quantity;
            $existingCartItem->product->save();
            $existingCartItem->save();
            $this->getCartItems();
            return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $this->getCartItemsJSON()]);
        }

        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->product_id = $request->productId;
        $cartItem->user_id = auth()->id();
        $cartItem->quantity = $request->quantity;

        // Find the related product
        $product = Product::findOrFail($request->productId);

        // Update the product stock
        $product->stock -= $request->quantity;

        // Save both the product and cart item
        $product->save();
        $cartItem->save();
        //  return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $this->getCartItemsJSON()]);
        return response()->json(['cartItem' => $this->getCartItemsJSON()]);
    }
    /**
     * Update products to the cart
     * @param Request $request is the instance
     * @param $id is the id reference 
     */
    public function updateCartItem(Request $request, $id)
    {

        try {
            // Find the cart item by ID for the authenticated user
            $cartItem = CartItem::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            // Find the related product
            $product = Product::findOrFail($request->product_id);


            if ($request->quantity + $cartItem->quantity > $product->stock) {
                return response()->json(['error' => 'stock error'], 400);
            }

            // Update the product stock
            if ($cartItem->quantity > $request->quantity) {
                $product->stock += $cartItem->quantity - $request->quantity;
            } else if ($cartItem->quantity < $request->quantity) {
                $product->stock -= $request->quantity - $cartItem->quantity;
            }
            // Update the quantity and save
            $cartItem->quantity = $request->quantity;


            $product->save();
            $cartItem->save();

            return response()->json(['cartItem' => $cartItem, 'product' => $product]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /** */
    public function removeCartItem($id)
    {
        // Find the cart item by ID and delete it
        $cartItem =  CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Find the related product
        $product = Product::findOrFail($cartItem->product_id);

        // Update the product stock
        $product->stock += $cartItem->quantity;
        $product->save();

        $cartItem->delete();

        $cartItems = CartItem::with('product')->get(); // Eager load product details

        return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $cartItems]);
    }

    public function emptyCart()
    {
        CartItem::where('user_id', auth()->id())->delete();
        return Inertia::render('Dashboard', ['content' => 'success']);
    }
}
