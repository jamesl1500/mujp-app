<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    public function donate()
    {
        return view('frontend.donate');
    }

    public function store(Request $request)
    {
        // Set your Stripe API key.
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get the payment amount and email address from the form.
        $amount = $request->input('donation-money') * 100;
        $email = $request->input('email');

        // Create a new Stripe customer.
        $customer = \Stripe\Customer::create([
            'name' => $request->input('fname') . ' ' . $request->input('lname'),
            'email' => $email,
            'source' => $request->input('stripeToken'),
        ]);
        
        // Create a new Stripe charge.
        $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $amount,
            'currency' => 'usd',
        ]);

        return redirect()->route('frontend.donate')->with('success', 'Thank you for your donation!');
    }
}
