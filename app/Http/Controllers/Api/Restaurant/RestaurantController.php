<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

    class RestaurantController extends Controller
    {
        public function index()
        {
            $restaurants = Restaurant::all();
            if($restaurants->count() > 0){
                return response()->json([
                    'status' => true,
                    'message' => 'Restaurant Details Found Successfully',
                    'data' => $restaurants
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Records Found',
                ], 404);
            }
        }

        public function store(Request $request)
        {
            try {
                $filename = "";

                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|string|unique:restaurants',
                    'type_id' => 'required',
                    'address' => 'required|max:255|string',
                    'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'delivery_time' => 'required|string'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    ], 401);
                }

                $restaurant = $request->isMethod('put') ? Restaurant::findOrFail($request->restaurant_id) : new Restaurant;

                $restaurant->fill([
                    'restaurant_id' => $request->input('restaurant_id'),
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'type_id' => $request->input('type_id'),
                    'photo_path' => $filename,
                    'delivery_time' => $request->input('delivery_time'),
                ]);

                if ($request->file('photo_path')) {
                    $image = $request->file('photo_path');
                    $path = public_path('images/');
                    !is_dir($path) && mkdir($path, 0777, true);
                    $filename = time() . '.' . $image->getClientOriginalExtension(); // Use 'getClientOriginalExtension()' to get the file extension
                    $image->move($path, $filename);
                    $restaurant->photo_path = $filename;
                }

                if ($restaurant->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Restaurant Added Successfully',
                        'data' => $restaurant
                    ], 200);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }

        public function showById($id)
        {
            $restaurant = Restaurant::find($id);
            if($restaurant){
                return response()->json([
                    'status' => true,
                    'restaurant' => $restaurant
                ],200);
            }else{
                return response()->json([
                    'status' => true,
                    'message' => "No Such Type Found"
                ],404);
            }
        }

    public function showRestaurant(string $id)
    {
        try {
            // Assuming $id is the restaurant's ID
            $restaurant = Restaurant::with(['menus', 'categories'])
                ->find($id);
    
            if ($restaurant) {
                return response()->json([
                    'status' => true,
                    'message' => 'Restaurant Found Successfully',
                    'data' => $restaurant
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Restaurant Not Found',
                    'data' => null
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

        
    public function update(Request $request, string $id)
    {
        try {
            $filename = "";

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|string|unique:restaurants,name,' . $id,
                'type_id' => 'required',
                'address' => 'required|max:255|string',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'delivery_time' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            $restaurant = Restaurant::findOrFail($id);

            $restaurant->fill([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'type_id' => $request->input('type_id'),
                'delivery_time' => $request->input('delivery_time'),
            ]);

            if ($request->file('photo_path')) {
                $image = $request->file('photo_path');
                $path = public_path('images/');
                !is_dir($path) && mkdir($path, 0777, true);
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);

                // Delete the old image if it exists
                if (!empty($restaurant->photo_path)) {
                    $oldImagePath = $path . $restaurant->photo_path;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $restaurant->photo_path = $filename;
            }

            if ($restaurant->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Restaurant Updated Successfully',
                    'data' => $restaurant
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

        public function destroy(string $id)
        {
    
            $restaurant = Restaurant::findOrFail($id);

            $restaurant->delete();
            return response()->json([
                'status' => true,
                'message' => 'Restaurant Successfully Deleted',
            ], 200);
        }
    }
