<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Categorycontroller;
use App\Http\Controllers\Kategorycontroller;
use App\Http\Controllers\BrandController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    echo" this is Home page";
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact',[ContactController::class, 'index'])->name('con');

// Category Controller
Route::get('/category/all',[Categorycontroller::class, 'AllCate'])->name('all.category');
Route::post('/category/add',[Categorycontroller::class, 'AddCate'])->name('store.category');

Route::get('/category/edit/{id}',[Categorycontroller::class, 'Edit']);
Route::post('/category/update/{id}',[Categorycontroller::class, 'Update']);

Route::get('/softdelete/category/{id}',[Categorycontroller::class, 'SoftDelete']);
Route::get('category/restore/{id}',[Categorycontroller::class, 'Restore']);
Route::get('pdelete/category/{id}',[Categorycontroller::class, 'Pdelete']);


//For brand route
Route::get('/brand/all',[BrandController::class, 'AllBrand'])->name('all.brand');
Route::post('/brand/add',[BrandController::class, 'StoreBrand'])->name('store.brand');
Route::get('/brand/edit/{id}',[BrandController::class, 'Edit']);
Route::post('/brand/update/{id}',[BrandController::class, 'Update']);
Route::get('brand/delete/{id}',[BrandController::class, 'Delete']);


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {

    // mengambil semua data User
    // $users = User::all();

    // mengambil data dari tabel users
    $users = DB::table('users')->get();
    return view('dashboard',compact ('users'));
})->name('dashboard');
