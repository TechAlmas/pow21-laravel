<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiCheckFavStrainController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_favorite_strains";        
				$this->permalink   = "check_fav_strain";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process


		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {
		         //This method will be execute after run the main process
		    	

		        $result["exists"] = false;
		        $result["id"] = 0;

		        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();
		        if($postdata['city'] == '0')
        		{
            		$city_id = 0;
            		$state_id = $postdata['state'];
        		}
        		elseif(strpos($postdata["city"], '0_') !== false)
        		{
         //   echo "Hello"; exit();
           		 $state_id = str_replace("0_","",$postdata["city"]);
            	 $city_id = 0;
        	    }
        		else
        		{       // echo "Hey";exit();
         		 $state_id = 0;
         		 $city_id = $postdata["city"];
        		}

		       // echo "<pre>"; print_r($user);exit;

		        if(isset($user)){
		        	
		        	$user_id = $user->id;
		        	if($state_id == 0)
		        	{
		        		$alert = DB::table("master_users_favorite_strains")->select("id")->where("city",$city_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();
		        	}
		        	else
		        	{
		        		$alert = DB::table("master_users_favorite_strains")->select("id")->where("state",$state_id)->where("strain",$postdata["strain"])->where("user_id",$user_id)->first();

		        	}
		        	
		        	//echo "<pre>"; print_r($alert);exit;

		        	if(isset($alert) && isset($alert->id)){
			        	 //DB::table("cms_users_price_alerts_detail")->where("id",$alert->id)->update(array("status" => 1));
			        	 $result["exists"] = true;
			        	 $result["id"] = $alert->id;
			        }

		        }
    				//echo "<pre>"; print_r($result); exit;

		    }

		}