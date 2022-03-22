<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Kategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;

class Kategorycontroller extends Controller
{
    public function AllCate(){
        return view('admin.category.index');
    }

    public function AddCate(Request $request){
        $validatedData = $request->validate([
            'category_name' => 'required|unique|max:255',

        ],
        [
            'category_name.required' => 'Please Input Category Name',
            'category_name.max' => 'Category Less Then 255Chars',

        ]);

            // insert Data
        // Kategory::insert([
        //     'category_name' => $request->category_name,
        //     'user_id'       => Auth::user()->id,
        //     'created_at'    => Carbon::now()
    // ]);

            $category= new Category();
            $category->category_name = $request->category_name;
            $category->user_id = Auth::user()->id;
            $category->save();
    }
}
