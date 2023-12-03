<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePageContent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class HomePageController extends Controller
{
    public function index()
    {
        $container1 = HomePageContent::where('container_number', 1)->first();
        $container2 = HomePageContent::where('container_number', 2)->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'container1' => $container1,
                'container2' => $container2,
            ],
        ]);
    }

    public function store(Request $request, $containerNumber)
    {
        $validator = Validator::make($request->all(), [
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
    
        $homepageContent = new HomePageContent();
        $homepageContent->container_number = $containerNumber;
    
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imagePath = 'homepage_images/' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imagePath);
            $homepageContent->image_path = $imagePath;
        }
    
        $homepageContent->title = $request->input('title');
        $homepageContent->text = $request->input('text');
        $homepageContent->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Homepage content for container ' . $containerNumber . ' created successfully',
            'data' => $homepageContent,
        ]);
    }
    
    
    public function update(Request $request, $containerNumber)
    {
        // Find the corresponding container based on container number
        $container = HomePageContent::where('container_number', $containerNumber)->firstOrFail();
    
        $validator = Validator::make($request->all(), [
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
    
        if ($request->hasFile('image_path')) {
            // Delete the old image
            Storage::disk('public')->delete($container->image_path);
    
            // Store the new image
            $imagePath = $request->file('image_path')->store('homepage_images', 'public');
            $container->image_path = $imagePath;
        }
    
        $container->title = $request->input('title');
        $container->text = $request->input('text');
        $container->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Container ' . $containerNumber . ' updated successfully',
            'data' => $container,
        ]);
    }
}
