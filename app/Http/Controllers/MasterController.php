<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use View, Config;

class MasterController extends CrudController{

    protected $user;
    protected $company;
	protected $tickets;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next, $guard = null) {
            $this->user = Auth::guard($guard)->user();
			$this->tickets = $this->user->unread_tickets;
            if($this->user){
                $this->company = $this->user->company;
				
				Config::set('mail.driver', $this->company->mail_driver);
                Config::set('mail.host', $this->company->mail_host);
                Config::set('mail.port', $this->company->mail_port);
				Config::set('mail.encryption', $this->company->mail_encryption);
				//Config::set('mail.encryption', '');
                Config::set('mail.username', $this->company->mail_username);
                Config::set('mail.password', $this->company->mail_password);
                Config::set('mail.from.address',$this->company->mail_username );
                Config::set('mail.from.name', $this->company->name);
                Config::set('app.name', $this->company->name);
                Config::set('backpack.base.project_name', $this->company->name);
				
				/*
				Config::set('mail.driver', $this->company->mail_driver);
                Config::set('mail.host', 'mail.24livehost.com');
                Config::set('mail.port', 25);
				Config::set('mail.encryption', $this->company->mail_encryption);
                Config::set('mail.username', 'wwwsmtp@24livehost.com');
                Config::set('mail.password', 'dsmtp909#');
                Config::set('mail.from.address','no-reply@advancedtuning.com' );
                Config::set('mail.from.name', 'advancedtuning');
                Config::set('app.name', $this->company->name);
                Config::set('backpack.base.project_name', $this->company->name);
				*/
				
                if($this->company->paypal_currency_code != 'GBP') {
					Config::set('site.currency_sign', \App\Helpers\Helper::getCurrencySymbol($this->company->paypal_currency_code));				
                } 
                Config::set('paypal.client_id', $this->company->paypal_client_id);
                Config::set('paypal.secret', $this->company->paypal_secret);

                if($this->user->is_admin){
                    Config::set('backpack.base.route_prefix', 'admin');
                    Config::set('backpack.base.middleware_key', 'admin');
                }else{							
                    Config::set('backpack.base.route_prefix', 'customer');
                    Config::set('backpack.base.middleware_key', 'customer');
                }
                view::share(['user'=>$this->user, 'company'=>$this->company, 'tickets_count'=>$this->tickets]);

                $this->user->last_login = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                $this->user->save();
            }
            return $next($request);
        });
    }
}
