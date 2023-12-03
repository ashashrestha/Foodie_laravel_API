<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageSlider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class ImageSliderController extends Controller
{
    public function index()
    {
        $slider = ImageSlider::all();
        return response()->json([
            'status' => true,
            'message' => 'Slider Detail Found Successfully',
            // 'id' => $slider->id,
            // 'title' => $slider->title,
            // 'photo' =>$slider->photo_path
            'data' => $slider
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $filename = "";
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title'=>'required|max:225',
                    'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            // $slide = Dashboard::create([
            //     'title' => $request->title
            // ]);
            $slide = new ImageSlider;
            $slide->title = $request->input('title');
            //while updating photo you need to check there is photo uploaded or not if ulpoaded then you need to delete the last one and update new one
            if ($request->file('photo')) {

                $image = $request->file('photo');
                $path = public_path('images/'); ##create images in public folder
                if ($path . $slide->photo) {  ##deleting old photo
                    @unlink($path . $slide->photo);
                }
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $filename = time() . '.' . $image->extension();
                $image->move($path, $filename);
                $slide->photo = $filename;
                $slide->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'Data save Successfully',
                'id' => $slide->id,
                'title' => $slide->title,
                'photo' =>$slide->photo
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
}
}
