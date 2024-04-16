<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class CartItemController extends Controller
{
    public function __construct(
        public CartService $services
    ) {
    }
    /**
     * Gets the cart items and redirect towards a specific page
     * @param $content is the landing page where it will be redirected
     */
    public function getCartItems($content = 'cart')
    {
        return $this->services->getCartItems($content);
        // $cartItems = $this->cartContents();
        // return Inertia::render('Dashboard', ['content' => $content, 'cartItem' => $cartItems]);
    }

    /**
     * Returns the collection of the products
     */
    public function cartContents()
    {
        return $this->services->cartContents();
        //return CartItem::with('product')->where('user_id', auth()->id())->get();
    }
    /**
     * Returns the JSON of the products
     */
    public function getCartItemsJSON()
    {
        return $this->services->getCartItemsJSON();

        //return response()->json(['cartItem' => $cartItems]);
    }

    public function totalCart()
    {
        return $this->services->total();
    }


    /**
     * Add products to the cart
     * @param Request $request is the instance
     */
    public function addToCart(Request $request)
    {
        return $this->services->addToCart($request);
    }

    /**
     * Update products to the cart
     * @param Request $request is the instance
     * @param $id is the id reference 
     */
    public function updateCartItem(Request $request, $id)
    {
        return $this->services->update($request, $id);
    }

    /** 
     * Removes an item in the cart
     * @param Request $request request instance  
     * @param $id id of the cart item
     */
    public function removeCartItem(Request $request, $id)
    {
        return $this->services->delete($request, $id);
    }



    /**
     * empties the cart of all items
     */
    public function success(Request $request)
    {
        return $this->services->handleSuccess($request);
    }
}
