<?php


namespace App\Services;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Returns the collection of the products
     */
    public function cartContents()
    {
        return CartItem::with('product')->where('user_id', auth()->id())->get();
    }
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
     * Returns the JSON of the products
     */
    public function getCartItemsJSON()
    {
        $cartItems = $this->cartContents();

        return response()->json(['cartItem' => $cartItems]);
    }


    public function total()
    {
        $totalPrice = 0;
        $cartItems = $this->cartContents();
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);

            $subtotal = $cartItem->quantity * $product->price;

            $totalPrice += $subtotal;
        }
        return $totalPrice;
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

    public function update(Request $request, $id)
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


    public function delete(Request $request, $id)
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
     * empties the cart of all items
     */
    public function emptyCart()
    {
        CartItem::where('user_id', auth()->id())->delete();
    }

    public function handleSuccess(Request $request)
    {

        $this->emptyCart();
        $order = new Order();
        $order->payment_reference_number = $request->id;
        $order->total =  $request->total;
        $order->user_id = auth()->id();
        $order->save();
        return redirect('successpage');
    }
}
