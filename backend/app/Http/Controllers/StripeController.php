<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    //
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'Event Ticket',
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => $request->quantity,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8080/payment_success',
            'cancel_url' => 'http://127.0.0.1:8080/payment_cancel',
        ]);

        return response()->json(['id' => $session->id]);
    }
}
