<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BestFood;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BestfoodController extends Controller
{
    public function index()
    {
        $bestFoods = BestFood::with('menu', 'restaurant')->get();

        return response()->json([
            'status' => 'success',
            'data' => $bestFoods,
        ]);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'menu_id' => 'required|integer|exists:menus,id',
        'restaurant_id' => 'required|integer|exists:restaurants,id',
        'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'name' => 'required|string',
        'm_name' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Create a new featured food item
    $bestFood = new BestFood();
    $bestFood->menu_id = $request->input('menu_id');
    $bestFood->restaurant_id = $request->input('restaurant_id');
    $bestFood->name = $request->input('name');
    $bestFood->m_name = $request->input('m_name');

    // Handle file upload and storage
    if ($request->hasFile('photo_path')) {
        $imagePath = $request->file('photo_path')->store('best_food_images', 'public');
        $bestFood->photo_path = $imagePath;
    }

    $bestFood->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Best food item created successfully',
        'data' => $bestFood,
    ]);
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'menu_id' => 'required|integer|exists:menus,id',
        'restaurant_id' => 'required|integer|exists:restaurants,id',
        'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'name' => 'required|string',
        'm_name' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Find the featured food item by ID
    $bestFood = BestFood::find($id);

    if (!$bestFood) {
        return response()->json([
            'status' => 'error',
            'message' => 'Featured food item not found',
        ], 404);
    }

    $bestFood->menu_id = $request->input('menu_id');
    $bestFood->restaurant_id = $request->input('restaurant_id');
    $bestFood->name = $request->input('name');
    $bestFood->m_name = $request->input('m_name');

    // Handle file upload and storage
    if ($request->hasFile('photo_path')) {
        // Delete the old image
        if (!empty($bestFood->photo_path)) {
            Storage::disk('public')->delete($bestFood->photo_path);
        }
        
        $imagePath = $request->file('photo_path')->store('best_food_images', 'public');
        $bestFood->photo_path = $imagePath;
    }

    $bestFood->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Featured food item updated successfully',
        'data' => $bestFood,
    ]);
}

    public function destroy($id)
    {
        // Find the featured food item by ID
        $bestFood = BestFood::find($id);

        if (!$featuredFood) {
            return response()->json([
                'status' => 'error',
                'message' => 'Featured food item not found',
            ], 404);
        }

        // Delete the featured food item and its associated photo (if stored in storage)
        if (!empty($bestFood->photo_path)) {
            Storage::disk('public')->delete($bestFood->photo_path);
        }

        $bestFood->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Featured food item deleted successfully',
        ]);
    }
}