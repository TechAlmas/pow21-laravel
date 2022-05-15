<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiRemovePriceAlertController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_price_alerts";        
				$this->permalink   = "remove_price_alert";    
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

		        if(isset($postdata["id"]) && !empty($postdata["id"]) && isset($postdata["type"]) && !empty($postdata["type"])){

		        	$table_name = "master_users_price_alerts";
		        	if($postdata["type"] == 2){
		        		$table_name = "master_users_price_alerts_detail";
		        	}
		        	
		        	if(DB::table($table_name)->where("id",$postdata["id"])->delete()){
		        		$result["api_status"] = 1;
			        	$result["api_message"] = "success";
			        	$result["api_http"] = "200";
			        	$result["removed"] = "true";
		        	}	        	

		        }
		        //echo "<pre>"; print_r($result);exit;

		    }

		}