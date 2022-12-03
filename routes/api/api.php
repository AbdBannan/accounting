<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("/uploadImage",function (Request $request){

//    dd($_SERVER["SERVER_ADDR"]);
//    File::ensureDirectoryExists("image1");
//    File::move("temp_images/192.jpg","images1/aa.jpg");
//    dd(glob(public_path("temp_images/20.*")));
//    File::move("temp_images/invoice_345.jpg","images/productInvoices/haha.jpg");

    File::cleanDirectory("temp_images");
    if ($file = request()->file($request->all()[1])){
        $file->move("temp_images");
        return json_encode(true);
    } else {
        return json_encode(false);
    }
})->name("uploadImage");
