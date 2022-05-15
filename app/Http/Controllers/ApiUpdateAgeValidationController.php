<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUpdateAgeValidationController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "update_age_validation";    
				$this->method_type = "get"; 
				$this->age_validation = 0;   
				$this->user_token =time();    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process		
		        $this->age_validation =    $postdata["age_validation"];     
		        unset($postdata["age_validation"]);

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->select("cms_users.id");

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        //echo "<pre>"; print_r($this->age_validation);exit;

		        $ageData = array("is_age_validation" => $this->age_validation);

		        //echo "<pre>"; print_r($ageData);exit;

		        if(isset($result["api_message"]) && $result["api_message"] == "success"){
		        	DB::table("cms_users")->where("id",$result["id"])->update($ageData);
		        	//unset($result["id"]);
		        }else{
		        	$new_result = array();
		        	$new_result["api_message"] = "success";
		        	$new_result["api_status"] = "1";
		        	$new_result["api_http"] = "200";
		        	
		        	if($postdata["id"] == 0){
		        		$new_result["id"] = $this->user_token;
		        	}else{
		        		$new_result["id"] = $postdata["id"];
		        	}	
		        	$result = $new_result;        	
		        }

		    }

		}