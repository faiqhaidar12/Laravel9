<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Carbon;


class BrandController extends Controller
{
    public function AllBrand(){

        $brands = Brand::latest()->paginate(5);
        return view('admin.brand.index',compact('brands'));
    }

    public function StoreBrand(Request $request){
        $validatedData = $request->validate([
            'brand_name' => 'required|unique:brands|min:4',
            'brand_image' => 'required|mimes:jpg,jpeg,png|size:2000',

        ],
        [
            'brand_name.required' => 'Please Input Brand Name',
            'brand_image.min' => 'brands Longer Then 4 Characters',

        ]);
                //upload image
        $brand_image    = $request->file('brand_image');

        $name_gen       = hexdec(uniqid());
        $image_ext      = strtolower($brand_image->getClientOriginalExtension());
        $image_name     = $name_gen.'.'.$image_ext;
        $upload_location= 'image/brand/';
        $last_img       = $upload_location.$image_name;
        $brand_image->move($upload_location,$image_name);

        Brand::insert([
            'brand_name'    => $request->brand_name,
            'brand_image'   => $last_img,
            'created_at'    => Carbon::now()
        ]);

        return Redirect()->back()->with('success','Brand Inserted Successfully');
    }

    public function Edit($id){
        $brands = Brand::find($id);
        return view('admin.brand.edit',compact('brands'));
    }

    public function Update(Request $request, $id){
        $validatedData = $request->validate([
            'brand_name' => 'required|min:4',
            'brand_image' => 'mimes:jpg,jpeg,png',
        ],
        [
            'brand_name.required' => 'Please Input Brand Name',
            'brand_image.min' => 'brands Longer Then 4 Characters',
        ]);
        $old_image      = $request->old_image;
        $brand_image    = $request->file('brand_image');
        if($brand_image){
        $name_gen       = hexdec(uniqid());
        $image_ext      = strtolower($brand_image->getClientOriginalExtension());
        $image_name     = $name_gen.'.'.$image_ext;
        $upload_location= 'image/brand/';
        $last_img       = $upload_location.$image_name;
        $brand_image->move($upload_location,$image_name);

        unlink($old_image);
        Brand::find($id)->update([
            'brand_name'    => $request->brand_name,
            'brand_image'   => $last_img,
            'created_at'    => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'info'
        );         
        return Redirect()->back()->with($notification);
        }else{
            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'created_at' => Carbon::now()
            ]);
            $notification = array(
                'message' => 'Brand Updated Successfully',
                'alert-type' => 'warning'
            );    
            return Redirect()->back()->with($notification);
        } 
    }
}
