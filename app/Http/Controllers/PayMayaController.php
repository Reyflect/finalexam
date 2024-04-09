<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CartItemController;

class PayMayaController extends Controller
{

    public $total = 0;

    public function extractJsonToArray(Request $request)
    {
        // Get the array of JSON strings from the request

        $jsonArray = $request->input('cartItems');
        // $stringifiedJson = json_decode($jsonArray, true);
        //    dd($stringifiedJson);
        $objectData = json_decode($jsonArray);

        //dd($objectData);
        //      



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

            $this->total +=  ($jsonData->quantity * $jsonData->product->price);
        }


        return $formattedArrays;
    }


    public function postRequest(Request $request)
    {


        $curl = curl_init();
        $certificateFilePath = public_path('cacert.pem');

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
                'redirectUrl' => [
                    'success' => 'http://localhost:8000/success',
                    'failure' => 'http://localhost:8000/fail',
                    'cancel' => 'http://localhost:8000/checkout'
                ],
                'items' => ($this->extractJsonToArray($request)),
                'requestReferenceNumber' => '5fc10b93-bdbd-4f31-b31d-4575a3785009'
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
            echo $response;
            $json = json_decode($response, true);
            return redirect($json['redirectUrl']);
        }
    }
}
//