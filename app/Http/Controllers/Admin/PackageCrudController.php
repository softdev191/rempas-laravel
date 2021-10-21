<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\PackageRequest as StoreRequest;
use App\Http\Requests\PackageRequest as UpdateRequest;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Common\PayPalModel;
use PayPal\Api\PatchRequest;
use PayPal\Rest\ApiContext;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\Patch;
use PayPal\Api\Plan;

/**
 * Class PackageCrudController
 * @param App\Http\Controllers\Admin
 * @return CrudPanel $crud
 */
class PackageCrudController extends MasterController
{

    /**
     * Class Setup
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Package');
        $this->crud->setRoute('admin/package');
        $this->crud->setEntityNameStrings('package', 'packages');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
        ]);

        $this->crud->addColumn([
            'name' => 'billing_interval',
            'label' => 'Billing Interval',
        ]);

        $this->crud->addColumn([
            'name' => 'amount_with_current_sign',
            'label' => 'Amount'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'name',
            'label' => "Name",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Name'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'billing_interval',
            'label' => "Billing Interval",
            'type' => 'select_from_array',
            'options' => config('site.package_billing_interval'),
            'allows_null' => true,
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank1',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'amount',
            'label' => "Amount",
            'type' => 'number',
            'attributes'=>['placeholder'=>'Amount'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank2',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => "Description",
            'type' => 'wysiwyg',
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store resource
     * @param App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request){
        try {
            $product = '1month Rolling £35';
            if ($request->billing_interval == 'Day') {
                $product = '1month Rolling £35';
            } else if ($request->billing_interval == 'Month') {
                $product = 'Sub-49';
            } else if ($request->billing_interval == 'Year') {
                $product = 'PROD-2XS463586A957535Y';
            }
            $accessToken = $this->getAccessToken();

            $data = [
                'product_id' => $product,
                'name' => $request->name,
                'description' => $request->name,
                'status' => 'ACTIVE',
                'billing_cycles' => [
                    [
                        'frequency' => [
                            'interval_unit' => $request->billing_interval,
                            'interval_count' => 1
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $request->amount,
                                'currency_code' => $this->company->paypal_currency_code
                            ]
                        ]
                    ]
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee' => [
                        'value' => $request->amount,
                        'currency_code' => $this->company->paypal_currency_code
                    ],
                    'setup_fee_failure_action' => 'CONTINUE',
                    'payment_failure_threshold' => 3
                ]
            ];

            $url = "https://api.paypal.com/v1/billing/plans";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);;
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                'Accept: application/json',
                'Authorization: '."Bearer ". $accessToken,
                'Prefer: return=representation',
                'Content-Type: application/json',
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $request->request->add(['pay_plan_id'=> json_decode($resp)->id]);
            $redirect_location = parent::storeCrud($request);
            return $redirect_location;
        }catch (\Exception $ex) {
            \Alert::error($ex->getMessage())->flash();
        }

        return redirect(url('admin/package'));
    }

    /**
     * Update resource
     * @param App\Http\Request\UpdateRequest $request
     * @return $response
     */
    public function update(UpdateRequest $request){
        try {
            $entry = $this->crud->getEntry($request->id);
            $accessToken = $this->getAccessToken();

            $url = "https://api.paypal.com/v1/billing/plans/P-1M592657LP7904156L6LKNQA/update-pricing-schemes";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);;
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                'Accept: application/json',
                'Authorization: '."Bearer ". $accessToken,
                'Prefer: return=representation',
                'Content-Type: application/json',
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $data = [
                'pricing_schemes' => [
                    [
                        'billing_cycle_sequence' => 1,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $request->amount,
                                'currency_code' => $this->company->paypal_currency_code
                            ]
                        ]
                    ]
                ]
            ];

            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            // dd($resp);

            $request->name = $entry->name;
            $request->billing_interval = $entry->billing_interval;
            $redirect_location = parent::updateCrud($request);
            return $redirect_location;
        }catch (\Exception $ex) {
            \Alert::error($ex->getMessage())->flash();
        }

        return redirect(url('admin/package'));
    }

    public function getAccessToken() {
        $paypal_conf = config('paypal');
        $apiContext = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $apiContext->setConfig($paypal_conf['settings']);
        $credential = $apiContext->getCredential();

        $ch = curl_init();
        $clientId = $credential->getClientId();
        $secret = $credential->getClientSecret();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result);
        return $json->access_token;
    }
}
