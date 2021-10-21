<?php

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
//'domain'=> request()->getHttpHost()
Auth::routes();
//dd(request()->getHttpHost());
Route::group(['domain' => 'myremaps.com'], function () {
    Route::get('/', '\App\Http\Controllers\Frontend\HomeController@index')->name('home');
	 Route::get('register-as-a-remapping-file-supplier', '\App\Http\Controllers\Frontend\HomeController@innerhome')->name('innerhome');

	Route::get('compare-prices', '\App\Http\Controllers\Frontend\CompanyController@companies')->name('companies');


	//Route::resource('register-account', '\App\Http\Controllers\Frontend\CompanyController');
	//Route::get('/register-account/{packageID}', '\App\Http\Controllers\Frontend\CompanyController@create')->name('register-account.create');
	Route::get('/register-account', '\App\Http\Controllers\Frontend\CompanyController@create')->name('register-account.create');

	Route::get('thankyou', '\App\Http\Controllers\Frontend\CompanyController@thankyou')->name('thankyou');

	// route for post request
	Route::post('paypal', '\App\Http\Controllers\Frontend\CompanyController@postPaymentWithpaypal')->name('pay.with.paypal.main');
	// route for check status responce
	Route::get('paypal', '\App\Http\Controllers\Frontend\CompanyController@getPaymentStatus')->name('paypal.payment.status.main');

	Route::get('paypal/subscribe/execute', '\App\Http\Controllers\Frontend\CompanyController@executeSubscription')->name('paypal.execute.subscription');
	Route::get('paypal/subscribe/{package}', '\App\Http\Controllers\Frontend\CompanyController@subscribeSubscription')->name('paypal.subscribe.subscription');


});
//changes
	Route::get('/user-register/', '\App\Http\Controllers\UserRegisterController@register')->name('users_registers');
    Route::post('/user-register/', '\App\Http\Controllers\UserRegisterController@create')->name('users_registers');
    Route::get('/browser', '\App\Http\Controllers\UserRegisterController@browser')->name('browser');
    Route::get('/browser/result', '\App\Http\Controllers\UserRegisterController@browserResult')->name('browser.result');
    Route::get('/browser/category', '\App\Http\Controllers\UserRegisterController@browserCategory')->name('browser.category');


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/admin', function () {
    return redirect(url('admin/login'));
});

Route::get('/customer', function () {
    return redirect(url('customer/dashboard'));
});


Route::post('/paypal/webhooks', 'PaypalWebhookController@index');
// Route::post('/paypal/zwebhooks', 'ZPaypalWebhookController@index');


Route::group(['middleware' => 'web', 'prefix'=>'admin'], function(){

	Route::get('login', '\App\Http\Controllers\Auth\Admin\LoginController@showLoginForm')->name('admin.auth.show.login');
	Route::post('login', '\App\Http\Controllers\Auth\Admin\LoginController@login')->name('admin.auth.login');
	Route::post('logout', '\App\Http\Controllers\Auth\Admin\LoginController@logout')->name('admin.auth.logout');
	Route::get('password/reset', '\App\Http\Controllers\Auth\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.auth.show.password.reset');
	Route::post('password/reset', '\App\Http\Controllers\Auth\Admin\ResetPasswordController@reset')->name('admin.auth.password.reset');
	Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\Admin\ResetPasswordController@showResetForm')->name('admin.auth.password.reset.form');
	Route::post('password/email', '\App\Http\Controllers\Auth\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.auth.password.email');

	Route::get('company/{company}/switch-account','\App\Http\Controllers\Auth\Admin\LoginController@switchAsCompany');
});

Route::get('tuning-type-options/{tuning_type?}', function(\App\Models\TuningType $tuningType){
	$tuningTypeOptions = $tuningType->tuningTypeOptions()->orderBy('order_as', 'ASC')->get();
	if($tuningTypeOptions->count() > 0){
		return view('vendor.custom.common.tuning_type_options', compact('tuningTypeOptions'))->render();
	}else{
		return "";
	}
})->name('get.tuning.type.options');

Route::get('/fource-logout', function(){
	\Auth::guard('admin')->logout();
	\Auth::guard('customer')->logout();
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:clear');
	//$exitCode = Artisan::call('up');
    //$exitCode = exec('composer dump-autoload');
});

Route::get('lang/{locale}', 'LocalizationController@index');





