<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function getCartItems()
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();

        return Inertia::render('Dashboard', ['content' => 'cart', 'cartItems' => $cartItems]);
    }

    public function getCartItemsCheckout()
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();

        return Inertia::render('Dashboard', ['content' => 'checkout', 'cartItems' => $cartItems]);
    }

    public function getCartItemsJSON()
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();

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
        $existingCartItem = CartItem::where('product_id', $request->productId)->where('user_id', auth()->id())
            ->first();

        if ($existingCartItem) {
            // Product already exists in cart, update the quantity
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();

            return response()->json(['cartItem' => $existingCartItem]);
        }

        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->product_id = $request->productId;
        $cartItem->user_id = auth()->id();
        $cartItem->quantity = $request->quantity; // Use the provided quantity
        $cartItem->save();

        return response()->json(['cartItem' => $this->getCartItemsJSON()]);
    }

    public function updateCartItem(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // Find the cart item by ID for the authenticated user
            $cartItem = CartItem::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Update the quantity and save
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json(['cartItem' => $cartItem]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating cart item'], 500);
        }
    }

    public function removeCartItem($id)
    {
        // Find the cart item by ID and delete it
        $cartItem =  CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
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
