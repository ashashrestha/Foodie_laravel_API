<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BestOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class BestOfferController extends Controller
{
    public function index()
    {
        $bestOffers = BestOffer::all();
        return response()->json(['data' => $bestOffers]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'm_name' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('best_offer_images', 'public');
        }

        // Create a new best offer
        $bestOffer = new BestOffer;
        $bestOffer->image_path = $imagePath;
        $bestOffer->m_name = $request->input('m_name');
        $bestOffer->name = $request->input('name');
        $bestOffer->price = $request->input('price');
        $bestOffer->save();

        return response()->json(['message' => 'Best offer created successfully', 'data' => $bestOffer]);
    }

    public function update(Request $request, $id)
    {
        // Find the best offer by its ID
        $bestOffer = BestOffer::find($id);

        if (!$bestOffer) {
            return response()->json(['error' => 'Best offer not found'], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'm_name' => 'string',
            'name' => 'string',
            'price' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Handle image upload for update
        if ($request->hasFile('image_path')) {
            // Delete the old image
            Storage::disk('public')->delete($bestOffer->image_path);

            // Store the new image
            $imagePath = $request->file('image_path')->store('best_offer_images', 'public');
            $bestOffer->image_path = $imagePath;
        }

        // Update the best offer's properties
        if ($request->has('m_name')) {
            $bestOffer->m_name = $request->input('m_name');
        }
        if ($request->has('name')) {
            $bestOffer->name = $request->input('name');
        }
        if ($request->has('price')) {
            $bestOffer->price = $request->input('price');
        }

        $bestOffer->save();

        return response()->json(['message' => 'Best offer updated successfully', 'data' => $bestOffer]);
    }
}
