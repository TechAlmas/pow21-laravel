<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiPricealertsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_price_alerts";        
				$this->permalink   = "pricealerts";    
				$this->method_type = "get";
				$this->postdata  = array();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		    	$this->postdata = $postdata;
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        if(isset($this->postdata["email"]) && !empty($this->postdata["email"])){		        	
		        	$query->join('cms_users', 'cms_users.id', '=', 'master_users_price_alerts.user_id');
		        	$query->where("cms_users.email",$this->postdata["email"]);
		        }


		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        //echo "<pre>"; print_r($result);exit;

		        /*$pricealerts = $result['data'];
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
		        }*/

		    }

		}