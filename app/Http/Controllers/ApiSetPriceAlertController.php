<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSetPriceAlertController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_price_alerts";        
				$this->permalink   = "set_price_alert";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {

		    	//print_r($postdata); exit();

		    	//print_r($result);exit;
		        //This method will be execute after run the main process
		        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();

		        if(isset($user)){
		        	$user_id = $user->id;
		        }else{
		        	$user_id = DB::table("cms_users")->insertGetId(array("name"=>$postdata["name"], "email"=>$postdata["email"],"id_cms_privileges"=>3,"status"=>"Active"));		        	
		        } 

		        DB::table("master_users_paid_for")->where("user_id",$postdata["user_id"])->update(array("user_id"=>$user_id));
		        DB::table("cms_logs")->where("id_cms_users",$postdata["user_id"])->update(array("id_cms_users"=>$user_id));

		        $result["user_id"] = $user_id;

		      

		        if($postdata["city"] == '0')
		        {
		        	$state_id = $postdata['state'];
		        	$city_id = 0;
		        }
		        elseif(strpos($postdata["city"], '0_') !== false)
		        {
		        	$state_id = str_replace("0_","",$postdata["city"]);
		        	$city_id = 0;
		        }
		        else
		        {
		        	$state_id = 0;
		        	$city_id = $postdata["city"];
		        }
		        if($state_id == 0)
		        {
		        	$alert = DB::table("master_users_price_alerts")->select("id")->where("city",$city_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();
		        }
		        else
		        {
		        	$alert = DB::table("master_users_price_alerts")->select("id")->where("state",$state_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();
		        	
		        }

		          

		       // print_r($alert);exit;

		        if(isset($alert) && isset($alert->id)){
		        	 DB::table("master_users_price_alerts")->where("id",$alert->id)->update(array("status" => 1));
		        	 $result["exists"] = true;
		        	 $result["id"] = $alert->id;
		        }else{
		        	$new_data = array();
		        	$new_data["user_id"] = $user_id;
		        	//$new_data["city"] = $postdata["city"];
		        	$new_data["strain"] = $postdata["strain"];
		        	$new_data["city"] = $city_id;
		        	$new_data["state"] = $state_id;
		        	
		        	$new_data["status"] = 1;

		        	if($state_id == 0)
		        	{
		        		$current_price = DB::table("master_search_avg_prices_history")
		        						->select("id","master_search_id")
		        						->where("master_search_avg_prices_history.city_id",$city_id)
		        						->where("master_search_avg_prices_history.strain_id",$postdata["strain"])
		        						->where("master_search_avg_prices_history.mass_id",1)
		        						->orderBy("created_at","DESC")
		        						->first();
		        	}
		        	else
		        	{
		        		$current_price = DB::table("master_search_avg_prices_history")
		        						->select("id","master_search_id")
		        						->where("master_search_avg_prices_history.state_id",$state_id)
		        						->where("master_search_avg_prices_history.strain_id",$postdata["strain"])
		        						->where("master_search_avg_prices_history.mass_id",1)
		        						->orderBy("created_at","DESC")
		        						->first();
		        	}
		        	
		        	if(count($current_price) > 0){
		        		$new_data["set_price_id"] = $current_price->master_search_id;	
			        	$new_data["set_price_history_id"] = $current_price->id;	

			        	
			        				
			        	/*$result["exists"] = false;
*/
			        	$id = DB::table("master_users_price_alerts")->insertGetId($new_data);
			        	$result["id"] = $id;
		        	}else{

		        		$state = DB::table("master_cities")->select("state_id")->where("id",$postdata["city"])->first();
		        		
		        		$current_price = DB::table("master_search_avg_prices_history")
			        						->select("id","master_search_id")
			        						->where("master_search_avg_prices_history.state_id",$state->state_id)
			        						->where("master_search_avg_prices_history.strain_id",$postdata["strain"])
			        						->where("master_search_avg_prices_history.mass_id",1)
			        						->orderBy("created_at","DESC")
			        						->first();
			        	//print_r($state->state_id);exit;
			        	/*if(count($current_price) > 0){*/
			        		$new_data["set_price_id"] = $current_price->master_search_id;	
				        	$new_data["set_price_history_id"] = $current_price->id;	
				        	if($new_data["set_price_id"] == "")
			        	{
			        		$new_data["set_price_id"] = 0;
			        	}
			        	if($new_data["set_price_history_id"] == "")
			        	{
			        		$new_data["set_price_history_id"]=0;
			        	}
				        				
				        	/*$result["exists"] = false;
*/
				        	$id = DB::table("master_users_price_alerts")->insertGetId($new_data);
				        	$result["id"] = $id;
				        /*}else{*/
				        	/*$result["exists"] = false;
		        			$result["id"] = 0;*/
				        /*}*/
		        		
		        	}	        	
		        	
		        }

		    }

		}