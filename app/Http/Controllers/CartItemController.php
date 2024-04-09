<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function getCartItems()
    {
        $cartItems = CartItem::with('product')->get(); // Eager load product details

        return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $cartItems]);
    }

    public function getCartItemsCheckout()
    {
        $cartItems = CartItem::with('product')->get(); // Eager load product details

        return Inertia::render('Dashboard', ['content' => 'checkout', 'cartItems' => $cartItems]);
    }

    public function getCartItemsJSON()
    {
        $cartItems = CartItem::with('product')->get(); // Eager load product details

        return response()->json(['cartItem' => $cartItems]);
    }

    public function addToCart(Request $request)
    {
        // Validate the request
        $request->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1', // Validate quantity
        ]);

        // Check if the product already exists in the cart
        $existingCartItem = CartItem::where('product_id', $request->productId)->first();

        if ($existingCartItem) {
            // Product already exists in cart, update the quantity
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();

            return response()->json(['message' => 'Item quantity updated in cart', 'cartItem' => $existingCartItem]);
        }

        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->product_id = $request->productId;
        $cartItem->quantity = $request->quantity; // Use the provided quantity
        $cartItem->save();

        return response()->json(['message' => 'Item added to cart', 'cartItem' => $cartItem]);
    }

    public function updateCartItem(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated', 'cartItem' => $cartItem]);
    }

    public function removeCartItem($id)
    {
        // Find the cart item by ID and delete it
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        $cartItems = CartItem::with('product')->get(); // Eager load product details

        return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $cartItems]);
    }
}
