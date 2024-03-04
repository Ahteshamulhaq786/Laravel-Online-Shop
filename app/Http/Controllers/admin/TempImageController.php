<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;

class TempImageController extends Controller
{
    public function create(Request $request){

        if($request->hasFile('image')){
            $file=$request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $tempFileName = hexdec(uniqid()).rand(11111,99999).time().".".$ext;
            $file->move(public_path('uploads/temp'),$tempFileName);

            $tempImage = new TempImage();
            $tempImage->name = $tempFileName;
            $tempImage->save();

            return response()->json([
                'status'=>true,
                'image_id'=>$tempImage->id,
                'message'=>'Image Uploaded'
            ]);
        }

    }
}
