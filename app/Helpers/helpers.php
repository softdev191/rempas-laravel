<?php 
namespace App\Helpers;
use Illuminate\Support\Facades\DB;
 
class Helper {	
		
	public static function convertCurrency($amount, $from, $to) {
		$currencyData = DB::table('currency_conversion')->select('conversion_rate')->where('currency','=',$to)->get()->first();
		
		return $amount * $currencyData->conversion_rate;
		//return round($convertAmount, 3);
	}

	public static function getCurrency() {
		
		$currencySelected = DB::table('companies')
		->join('users', 'companies.id', '=', 'users.company_id')
		->select('companies.paypal_currency_code')
		->where('users.id','=',auth()->user()->id)	
		->get()->all();
		
		return $currencySelected[0]->paypal_currency_code;
	}
	
	
	public static function getCurrencySymbol($currencyCode, $locale = 'en_US')
	{
		$formatter = new \NumberFormatter($locale . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);
		return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
	}
	
}
?>