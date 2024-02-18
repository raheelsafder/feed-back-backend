<?php

use App\Http\Controllers\FeedBack\FeedBackController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Helper;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['logs']], function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/get-feedback', [FeedBackController::class, 'getFeedbacks']);
    Route::post('/view-feedback', [FeedBackController::class, 'viewFeedbacks']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/submit-feedback', [FeedBackController::class, 'addFeedback']);

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/add-comment', [FeedBackController::class, 'addComments']);


    });


    Route::any('{any}', function () {
        return Helper::response(request(), 'Page Not Found. Check method type Post/Get or URL', 404);
    })->where('any', '.*');
});




