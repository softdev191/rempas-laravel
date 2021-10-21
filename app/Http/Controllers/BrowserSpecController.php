<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class BrowserSpecController extends MasterController
{
    //
    public function browser(){
        $company  = $this->company;
        $brands = Car::groupBy('brand')->pluck('brand');
        $category_link = backpack_url('/car/category');
        return view('vendor.custom.specs.browser')->with(compact('company', 'brands', 'category_link'));
    }

    public function category() {
        $make = request()->make;
        $model = request()->model;
        $generation = request()->generation;
        $engine = request()->engine;
        if ($engine) {
            $car = $engines = Car::find($engine);
            $logofile = str_replace(" ", "-", strtolower($car->brand));
            $logofile = asset('images/carlogo/'.$logofile.'.jpg');
            return view('vendor.custom.specs.result', compact('car', 'logofile'));
        } else if ($generation) {
            $engines = Car::where('brand', $make)
                ->where('model', $model)
                ->where('year', $generation)
                ->get();
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return view('vendor.custom.specs.category')
                ->with('mode', 'generation')
                ->with('subitems', $engines)
                ->with('title', $generation)
                ->with('brand', $make)
                ->with('model', $model)
                ->with('logo', $logo);
        } else if ($model) {
            $generations = Car::where('brand', $make)
                ->where('model', $model)
                ->groupBy('year')
                ->pluck('year');
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return view('vendor.custom.specs.category')
                ->with('mode', 'model')
                ->with('subitems', $generations)
                ->with('title', $model)
                ->with('brand', $make)
                ->with('logo', $logo);
        } else if ($make) {
            $models = Car::where('brand', $make)
                ->groupBy('model')
                ->pluck('model');
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return view('vendor.custom.specs.category')
                ->with('mode', 'make')
                ->with('subitems', $models)
                ->with('title', $make)
                ->with('logo', $logo);
        } else {
            $res = array();
            $brands = Car::groupBy('brand')->pluck('brand');
            foreach($brands as $i => $b) {
                array_push($res, [
                    'brand' => $b,
                    'logo' => asset('images/carlogo/'.str_replace(" ", "-", strtolower($b)).'.jpg')
                ]);
            }
            return view('vendor.custom.specs.brand')->with('brands', $res);
        }
    }
}
