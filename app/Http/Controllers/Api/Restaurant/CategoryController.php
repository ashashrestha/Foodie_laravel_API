<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('restaurantCat')->paginate(5);
        if($categories->count() > 0){
            return response()->json([
                'status' => true,
                'message' => 'Category Found Successfully',
                'data' => $categories
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

            $validator = Validator::make($request->all(), [
                'c_name' => 'required|max:255|string',
                'restaurant_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            $category = $request->isMethod('put') ? Category::findOrFail($request->c_id) : new Category;

            $category->fill([
                'c_id' => $request->input('c_id'),
                'c_name' => $request->input('c_name'),
                'restaurant_id' => $request->input('restaurant_id'),
            ]);

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Category Added Successfully',
                    'data' => $category
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
        $category = Category::find($id);
        if($category){
            return response()->json([
                'status' => true,
                'category' => $category
            ],200);
        }else{
            return response()->json([
                'status' => true,
                'message' => "No Such Category Found"
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $filename = "";
            $validator = Validator::make(
                $request->all(),
                [
                    'c_name' => 'required|max:255|string',
                    'restaurant_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            $category = Category::findOrFail($id);
            $category->c_name = $request->c_name;
            $category->restaurant_id = $request->restaurant_id;

            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Category Updated Successfully',
                'data' => $category
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

        $category = Category::findOrFail($id);

        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category Successfully Deleted',
        ], 200);
    }

}
