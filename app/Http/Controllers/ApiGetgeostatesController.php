<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetgeostatesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_geo";        
				$this->permalink   = "getgeostates";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		    	
		    	$data =DB::table('master_geo')->where('country_name','Like',$postdata['country_name'])->select('country_name','subdivision_1_name')
		    		->groupBy('subdivision_1_name')
		    	->get();
		    	$result['data'] = $data;
		        //This method will be execute after run the main process

		    }

		}