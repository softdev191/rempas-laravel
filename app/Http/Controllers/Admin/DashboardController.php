<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Models\FileService;

class DashboardController extends MasterController
{

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        try{
            $data['title'] = trans('backpack::base.dashboard');
            $user = $this->user;
            FileService::whereHas('user', function($query) use($user){
                $query->where('company_id', $user->company_id);
            })->where('status', 'P')->update(['status' => 'O']);
            $data['orders'] = \App\Models\Order::whereHas('user', function($query) use($user){
                $query->where('company_id', $user->company_id);
            })->orderBy('id', 'DESC')->take(5)->get();
            $data['openFileServices'] = \App\Models\FileService::whereHas('user', function($query) use($user){
                $query->where('company_id', $user->company_id);
            })->where('status', 'O')->count();
            $data['waitingFileServices'] = \App\Models\FileService::whereHas('user', function($query) use($user){
                $query->where('company_id', $user->company_id);
            })->where('status', 'W')->count();
            $data['complatedFileServices'] = \App\Models\FileService::whereHas('user', function($query) use($user){
                $query->where('company_id', $user->company_id);
            })->where('status', 'C')->count();

            return view('backpack::dashboard', $data);
        }catch(\Exception $e){
            return abort(404);
        }
    }
}
