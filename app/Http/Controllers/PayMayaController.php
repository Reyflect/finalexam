<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use GuzzleHttp\Client;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PayMayaController extends Controller
{


    /**
     * Formats the checkoutcart to a json
     * @param Request $request of the instance
     */
    public function extractJsonToArray(Request $request)
    {
        $jsonArray = $request->input('cartItems');
        $objectData = json_decode($jsonArray);

        // Initialize an empty array to hold the formatted arrays
        $formattedArrays = [];

        // Loop through each JSON string in the array
        foreach ($objectData as $jsonData) {
            // Create and add the formatted array to the result array
            $formattedArrays[] = [
                'amount' => ['value' => $jsonData->product->price],
                'totalAmount' => ['value' => ($jsonData->quantity * $jsonData->product->price)],
                'name' => $jsonData->product->name,
                'quantity' => $jsonData->quantity,
            ];
        }


        return $formattedArrays;
    }


    /**
     * process the checkout
     * @param Request $request
     */
    public function postRequest(Request $request)
    {

        return $this->paymaya($request);
        //$v = $this->paymaya($request);

        //  return Inertia::render('Dashboard', ['content' => 'checkout', 'cartItems' => $this->cartContents(), 'maya' => $v]);
        //  return Inertia::render('Dashboard', [$v]);
    }

    public function generateReferenceNumber($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $reference = '';
        $maxIndex = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $reference .= $characters[rand(0, $maxIndex)];
        }

        return $reference;
    }
    public function paymaya(Request $request)
    {

        $curl = curl_init();
        $certificateFilePath = public_path('cacert.pem');
        $reference_number = $this->generateReferenceNumber();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://pg-sandbox.paymaya.com/checkout/v1/checkouts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CAINFO => $certificateFilePath,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'totalAmount' => [
                    'currency' => 'PHP',
                    'value' => $request->input('total_price'),
                ],
                'items' => ($this->extractJsonToArray($request)),
                'requestReferenceNumber' => $reference_number,
                'redirectUrl' => [
                    'success' => 'http://localhost:8000/success?total=' . $request->input('total_price') . '&id=' . $reference_number,
                    'failure' => 'http://localhost:8000/fail',
                    'cancel' => 'http://localhost:8000/checkout'
                ],
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic cGstWjBPU3pMdkljT0kyVUl2RGhkVEdWVmZSU1NlaUdTdG5jZXF3VUU3bjBBaDpzay1YOHFvbFlqeTYya0l6RWJyMFFSSzFoNGI0S0RWSGFOY3dNWWszOWpJblNs",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //  echo $response;

            $json = json_decode($response, true);
            return redirect($json['redirectUrl']);
            //return response()->json($json);
        }
    }


    // /**
    //  * Returns the JSON of the products
    //  */
    // public function getCartItemsJSON()
    // {
    //     $cartItems = $this->cartContents();

    //     return response()->json(['cartItem' => $cartItems]);
    // }

    // /**
    //  * Returns the collection of the products
    //  */
    // public function cartContents()
    // {
    //     return CartItem::with('product')->where('user_id', auth()->id())->get();
    // }
}
