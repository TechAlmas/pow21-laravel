<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiPricealertdetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_price_alerts_detail";        
				$this->permalink   = "pricealertdetail";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();

		        if(isset($user)){
		        	$user_id = $user->id;
		        }else{
		        	$user_id = DB::table("cms_users")->insertGetId(array("name"=>$postdata["name"], "email"=>$postdata["email"],"id_cms_privileges"=>3,"status"=>"Active"));		        	
		        } 

		        $alert = DB::table("master_users_price_alerts_detail")->select("id")->where("city",$postdata["city"])->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();

		        if(isset($alert)){
		        	 DB::table("master_users_price_alerts_detail")->where("id",$alert->id)->update(array("status" => 1));
		        	 unset($postdata);
		        	 $result = array();
			        $result["api_status"] = 1;
			        $result["api_message"] = "success";
			        $result["api_http"] = "200";

			        echo json_encode($result);exit;
		        }else{

		        	unset($postdata["name"]);
		        	unset($postdata["email"]); 
		        	$postdata["user_id"] = $user_id;

		        	//echo "<pre>"; print_r($postdata);exit;
		        }

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}