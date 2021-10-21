<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

if(\Request::is('admin') || \Request::is('admin/*')){

	Route::group([
	    'prefix'     => 'admin',
	    'middleware' => ['web', 'auth:admin'],
	    'namespace'  => 'App\Http\Controllers\Admin',
	], function () {

		Route::get('/dashboard', 'DashboardController@dashboard')->name('admin.dashboard');

		Route::get('/edit-account-info', 'AccountController@getAccountInfoForm')->name('account.info');
	    Route::post('/edit-account-info', 'AccountController@postAccountInfoForm');
	    Route::get('/change-password', 'AccountController@getChangePasswordForm')->name('account.password');
	    Route::post('/change-password', 'AccountController@postChangePasswordForm');
		//changes
            Route::post('/set_default_tier', 'TuningCreditCrudController@set_default_tier');
            Route::post('/set_evc_default_tier', 'TuningEVCCreditCrudController@set_default_tier');


		#Customers routes

		CRUD::resource('customer', 'CustomerCrudController')->with(function(){

			Route::get('customer/{user}/switch-account','CustomerCrudController@switchAsCustomer');

			Route::get('customer/{user}/transactions','CustomerCrudController@transactions');

			Route::get('customer/{user}/resend-password-reset-link','CustomerCrudController@resendPasswordResetLink');

            Route::post('customer/transaction','CustomerCrudController@storeTransaction');

            Route::post('customer/transaction-evc','CustomerCrudController@storeTransactionEVC');

			Route::get('customer/transaction/{transaction}/delete','CustomerCrudController@deleteTransaction');

			Route::get('customer/{user}/file-services','CustomerCrudController@fileServices');

			Route::get('customer/file-service/{fileService}/delete','CustomerCrudController@deleteFileService');
        });

        CRUD::resource('staff', 'StaffCrudController')->with(function(){
            Route::get('staff/{user}/switch-account','StaffCrudController@switchAsStaff');
        });


		#File service routes

		CRUD::resource('file-service', 'FileServiceCrudController')->with(function(){
			Route::get('file-service/{fileService}/download-orginal', 'FileServiceCrudController@downloadOrginalFile');
			Route::get('file-service/{fileService}/download-modified', 'FileServiceCrudController@downloadModifiedFile');
			Route::get('file-service/{fileService}/delete-modified', 'FileServiceCrudController@deleteModifiedFile');
			Route::get('file-service/{fileService}/create-ticket', 'FileServiceCrudController@createTicket');
			Route::post('file-service/{fileService}/store-ticket', 'FileServiceCrudController@storeTicket');
			Route::post('upload-file-service-file', 'FileServiceCrudController@uploadFile');
		});

		#Orders routes

		CRUD::resource('order', 'OrderCrudController')->with(function(){
			Route::get('order/{order}/invoice', 'OrderCrudController@invoice');
		});

		#Transaction routes

		CRUD::resource('transaction', 'TransactionCrudController');

		#Email templates routes

		CRUD::resource('email-template', 'EmailTemplateCrudController');

		#Tuning credit routes
		CRUD::resource('tuning-credit', 'TuningCreditCrudController')->with(function(){
			Route::get('tuning-credit/{tuningCreditGroup}/default','TuningCreditCrudController@markDefault');
			Route::get('tuning-credit/credit-tire','TuningCreditTireController@creditTire');
			Route::post('tuning-credit/credit-tire','TuningCreditTireController@updateCreditTire');
			Route::get('tuning-credit/credit-tire/{tuningCreditTire}/delete','TuningCreditTireController@deleteCreditTire');
        });

        #Tuning EVC credit routes
		CRUD::resource('tuning-evc-credit', 'TuningEVCCreditCrudController')->with(function(){
			Route::get('tuning-evc-credit/{tuningCreditGroup}/default','TuningEVCCreditCrudController@markDefault');
			Route::get('tuning-evc-credit/credit-tire','TuningEVCCreditTireController@creditTire');
			Route::post('tuning-evc-credit/credit-tire','TuningEVCCreditTireController@updateCreditTire');
			Route::get('tuning-evc-credit/credit-tire/{tuningCreditTire}/delete','TuningEVCCreditTireController@deleteCreditTire');
		});

		#Tuning types routes

		CRUD::resource('tuning-type', 'TuningTypeCrudController')->with(function(){
			Route::get('tuning-type/{tuningType}/up', 'TuningTypeCrudController@upGradeOrder');
			Route::get('tuning-type/{tuningType}/down', 'TuningTypeCrudController@downGradeOrder');
		});

		#Tuning type options routes

		CRUD::resource('tuning-type/{tuningType}/options', 'TuningTypeOptionCrudController')->with(function(){
			Route::get('tuning-type/{tuningType}/options/{tuningTypeOption}/up', 'TuningTypeOptionCrudController@upGradeOrder');
			Route::get('tuning-type/{tuningType}/options/{tuningTypeOption}/down', 'TuningTypeOptionCrudController@downGradeOrder');
		});

		Route::group(['middleware' => 'has.privilege:admin'], function(){
			#Packages routes

            CRUD::resource('package', 'PackageCrudController');
            // CRUD::resource('zpackage', 'ZPackageCrudController');
		});

		#Companies routes
		Route::group(['middleware' => 'has.privilege:admin'], function(){
			CRUD::resource('company', 'CompanyCrudController')->with(function(){
				Route::get('company/{company}/resend-password-reset-link','CompanyCrudController@resendPasswordResetLink');
                Route::get('company/{company}/company-trial-subscription','CompanyCrudController@trialSubscriptions');
                Route::post('company/{company}/company-trial-subscription','CompanyCrudController@storeTrialSubscription');
				//change
					Route::get('company/{company}/company-account-type','CompanyCrudController@companyAccountType');
					Route::get('company/{company}/account-activate','CompanyCrudController@accountActivate');
			});
		});

		#Company setting routes

		Route::get('company-setting', 'CompanySettingController@showSetting')->name('company.setting');
		Route::post('update-company-setting', 'CompanySettingController@update')->name('update.company.setting');

		#Subscription routes

		CRUD::resource('subscription', 'SubscriptionCrudController')->with(function(){

			Route::get('subscription/packages', 'SubscriptionCrudController@showSubscriptionPackages')->name('subscription.packages');
	    	Route::get('subscription/subscribe-package/{package}', 'SubscriptionCrudController@subscribeSubscription')->name('subscribe.paypal');
	    	Route::get('subscription/execute', 'SubscriptionCrudController@executeSubscription')->name('paypal.subscription.execute');
	    	Route::get('subscription/immediate/{subscription}', 'SubscriptionCrudController@immediateCancelSubscription');

			Route::get('subscription/cancel/{subscription}', 'SubscriptionCrudController@cancelSubscription');
        });

        #ZSubscription routes

		// CRUD::resource('zsubscription', 'ZSubscriptionCrudController')->with(function(){

		// 	Route::get('zsubscription/packages', 'ZSubscriptionCrudController@showSubscriptionPackages')->name('subscription.packages');
	    // 	Route::get('zsubscription/subscribe-package/{package}', 'ZSubscriptionCrudController@subscribeSubscription')->name('subscribe.paypal');
	    // 	Route::get('zsubscription/execute', 'ZSubscriptionCrudController@executeSubscription')->name('paypal.subscription.execute');
	    // 	Route::get('zsubscription/immediate/{subscription}', 'ZSubscriptionCrudController@immediateCancelSubscription');

		// 	Route::get('zsubscription/cancel/{subscription}', 'ZSubscriptionCrudController@cancelSubscription');
		// });

		#Subscription payment routes
        CRUD::resource('subscription-payment', 'SubscriptionPaymentCrudController')->with(function(){
            Route::get('subscription-payment/{id}/invoice', 'SubscriptionPaymentCrudController@invoice');
        });
        #ZSubscription payment routes
        // CRUD::resource('zsubscription-payment', 'ZSubscriptionPaymentCrudController')->with(function(){
        //     Route::get('zsubscription-payment/{id}/invoice', 'ZSubscriptionPaymentCrudController@invoice');
        // });

        CRUD::resource('tickets', 'Admin\TicketsCrudController')->with(function(){
            Route::get('tickets/{ticket}/download-file', 'TicketsCrudController@downloadFile');
            Route::get('tickets/{ticket}/mark-close', 'TicketsCrudController@markClose');
		    Route::post('upload-ticket-file', 'TicketsCrudController@uploadFile');
		});

		//CRUD::resource('subscription-payment', 'SubscriptionPaymentCrudController');

		Route::get('backup', 'BackupController@index');
	    Route::put('backup/create', 'BackupController@create');
	    Route::get('backup/download/{file_name?}', 'BackupController@download');
	    Route::delete('backup/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');

            #Tickets routes
            CRUD::resource('tickets', 'TicketsCrudController');

            Route::group(['prefix' => config('backpack.base.route_prefix', 'admin'), 'middleware' => ['web', 'auth']], function () {
                CRUD::resource('tickets', 'Admin\TicketsCrudController')->with(function(){
                    Route::get('tickets/{ticket}/download-file', 'TicketsCrudController@downloadFile');
                    Route::get('tickets/{ticket}/mark-close', 'TicketsCrudController@markClose');
		    Route::post('upload-ticket-file', 'TicketsCrudController@uploadFile');
		});
            });

		#Slider Manager routes
        CRUD::resource('slidermanager', 'SliderManagerCrudController');
        #Browse Car Tuning Specs
        Route::get('/car/browser', '\App\Http\Controllers\BrowserSpecController@browser')->name('car.browser');
        Route::get('/car/category', '\App\Http\Controllers\BrowserSpecController@category')->name('car.browser.category');
	});

}elseif(\Request::is('customer') || \Request::is('customer/*')){

	Route::group([
	    'prefix'     => 'customer',
	    'middleware' => ['web', 'auth:customer'],
	    'namespace'  => 'App\Http\Controllers\Customer',
	], function () {

		Route::get('/dashboard', 'DashboardController@dashboard')->name('customer.dashboard');
        Route::post('add-rating', 'DashboardController@addRating');
        Route::post('set-reseller', 'DashboardController@setReseller');
		Route::get('edit-account-info', 'AccountController@getAccountInfoForm')->name('account.info');
	    Route::post('edit-account-info', 'AccountController@postAccountInfoForm');
	    Route::get('change-password', 'AccountController@getChangePasswordForm')->name('account.password');
	    Route::post('change-password', 'AccountController@postChangePasswordForm');

	    #File service routes

		CRUD::resource('file-service', 'FileServiceCrudController')->with(function(){
			Route::get('file-service/{fileService}/download-orginal', 'FileServiceCrudController@downloadOrginalFile');
			Route::get('file-service/{fileService}/download-modified', 'FileServiceCrudController@downloadModifiedFile');
                        Route::get('file-service/{fileService}/create-ticket', 'FileServiceCrudController@createTicket');
			Route::post('file-service/{fileService}/store-ticket', 'FileServiceCrudController@storeTicket');
			Route::post('upload-file-service-file', 'FileServiceCrudController@uploadFile');
		});

		#Buy credit routes
		Route::group(['prefix'=>'buy-credits'], function(){
			Route::get('/', 'BuyCreditController@index')->name('buy.credit');
			// route for post request
			Route::post('paypal', 'PaymentController@postPaymentWithpaypal')->name('pay.with.paypal');
			// route for check status responce
            Route::get('paypal', 'PaymentController@getPaymentStatus')->name('paypal.payment.status');
            // route for confirm stripe payment
            Route::post('stripe', 'PaymentController@stripePost')->name('pay.with.stripe');
		});

		#Orders routes

		CRUD::resource('order', 'OrderCrudController')->with(function(){
			Route::get('order/{order}/invoice', 'OrderCrudController@invoice');
		});

		#Transaction routes

		CRUD::resource('transaction', 'TransactionCrudController');
                #Tickets routes
                CRUD::resource('tickets', 'TicketsCrudController')->with(function(){
                    Route::get('tickets/{ticket}/download-file', 'TicketsCrudController@downloadFile');
                    Route::get('tickets/{ticket}/mark-close', 'TicketsCrudController@markClose');
		    Route::post('upload-ticket-file', 'TicketsCrudController@uploadFile');

        });
        Route::get('/car/browser', '\App\Http\Controllers\BrowserSpecController@browser')->name('car.browser');
        Route::get('/car/category', '\App\Http\Controllers\BrowserSpecController@category')->name('car.browser.category');
	});

}else{

}

