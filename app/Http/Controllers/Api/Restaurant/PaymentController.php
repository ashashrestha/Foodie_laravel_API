<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Retrieve cart items for the logged-in user
        $user = Auth::user();
        $cartItems = $user->cartItems;

        // Initialize arrays to store details
        $menuNames = [];
        $prices = [];
        $quantities = [];

        // Calculate the total price
        $totalPrice = $cartItems->sum(function ($cartItem) use (&$menuNames, &$prices, &$quantities) {
            // Retrieve details from cart item and store them
            $menuNames[] = $cartItem->menu_name;
            $prices[] = $cartItem->menu->price;
            $quantities[] = $cartItem->quantity;

            return $cartItem->total;
        });

        // Create a payment record
        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->m_name = json_encode($menuNames); // Store as JSON array
        $payment->price = json_encode($prices); // Store as JSON array
        $payment->quantity = json_encode($quantities); // Store as JSON array
        $payment->total = $totalPrice;
        $payment->delivery_address = $request->input('delivery_address');
        $payment->payment_type = $request->input('payment_type');
        $payment->save();

        // Optionally, you can remove cart items here if needed

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully',
        ]);
    }
}