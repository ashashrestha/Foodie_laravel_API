<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class CartController extends Controller
{
    public function index()
        {
            // Retrieve the logged-in user's cart items with associated menu and restaurant data
            $user = Auth::user();
            $cartItems = $user->cartItems()->with(['menu.restaurant'])->get();

            // Create an array to store the needed information
            $formattedCartItems = [];

            foreach ($cartItems as $cartItem) {
                $formattedCartItems[] = [
                    'id' => $cartItem->id,
                    'menu_name' => $cartItem->menu->m_name,
                    'restaurant_name' => $cartItem->menu->restaurant->name,
                    'photo_path' => $cartItem->menu->photo_path,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->menu->price,
                    'total' => $cartItem->total,
                ];
            }

            // Calculate the cart's total
            $cartTotal = $cartItems->sum(function ($cartItem) {
                return $cartItem->menu->price * $cartItem->quantity;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Cart items retrieved successfully',
                'data' => [
                    'cart_items' => $formattedCartItems,
                    'cart_total' => $cartTotal,
                ],
            ]);
        }

    public function addToCart(Request $request, $menuId)
        {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the menu item
        $menu = Menu::findOrFail($menuId);

        // Calculate the total based on the quantity and menu item's price
        $total = $menu->price * $request->input('quantity');

        // Check if the item is already in the cart
        $cartItem = Cart::where([
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
        ])->first();

        if ($cartItem) {
            // If it's in the cart, update the quantity, total, restaurant name, and menu name
            $cartItem->update([
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'restaurant_name' => $menu->restaurant->name, 
                'menu_name' => $menu->m_name, 
                'photo_path' => $menu->photo_path, 
            ]);
        } else {
            // If it's not in the cart, create a new cart item with the calculated values
            Cart::create([
                'user_id' => Auth::id(),
                'menu_id' => $menuId,
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'restaurant_name' => $menu->restaurant->name,
                'menu_name' => $menu->m_name, 
                'photo_path' => $menu->photo_path, 
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Item added to cart successfully',
        ]);
        }

    public function updateCartItem(Request $request, $cartItemId)
        {
            // Validate the request
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
        
            // Find the cart item
            $cartItem = Cart::findOrFail($cartItemId);
        
            // Update the quantity
            $cartItem->update([
                'quantity' => $request->input('quantity'),
            ]);
        
            // Recalculate the total based on the updated quantity and menu item's price
            $total = $cartItem->menu->price * $request->input('quantity');
            $cartItem->update([
                'total' => $total,
            ]);
        
            // Calculate the updated cart total
            $user = Auth::user();
            $cartItems = $user->cartItems()->with('menu')->get(); // Eager load the associated menu
            $cartTotal = $cartItems->sum(function ($cartItem) {
                return $cartItem->menu->price * $cartItem->quantity;
            });
        
            return response()->json([
                'status' => 'success',
                'message' => 'Cart item updated successfully',
                'cart_total' => $cartTotal, // Include the updated total in the response
            ]);
        }
    
    

    public function removeCartItem($cartItemId)
    {
        // Find and delete the cart item
        $cartItem = Cart::findOrFail($cartItemId);
        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart successfully',
        ]);
    }
}
