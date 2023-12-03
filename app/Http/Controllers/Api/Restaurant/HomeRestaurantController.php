<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeRestaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class HomeRestaurantController extends Controller
{
    public function index()
    {
        $restaurantTypes = HomeRestaurant::all(); 
        if($restaurantTypes->count() > 0){
            return response()->json([
                'status' => true,
                'message' => 'Restaurant Type Found Successfully',
                'data' => $restaurantTypes
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No Records Found',
            ], 404);
        }
    }

    public function create(Request $request)
    {
       
    }

    public function store(Request $request)
    {
        try{
            $filename = "";
            $validateType = Validator::make(
                $request->all(),
                [
                    'type' => 'required',
                    'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]
            );
            if ($validateType->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateType->errors()
                ], 401);
            }
    
            $restaurantType = HomeRestaurant::create([
                'type' => $request->type,
            ]);
    
            if ($request->file('photo_path')) {
                $image = $request->file('photo_path');
                $path = public_path('images/');
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
                $restaurantType->photo_path = $filename;
                $restaurantType->save();
            }
            $restaurantType->save();
            return response()->json([
                'status' => true,
                'message' => 'Restaurant Type Added Successfully',
                'data' => $restaurantType
            ], 200);
        }catch (\Throwable $th) {
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
            $validateType = Validator::make(
                $request->all(),
                [
                    'type' => 'required',
                    'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]
            );

            if ($validateType->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateType->errors()
                ], 401);
            }
            $restaurantType = HomeRestaurant::findOrFail($id);
            $restaurantType->type = $request->type;
            //while updating photo you need to check there is photo uploaded or not if ulpoaded then you need to delete the last one and update new one
            if ($request->file('photo_path')) {

                $image = $request->file('photo_path');
                $path = public_path('images/'); ##create images in public folder
                if ($path . $restaurantType->photo_path) {  ##deleting old photo
                    @unlink($path . $restaurantType->photo_path);
                }
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
                $restaurantType->photo_path = $filename;
                $restaurantType->save();
            }

            $restaurantType->save();
            return response()->json([
                'status' => true,
                'message' => 'Restaurant Type Updated Successfully',
                'data' => $restaurantType
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

        $restaurantType = HomeRestaurant::findOrFail($id);
        $path = public_path('images/');
        if ($path . $restaurantType->photo_path) {
            @unlink($path . $restaurantType->photo_path);
        }
        $restaurantType->delete();
        return response()->json([
            'status' => true,
            'message' => 'Restaurant Type Successfully Deleted',
        ], 200);
    }
}
