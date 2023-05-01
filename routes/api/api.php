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

Route::middleware('auth.uploadImage')->get('/uploadImage', function (Request $request) {
    File::cleanDirectory("temp_images");
    if ($file = request()->file($request->all()[1])){
        $file->move("temp_images");
        return json_encode(true);
    } else {
        return json_encode(false);
    }
})->name("uploadImage");


Route::middleware('auth.quiz')->get('/getQuiz', function (Request $request) {
    if (File::exists("quiz.json")){
        $json = ["key" => "val"];
        return response()->json($json);
    }
    return abort(402,"not exist");
})->name("getQuiz");
