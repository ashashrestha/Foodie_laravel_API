<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;


class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
    
        if ($menus->count() > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Menus Found Successfully',
                'data' => $menus
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Records Found',
            ], 404);
        }
    }
    
    public function search(Request $request)
    {
        // Validate the search query
        $request->validate([
            'query' => 'required|string|max:255',
        ]);
    
        // Get the validated search query from the request
        $query = $request->input('query');
    
        // Perform a case-insensitive search using ILIKE
        $results = Menu::where('m_name', 'LIKE', '%' . $query . '%')
        ->orWhere('m_name', 'LIKE', '%' . $query . '% COLLATE utf8_general_ci')
        ->get();

    
        if ($results->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No matching menu items found',
                'data' => [],
            ], 200);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Search results retrieved successfully',
            'data' => $results,
        ], 200);
    }
    
    
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'restaurant_id' => 'required',
                'c_id' => 'required',
                'm_name' => 'required|max:255|string|unique:menus',
                'portion_size' => 'required',
                'price' => 'required|numeric|min:0',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            $menu = $request->isMethod('put') ? Menu::findOrFail($request->item_id) : new Menu;

            $menu->fill([
                'item_id' => $request->input('item_id'),
                'restaurant_id' => $request->input('restaurant_id'),
                'c_id' => $request->input('c_id'),
                'm_name' => $request->input('m_name'),
                'portion_size' => $request->input('portion_size'),
                'price' => $request->input('price'),
                'photo_path' => $request->input('photo_path'),
            ]);

            if ($request->file('photo_path')) {
                $image = $request->file('photo_path');
                $path = public_path('images/menus/');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
                $menu->photo_path = 'images/menus/' . $filename;
            }

            if ($menu->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Menu Added Successfully',
                    'data' => $menu
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
        $menu = Menu::find($id);
        if($menu){
            return response()->json([
                'status' => true,
                'data' => $menu
            ],200);
        }else{
            return response()->json([
                'status' => true,
                'message' => "Menu not found"
            ],404);
        }
    }

    public function showMenuWithDetails($id)
    {
        $menu = Menu::select(
                'menus.id',
                'menus.m_name as menu_name', 
                'menus.portion_size',
                'menus.price',
                'menus.photo_path',
                'restaurants.name as restaurant_name',
                'categories.c_name as category_name'
            )
            ->join('restaurants', 'menus.restaurant_id', '=', 'restaurants.id')
            ->join('categories', 'menus.c_id', '=', 'categories.id')
            ->where('menus.id', $id)
            ->first();
    
        if (!$menu) {
            return response()->json([
                'status' => false,
                'message' => 'Menu not found',
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Menu Found Successfully',
            'data' => $menu
        ], 200);
    }
    
    public function update(Request $request, string $id)
    {
        try {
            $filename = "";
            $validator = Validator::make(
                $request->all(),
                [
                    'restaurant_id' => 'required',
                    'c_id' => 'required',
                    'm_name' => 'required|max:255|string|unique:menus,m_name,'.$id,
                    'portion_size' => 'required',
                    'price' => 'required|numeric|min:0',
                    'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            $menu = Menu::findOrFail($id);
            $menu->restaurant_id = $request->restaurant_id;
            $menu->c_id = $request->c_id;
            $menu->m_name = $request->m_name;
            $menu->portion_size = $request->portion_size;
            $menu->price = $request->price;

            if ($request->file('photo_path')) {
    
                $image = $request->file('photo_path');
                $path = public_path('images/'); 
                if ($menu->photo_path) { // Check if a photo exists and delete it
                    @unlink($path . $menu->photo_path);
                }
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
                $menu->photo_path = $filename;

                $menu->save();
            }

            $menu->save();

            return response()->json([
                'status' => true,
                'message' => 'Menu Updated Successfully',
                'data' => $menu
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {

        $menu = Menu::findOrFail($id);

        $menu->delete();
        return response()->json([
            'status' => true,
            'message' => 'Menu Deleted Successfully',
        ], 200);
    }
}