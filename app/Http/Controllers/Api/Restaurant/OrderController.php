<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        // Retrieve the logged-in user's order history
        $user = Auth::user();
        $orderHistory = $user->orders;

        return response()->json([
            'status' => 'success',
            'message' => 'Order history retrieved successfully',
            'data' => $orderHistory,
        ]);
    }

    public function store(Request $request)
{
    // Validate the request (you can add more validation rules as needed)
    $request->validate([
        'menu_id' => 'required|exists:menus,id',
    ]);

    // Find the menu item to access restaurant name, m_name, and photo_path
    $menu = Menu::findOrFail($request->input('menu_id'));

    // Create a new order
    $order = new Order([
        'user_id' => Auth::id(),
        'menu_id' => $request->input('menu_id'),
        'restaurant_name' => $menu->restaurant->name,
        'menu_name' => $menu->m_name, 
        'photo_path' => $menu->photo_path, 
    ]);

    $order->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Order placed successfully',
        'data' => $order,
    ]);
}


    // public function store(Request $request)
    // {
    //     // Validate the request (you can add more validation rules as needed)
    //     $request->validate([
    //         'menu_id' => 'required|exists:menus,id',
    //         'restaurant_name' => 'required|string',
    //         'menu_name' => 'required|string',
    //         'photo_path' => 'required|string',
    //     ]);

    //     // Create a new order
    //     $order = new Order([
    //         'user_id' => Auth::id(),
    //         'menu_id' => $request->input('menu_id'),
    //         'restaurant_name' => $request->input('restaurant_name'),
    //         'menu_name' => $request->input('menu_name'),
    //         'photo_path' => $request->input('photo_path'),
    //     ]);

    //     $order->save();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Order placed successfully',
    //         'data' => $order,
    //     ]);
    // }

    // public function placeOrder(Request $request)
    // {
    //     // Calculate the total price based on the user's cart items
    //     $user = Auth::user();
    //     $cartItems = $user->cartItems()->with('menu')->get();
    //     $totalPrice = $cartItems->sum(function ($cartItem) {
    //         return $cartItem->menu->price * $cartItem->quantity;
    //     });

    //     // Create a new order
    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'total_price' => $totalPrice,
    //         'status' => 'pending', // You can set the initial status
    //     ]);

    //     // Associate cart items with the order and update their status
    //     $cartItems->each(function ($cartItem) use ($order) {
    //         $cartItem->order_id = $order->id;
    //         $cartItem->status = 'ordered';
    //         $cartItem->save();
    //     });

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Order placed successfully',
    //         'order' => $order,
    //     ]);
    // }

    // public function getOrderHistory()
    // {
    //     // Retrieve the order history for the logged-in user
    //     $user = Auth::user();
    //     $orderHistory = Order::where('user_id', $user->id)->orderByDesc('created_at')->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Order history retrieved successfully',
    //         'order_history' => $orderHistory,
    //     ]);
    // }
}
