<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

        public function order(Request $request){
        // $field = $request->all();
        $order = new Order;
        $order->name = $request->name;
        $order->adress = $request->adress;
        $order->phone = $request->phone;
        $order->tempat_id = $request->tempat_id;
        $order->arena_id = $request->arena_id;
        $order->total_price = $request->total_price;
        $order->time_from = $request->time_from;
        $order->time_to = $request->time_to;
        $order->status = 'unpaid';
        $order->save();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ),
            'customer_details' => array(
                'first_name' => $request->name,
                'address' => $request->adress,
                'phone' => $request->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json([
            'status' => 200,
            'token' => $snapToken,
        ]);

    }

}
