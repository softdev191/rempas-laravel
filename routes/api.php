<?php

use Illuminate\Http\Request;

use App\Models\Car;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/carquery', function(Request $request) {
    if ($request->model == '') {
        $models = Car::where('brand', $request->make)
            ->groupBy('model')
            ->pluck('model');
        return $models;
    }
    if ($request->generation == '') {
        $generations = Car::where('brand', $request->make)
            ->where('model', $request->model)
            ->groupBy('year')
            ->pluck('year');
        return $generations;
    }
    if ($request->engine == '') {
        $engines = Car::where('brand', $request->make)
            ->where('model', $request->model)
            ->where('year', $request->generation)
            ->get();
        return $engines;
    }
})->name('api.car.query');

Route::post('/carid', function(Request $request) {
    $id = Car::where('brand', $request->make)
        ->where('model', $request->model)
        ->where('year', $request->generation)
        ->where('engine_type', $request->engine)
        ->first()->id;
    return $id;
})->name('api.car.id');
