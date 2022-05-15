<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiPricealertstargetController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_price_alerts_detail";        
				$this->permalink   = "pricealertstarget";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        $pricealerts = $result['data'];
		        foreach ($pricealerts as $key => $value) {
		        	# code...
		        	$master_strains = DB::table('master_strains')
		                      ->where('id', $value->strain)
		                      ->first();
					$result['data'][$key]->strain_name = $master_strains->name;

					$master_cities = DB::table('master_cities')
		                      ->where('id', $value->city)
		                      ->first();
					$result['data'][$key]->city_name = $master_cities->city;
		        }

		    }

		}