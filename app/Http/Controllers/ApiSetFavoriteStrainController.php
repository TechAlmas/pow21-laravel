<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSetFavoriteStrainController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_favorite_strains";        
				$this->permalink   = "set_favorite_strain";    
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

		    	//print_r($postdata['city']);exit;
		        //This method will be execute after run the main process
		        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();
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

		        if(isset($user)){
		        	$user_id = $user->id;
		        }else{
		        	$user_id = DB::table("cms_users")->insertGetId(array("name"=>$postdata["name"], "email"=>$postdata["email"],"id_cms_privileges"=>3,"status"=>"Active"));		        	
		        } 

		        DB::table("master_users_paid_for")->where("user_id",$postdata["user_id"])->update(array("user_id"=>$user_id));
		        DB::table("cms_logs")->where("id_cms_users",$postdata["user_id"])->update(array("id_cms_users"=>$user_id));

		        $result["user_id"] = $user_id;
		        if($state_id == 0)
		        {
		        	$alert = DB::table("master_users_favorite_strains")->select("id")->where("city",$city_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();
		        }
		        else
		        {
		        	$alert = DB::table("master_users_favorite_strains")->select("id")->where("state",$state_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();
		        }

		        

		       // print_r($alert);exit;

		        if(isset($alert) && isset($alert->id)){
		        	//echo $alert->id; exit;
		        	 DB::table("master_users_favorite_strains")->where("id",$alert->id)->update(array("status" => 1));
		        	 $result["exists"] = true;
		        	 $result["id"] = $alert->id;
		        }else{
		        	$new_data = array();
		        	$new_data["user_id"] = $user_id;
		        	$new_data["city"] = $city_id;
		        	$new_data["state"] = $state_id;
		        	$new_data["strain"] = $postdata["strain"];
		        	$new_data["status"] = 1;

		        	$result["exists"] = false;

		        	//print_r($new_data); exit;

		        	$id1 = DB::table("master_users_favorite_strains")->insertGetId($new_data);

		        	$result["id"] = $id1;
		        }

		    }

		}