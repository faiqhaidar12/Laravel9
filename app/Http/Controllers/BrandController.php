<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Multipic;
use Illuminate\Support\Carbon;
use Image;

class BrandController extends Controller
{
    public function AllBrand(){

        $brands = Brand::latest()->paginate(5);
        return view('admin.brand.index',compact('brands'));
    }

    public function StoreBrand(Request $request){
        $validatedData = $request->validate([
            'brand_name' => 'required|unique:brands|min:4',
            'brand_image' => 'required|mimes:jpg,jpeg,png|max:200',

        ],
        [
            'brand_name.required' => 'Please Input Brand Name',
            'brand_image.min' => 'brands Longer Then 4 Characters',

        ]);
                //upload image
        $brand_image    = $request->file('brand_image');

        // $name_gen       = hexdec(uniqid());
        // $image_ext      = strtolower($brand_image->getClientOriginalExtension());
        // $image_name     = $name_gen.'.'.$image_ext;
        // $upload_location= 'image/brand/';
        // $last_img       = $upload_location.$image_name;
        // $brand_image->move($upload_location,$image_name);

        $name_gen       = hexdec(uniqid()).'.'.$brand_image->getClientOriginalExtension();
        Image::make($brand_image)->resize(300,200)->save('image/brand/'.$name_gen);

        $last_image = 'image/brand/'.$name_gen;


        Brand::insert([
            'brand_name'    => $request->brand_name,
            'brand_image'   => $last_image,
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
        ],
        [
            'brand_name.required' => 'Please Input Brand Name',
            'brand_image.min' => 'Brand Longer then 4 Characters', 
        ]);
        $old_image = $request->old_image;
        $brand_image =  $request->file('brand_image');
        if($brand_image){
        
        $name_gen = hexdec(uniqid());
        $img_ext = strtolower($brand_image->getClientOriginalExtension());
        $img_name = $name_gen.'.'.$img_ext;
        $up_location = 'image/brand/';
        $last_img = $up_location.$img_name;
        $brand_image->move($up_location,$img_name);
        unlink($old_image);
        Brand::find($id)->update([
            'brand_name' => $request->brand_name,
            'brand_image' => $last_img,
            'created_at' => Carbon::now()
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

    public function Delete($id){

        $image = Brand::find($id);
        $old_image = $image->brand_image;
        unlink($old_image);


        Brand::find($id)->delete();

        return Redirect()->back()->with('success','Brand Delete Successfully');
    }

    //untuk multi image metode
    public function Multipic(){
        $images = Multipic::all();
        return view('admin.multipic.index',compact('images'));
    }

    public function StoreImg(Request $request){
        $image    = $request->file('image');
        foreach ($image as $multi_image) {
    
        
        $name_gen       = hexdec(uniqid()).'.'.$multi_image->getClientOriginalExtension();
        Image::make($multi_image)->resize(300,200)->save('image/multi/'.$name_gen);

        $last_image = 'image/multi/'.$name_gen;

        Multipic::insert([
            'image'   => $last_image,
            'created_at'    => Carbon::now()
        ]);
        }//endforeach
        return Redirect()->back()->with('success','Image Inserted Successfully');

    }
}
