<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestoType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;


class RestaurantTypeController extends Controller
{
    public function index()
    {
        $restaurantTypes = RestoType::paginate(3); 
        return view('BackEnd.RestaurantType.index', compact('restaurantTypes'));
    }

    public function create()
    {
        $restaurantType = new RestoType();
        return view('BackEnd.RestaurantType.form',compact('restaurantType'));
    }

    public function store(Request $request)
    {
        $filename = "";
        $validateType = Validator::make(
            $request->all(),
            [
                'type' => 'required',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]
        );

        $restaurantType = RestoType::create([
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

        return Redirect::to('/dashboard/restaurantType')->with(['type' => 'success', 'message' => 'Restaurant Type added successfully']);
    }

     public function edit($id)
    {
        $restaurantType = RestoType::findOrFail($id);
        return view('BackEnd.RestaurantType.edit', compact('restaurantType'));
    }

    public function update(Request $request, $id)
    {
        $restaurantType = RestoType::findOrFail($id);

        $validateType = Validator::make(
            $request->all(),
            [
                'type' => 'required',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]
        );

        if ($validateType->fails()) {
            return redirect()->back()->withErrors($validateType)->withInput();
        }

        $restaurantType->type = $request->type;

        if ($request->file('photo_path')) {
            $image = $request->file('photo_path');
            $path = public_path('images/');
            !is_dir($path) && mkdir($path, 0777, true);
            $filename = time() . '.' . $image->extension();
            $image->move($path, $filename);
            $restaurantType->photo_path = $filename;
        }

        $restaurantType->save();

        return redirect('/dashboard/restaurantType')->with(['type' => 'success', 'message' => 'Restaurant Type updated successfully']);
    }
    public function destroy($id)
    {
        $restaurantType = RestoType::findOrFail($id);
    
        // Delete the associated photo if it exists
        if ($restaurantType->photo_path) {
            $path = public_path('images/') . $restaurantType->photo_path;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    
        // Delete the restaurant type
        $restaurantType->delete();
    
        return redirect()->route('show_type_table')->with(['type' => 'success', 'message' => 'Restaurant Type deleted successfully']);
    }
    
    }





// public function update(Request $request, string $id)
// {
//     $restaurantType = RestoType::findOrFail($id);

//     $validateType = Validator::make(
//         $request->all(),
//         [
//             'type' => 'required',
//             'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
//         ]
//     );

//     $restaurantType->type = $request->type;
    
//     if ($request->file('photo_path')) {
//         $image = $request->file('photo_path');
//         $path = public_path('images/');
//         !is_dir($path) &&
//             mkdir($path, 0777, true);
//         $filename = time() . '.' . $image->extension();
//         $image->move($path, $filename);
//         $restaurantType->photo_path = $filename;
//     }

//     $restaurantType->save();

//     return Redirect::to('/dashboard/restaurantType')->with(['type' => 'success', 'message' => 'Restaurant Type updated successfully']);
// }

    // public function edit(string $id)
    // {
    //     $restaurantType = RestoType::findOrFail($id);
    //     return view('BackEnd.RestaurantType.form', compact('restaurantType'));
    // }

    // public function destroy(string $id)
    // {
    //     $restaurantType = RestoType::findOrFail($id);

    //     // Delete the associated photo if it exists
    //     if ($restaurantType->photo_path) {
    //         $path = public_path('images/') . $restaurantType->photo_path;
    //         if (File::exists($path)) {
    //             File::delete($path);
    //         }
    //     }

    //     // Delete the restaurant type
    //     $restaurantType->delete();

    //     return Redirect::to('/dashboard/restaurantType')->with(['type' => 'success', 'message' => 'Restaurant Type deleted successfully']);
    // }
    // public function store(Request $request)
    // {
    //     $filename = "";
    //     $validateUser = Validator::make(
    //         $request->all(),
    //         [
    //             'type' => 'required',
    //             'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //         ]
    //     );
    //     $restaurantType = RestaurantType::create([
    //         'type' => $request->name,
    //     ]);
    //     if ($request->file('photo_path')) {
    //         $image = $request->file('photo_path');
    //         $path = public_path('images/');
    //         !is_dir($path) &&
    //             mkdir($path, 0777, true);
    //         $filename = time() . '.' . $image->extension();
    //         $image->move($path, $filename);
    //         $restaurantType->photo_path = $filename;
    //         $restaurantType->save();
    //     }

    //     return Redirect::to('/dashboard/restaurantType')->with(['type' => 'success', 'message' => 'Restaurant Type Added succesfully']);
    // }

