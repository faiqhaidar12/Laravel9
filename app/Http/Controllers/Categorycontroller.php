<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class Categorycontroller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function AllCate(){
        
            //Query builder join table
        // $categories = DB::table('categories')
        // ->join('users','categories.user_id','users.id')
        // ->select('categories.*','users.name')
        // ->latest()->paginate(5);
        
            //Eloquent ORM
        $categories = Category::latest()->paginate(5);
        $trashCate  = Category::onlyTrashed()->latest()->paginate(2);

                // Query Builder
        // $categories = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('categories','trashCate'));
    }

    public function AddCate(Request $request){
        $validatedData = $request->validate([
            'category_name' => 'required|unique:categories|max:255',

        ],
        [
            'category_name.required' => 'Please Input Category Name',
            'category_name.max' => 'Category Less Then 255Chars',

        ]);

            // INSERT DATA //

        Category::insert([
            'category_name' => $request->category_name,
            'user_id'       => Auth::user()->id,
            'created_at'    => Carbon::now()
    ]);


        // INSERT DATA created_at dan update_at langsung ke isi //
    // $category= new Category();
    //         $category->category_name = $request->category_name;
    //         $category->user_id = Auth::user()->id;
    //         $category->save();

        // $data = array();
        // $data['category_name']  = $request->category_name;
        // $data['user_id']        = Auth::user()->id;
        // DB::table('categories')->insert($data);

    return Redirect()->back()->with('success','Category Inserted Successfull');

    }

    public function Edit($id){
                //Eloquent ORM
        // $categories = Category::find($id);
                //Query Builder 
        $categories =DB::table('categories')->where('id',$id)->first();
        return view('admin/category/edit',compact('categories'));

    }

    public function Update(Request $request, $id){
        // $update = Category::find($id)->update([
        //     'category_name' => $request->category_name,
        //     'user_id'       => Auth::user()->id
        // ]);

                //Query Builder
        $data = array();
        $data['category_name']  = $request->category_name;
        $data['user_id']        = Auth::user()->id;
        DB::table('categories')->where('id',$id)->update($data);
        return Redirect()->route('all.category')->with('success','Category Update Successfull');
    }

    public function SoftDelete($id){
        $delete = Category::find($id)->delete();
        return Redirect()->back()->with('success','Category Soft Delete Successfull');
    }

    public function Restore($id){
        $delete = Category::withTrashed()->find($id)->restore();
        return Redirect()->back()->with('success','Category Restore Successfull');
    }

    public function Pdelete($id){
        $delete = Category::onlyTrashed()->find($id)->forceDelete();
        return Redirect()->back()->with('success','Category Permanently Delete Successfull');
    }

}
