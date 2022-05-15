<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use GeoIp2\Database\Reader;
		use CRUDBooster;

		class ApiGetusergeolocationController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_cities";        
				$this->permalink   = "getusergeolocation";    
				$this->method_type = "get";   
				$this->post_data = array();
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb'); 
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		       $this->post_data = $postdata;

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    	//$query->where("id",1065);
		    	//$query->select(DB::raw("id,city,state,country,state_id,CONCAT(city,', ',state_code) as name"));
		    	$query->limit(0);
		    }

		    public function hook_after($postdata,&$result) {

		    	//
		        //This method will be execute after run the main process

		       $clientIp = Request::getClientIp(true);

		       if($clientIp == "" | $clientIp == null)
		       {
		       		$clientIp = "174.6.81.237";
		       }

		       //$clientIp = "74.15.191.160";
		      

		       //$clientIp = "74.15.191.160";

		        $record = $this->reader->city($clientIp);


		       //echo "<pre>"; print_r($record);exit;

		        $new = array();

		        //echo "<pre>"; print_r($result);exit;
		    	
		    	$user_city = $record->city->name;
		    	$user_state_code = $record->mostSpecificSubdivision->isoCode;
		    	$user_country_code = $record->country->isoCode;
		    	$user_state = $record->mostSpecificSubdivision->name;
		    	$user_country = $record->country->name;
		    	$user_latitude = $record->location->latitude;
		    	$user_longitude = $record->location->longitude;

		    	$user = array();

		    	$city = DB::table("master_cities")
		    			->select(DB::raw("id,city,state,country,state_id,latitude,longitude,CONCAT(city,', ',state_code) as name"))
		    			->where("city",$user_city)
		    			->where("state_code",$user_state_code)
		    			->where("country_code",$user_country_code)
		    			->first();
		    	if(count((array) $city) == 0){

		    		$state = DB::table("master_states")
		    					->where("state_code",$user_state_code)
				    			->where("country_code",$user_country_code)
				    			->first();

				    if(count((array) $state) == 0){
				    	if($user_country_code == "US"){
			    			$tmp_id = 150;
			    		}else{
			    			$tmp_id = 1065;
			    		}
			    		$city = DB::table("master_cities")
			    			->select(DB::raw("id,city,state,country,state_id,latitude,longitude,CONCAT(city,', ',state_code) as name"))
			    			->where("id",$tmp_id)
			    			->first();		    		

			    		$new["data"] = $city;
				    }else{

				    	$notCity = array();
				    	$notCity["id"] = 0;
				    	$notCity["city"] = $user_city;
				    	$notCity["state"] = $state->state;
				    	$notCity["country"] = $user_country;
				    	$notCity["state_id"] = $state->id;
				    	$notCity["name"] = $state->state.", ".$user_country;
				    	$notCity["latitude"] = $user_latitude;
				    	$notCity["longitude"] = $user_longitude;
				    	$new["data"] = $notCity;
				    }
		    	}
		    	else
		    	{

		    		//echo "<pre>"; print_r($city);exit;
		    		$new["data"] = $city;
		    	}

		    	$cityLevel = false;
		    	$stateLevel = false;

		    	/*$dataCheck = DB::table("master_search_avg_prices")->where("city_id",$city->id)->first();

		    	if(count($dataCheck) > 0){
		    		$cityLevel = true;
		    	}

		    	$stateDataCheck = DB::table("master_search_avg_prices")->where("city_id",$city->state_id)->first();

	    		if(count($stateDataCheck) > 0){
	    			$stateLevel = true;		    			
	    		}*/

	    		$new["data"]->city_level = $cityLevel;
		    	$new["data"]->state_level = $stateLevel;

		    	$new["api_status"] = 1;
		        $new["api_message"] = "success";
		        $new["api_http"] = "200";
		    	$result['timestamps'] = time();
		    	$result = $new;

		    }

		}