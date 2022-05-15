<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSetPaidForController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_paid_for";        
				$this->permalink   = "set_paid_for";    
				$this->method_type = "post";    
				$this->user_token = time();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		        if($postdata['user_id']==0)
		        {
		        	$postdata['user_id'] = $this->user_token;
		        }
		        if($postdata["city"] == '0')
		        {
		        	$postdata['state'] = $postdata['state'];
		        	$postdata['city'] = 0;
		        }
		        elseif(strpos($postdata["city"], '0_') !== false)
		        {
		        	$postdata['state'] = str_replace("0_","",$postdata["city"]);
		        	$postdata['city'] = 0;
		        }
		        else
		        {
		        	$postdata['state'] = 0;
		        	$postdata['city'] = $postdata["city"];
		        }


		         //unset($postdata["user_id"]);


		    	/*$postdata["user_id"] = 0;

		        if(isset($postdata['email']) && !empty($postdata["email"])){
		        	$user = DB::table("cms_users")->where("email",$postdata["email"])->first();
		        	if(isset($user) && isset($user->id)){
		        		$postdata["user_id"] =$user->id;
		        	}
		        }
		        unset($postdata["email"]);*/
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		    	$result["user_id"] = $postdata['user_id'];
		    }

		}