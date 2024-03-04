<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories=Category::latest();
        if(!empty($request->get('keyword'))){
            $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10)->withQueryString();
        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories',
            'status'=>'required',
        ]);

        if($validator->passes()){

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            if(!empty($request->image_id)){
                $temp_imag=TempImage::find($request->image_id);
                $ext = last(explode('.',$temp_imag->name));
                $new_file_name = $category->id.'.'.$ext;
                $sPath=public_path('uploads/temp').'/'.$temp_imag->name;
                $dPath=public_path('uploads/category').'/'.$new_file_name;
                File::copy($sPath,$dPath);
            }

            $request->session()->flash('success','Category created Successfully');

            return response()->json([
                'status'=>true,
                'message'=>'Category created Successfully',
            ]);
            
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
