<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetgeocitiesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_geo";        
				$this->permalink   = "getgeocities";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        $data =DB::table('master_geo')->where('country_name','Like',$postdata['country_name'])->where('subdivision_1_name','Like',$postdata['subdivision_1_name'])->select('country_name','subdivision_1_name','city_name')
		    		->groupBy('city_name')
		    	->get();
		    	$result['data'] = $data;
		    }

		}