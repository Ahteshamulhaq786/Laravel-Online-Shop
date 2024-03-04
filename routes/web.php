<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'admin'],function(){

    Route::group(['middleware'=>'admin.auth'],function(){

        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');
        Route::post('/upload_temp_image',[TempImageController::class,'create'])->name('admin.upload_temp_image');

        Route::resource('/categories',CategoryController::class)->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'edit'=> 'admin.categories.edit',
            'update'=> 'admin.categories.update',
            'destroy'=> 'admin.categories.destroy'
        ]);

    });

    Route::group(['middleware'=>'admin.guest'],function(){

        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

    });

});

Route::get('getSlug',function(Request $request){
    $slug = '';
    if(!empty($request->keyword)){
        $slug = Str::slug($request->keyword);
    }
    return response()->json([
        'status'=>true,
        'slug'=>$slug
    ]);
})->name('getSlug');

