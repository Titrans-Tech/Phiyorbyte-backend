<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersCollection;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    // Paystack callback after successful payment
 public function paymentCallback(Request $request)
 {
     // Get the payment reference from the query string
     $reference = $request->query('reference');

     if (!$reference) {
         return back()->with('error', 'No payment reference provided.');
     }

     // Verify the payment using Paystack API
     try {
         $response = Http::withToken("sk_test_2480c735552c0c451064507cb47a75d736c5c969")
             ->get('https://api.paystack.co/transaction/verify/' . $reference);
             // dd($response);
         $result = $response->json();
         // $result = json_decode($response->getBody()->getContents(), true);
         

         $reference = $request->query('reference');
         if ($result['status'] && $result['status'] == 'success') {
         $payment = Order::where('reference', $reference)->first();
             
            $payment->update([
                 'status' => 'success',
                 'domain' => $result['data']['domain'],
                 'requested_amount' => $result['data']['requested_amount'],
                 'paidAt' => $result['data']['paidAt'],
                 'gateway_response' => $result['data']['gateway_response'],
                 'channel' => $result['data']['channel'],
                 'ip_address' => $result['data']['ip_address'],
                 'channel' => $result['data']['channel'],
                 'ip_address' => $result['data']['ip_address'],
                 'split_id' => $result['data']['split']['id'],
                 'name' => $result['data']['split']['name'],
                 
                 
                 'authorization_code' => $result['data']['authorization']['authorization_code'],
                 'bin' => $result['data']['authorization']['bin'],
                 'last4' => $result['data']['authorization']['last4'],
                 'exp_month' => $result['data']['authorization']['exp_month'],
                 'channel' => $result['data']['authorization']['channel'],
                 'card_type' => $result['data']['authorization']['card_type'],
                 'bank' => $result['data']['authorization']['bank'],
                 'country_code' => $result['data']['authorization']['country_code'],
                 'brand' => $result['data']['authorization']['brand'],
                 'reusable' => $result['data']['authorization']['reusable'],
                 'signature' => $result['data']['authorization']['signature'],
                 
                 'split_code' => $result['data']['split']['split_code'],
                 'type' => $result['data']['split']['formula']['type'],
                 'bearer_type' => $result['data']['split']['formula']['bearer_type'],
                 'bearer_subaccount' => $result['data']['split']['formula']['bearer_subaccount'],
                 
                 
                 
                 // 'original_share' => $result['data']['split']['formula']['subaccounts']['original_share'],
                 // 'split_fees' => $result['data']['split']['formula']['subaccounts']['fees'],
             // 'share' => ['split']['formula']['subaccounts']['share'],
             // 'original_share' => ['split']['formula']['subaccounts']['original_share'],
             // 'subaccount_code' => ['split']['formula']['subaccounts']['subaccount_code'],
             // 'subaccount_id' => ['split']['formula']['subaccounts']['id'],
             // 'subaccount_name' => ['split']['formula']['subaccounts']['name'],
             // 'integration' => ['split']['formula']['subaccounts']['integration'],
             

             // 'paystack' => ['split']['shares']['paystack'],
             // 'subaccount_amount' => ['split']['shares']['subaccounts']['amount'],
             // 'original_share' => ['split']['shares']['subaccounts']['original_share'],
             // 'shere_fees' => ['split']['shares']['subaccounts']['shere_fees'],
             // 'shere_subaccount_code' => ['split']['shares']['subaccounts']['subaccount_code'],
             // 'shere_id' => ['split']['shares']['subaccounts']['id'],
             // 'share_integration' => ['split']['shares']['subaccounts']['integration'],

                
            ]);
            // dd($result);
            return response()->json([
                // 'payment' => $payment,
                'messsage' => 'Thank You for your Patronage'
            ]);
            //return redirect()->route('pages.thankyou')->with('success', 'Thank You for your Patronage');
         //    return redirect()->route('payment.success')->with('success', 'Payment successful!');
         } else {
            return response()->json([
                'messsage' => 'Payment Failed'
            ]);
            //return redirect()->route('payment.failed')->with('error', 'Payment failed. Please try again.');
         }
     } catch (RequestException $e) {
        return response()->json([
            'messsage' => 'Payment Failed'
        ]);
        // return redirect()->route('payment.failed')->with('error', 'An error occurred. Please try again.');
     }

     throw $e; // or handle the error as needed

 }

 public function thankyou(){

    return response()->json([
        'message' => 'Thank You for Your Patronage'
    ]);
 }

 public function viewmyorder(){
    $view_myoders = Order::where('user_id', auth()->user()->id)->latest()->get();
    return new  ($view_myoders);
    
 }
 public function vieworder(){
    $view_oders = Order::latest()->get();
    return new OrdersCollection($view_oders);
 }

 public function myordersproducts(){
    $view_myproducts = Order::where('user_id', auth()->user()->id)->latest()->get();
//     return response()->json([
//         'order' => $view_myproducts
//  ]);

return new OrdersCollection ($view_myproducts);

 }

 public function ordermydetail($id){
    $view_myordetails = Order::find($id);
    return response()->json([
        'order' => $view_myordetails
 ]);
 }
 
 
}
