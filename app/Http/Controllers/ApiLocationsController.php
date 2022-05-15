<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use GeoIp2\Database\Reader;
		use CRUDBooster;

		class ApiLocationsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "locations";    
				$this->method_type = "get";    
				$this->post_data = array();
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		    	$this->post_data = $postdata;
		        //echo "<pre>"; print_r($postdata);exit;

		    }

		    public function hook_query(&$query) {
		    	
		        $query->limit(0);
		    }

		    public function hook_after($postdata,&$result) {

		    	//print_r($postdata); exit;
		    	$city = json_decode($postdata["userCityData"]);

		    //print_r($city); exit();

		    	//print_r($city->id); exit;
		    	
		    	$data = array();
		    	$data1 = array();
		    	$data2 = array();

		   		$latitude = $city->latitude; 
		    	$longitude = $city->longitude;
		    	$country = $city->country;
		    	if($country == 'us')
		    	{
		    		$country = 'United States';
		    	}

		    	//echo $country; exit();

		    	//$latitude = $record->location->latitude;
		    	//$longitude = $record->location->longitude;


		    	if($city->id > 0){

		    	
		    		$query = DB::table("master_cities");		    		
			    	$query->select(DB::raw("master_cities.city,master_cities.id,master_cities.state,master_cities.state_id,CONCAT(master_cities.city,', ',master_cities.state_code) as name,( 6371 * acos( cos( radians($latitude) ) * cos( radians( master_cities.latitude ) ) * cos( radians( master_cities.longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( master_cities.latitude) ) ) ) AS distance"));	
			    	$query->join("master_search_avg_prices","master_search_avg_prices.city_id","=", "master_cities.id");
			    	$query->where("master_cities.id","<>",$city->id);
			    	$query->where("master_cities.status","<>",2); 

			    	$query->where(function ($query_cond) use ($country){
    						$query_cond->where("master_cities.country_code",$country)
          					->orWhere("master_cities.country",$country);
					});

			    	$query->where("master_cities.country",$country); 	
			    	$query->havingRaw('distance < 500');
			    	$query->orderBy("distance","ASC");
			    	$query->orderBy("name","ASC");
			    	$query->groupBy("master_cities.id");
			    	$query->offset(0);
			    	$query->limit(5);

			    	$data = $query->get();

			    	//print_r($data); exit;
		    	}
		    	

		    	$city_array = array();
		    	$city_array[] = $city->id;

		    	foreach ($data as $key => $value) {
		    		$city_array[] = $value->id;
		    	}

		    	//print_r($city_array); exit();

		    	/*$exlude_ids = array();
		    	foreach ($data as $key => $city) {
		    		$exlude_ids[] = $city->id;
		    	}*/

		    	if($city->state_id > 0){
		    		//echo "Hello"; exit();

			    	$query1 = DB::table("master_cities");
			    	$query1->select(DB::raw("master_cities.city,master_cities.id,master_cities.state,master_cities.state_id,CONCAT(master_cities.city,', ',master_cities.state_code) as name"));
			    	$query1->join("master_search_avg_prices","master_search_avg_prices.city_id","=", "master_cities.id");
			    	$query1->where(function ($query_cond) use ($country){
    						$query_cond->where("master_cities.country_code",$country)
          					->orWhere("master_cities.country",$country);
					});
			    	$query1->where("master_cities.country",$country); 
			    	$query1->whereNotIn("master_cities.id",$city_array);
			    	$query1->where("master_cities.state_id",$city->state_id);
			    	$query1->where("master_cities.status","<>",2);
			    	$query1->groupBy("master_cities.id");
			    	$query1->orderByRaw("name ASC");
			    	$data1 = $query1->get();

			    }

		    	$query2 = DB::table("master_cities");
		    	$query2->select(DB::raw("master_cities.city,master_cities.id,master_cities.state,master_cities.state_id,CONCAT(master_cities.city,', ',master_cities.state_code) as name"));
		    	$query2->join("master_search_avg_prices","master_search_avg_prices.city_id","=", "master_cities.id");
		    	$query2->where(function ($query_cond) use ($country){
    						$query_cond->where("master_cities.country_code",$country)
          					->orWhere("master_cities.country",$country);
					});

		    	$query2->whereNotIn("master_cities.id",$city_array);
		    	$query2->where("master_cities.state_code","!=",$city->state_code);
		    	$query2->where("master_cities.status","<>",2);
		    	$query2->groupBy("master_cities.id");
		    	$query2->orderByRaw("name ASC");
		    	$data2 = $query2->get();

		    	$state = DB::table("master_cities")
		    		->join("master_states","master_states.id","master_cities.state_id")
		    		->join("master_search_avg_prices","master_search_avg_prices.city_id","=", "master_cities.id")
		    		->select("master_cities.id as city_id","master_states.id as state_id","master_states.state")
		    		->groupBy("master_cities.state_id")
		    		->where("master_cities.country",$country)
		    		->where("master_cities.state_id","!=",$city->state_id)

		    		->get();
		    		$state_data = array();


		    		//print_r($country); exit();

		    		foreach ($state as $key => $st) {
		    			$state_data[$key]["city_id"] = $st->city_id;
		    			$state_data[$key]["state_id"] = "0_".$st->state_id;
		    			$state_data[$key]["state"] = $st->state;
		    		}

		    		$states_cities =  DB::select( DB::raw("SELECT master_cities.state,master_cities.city,master_cities.id,master_cities.state_id,CONCAT(master_cities.city,', ',master_cities.state_code) as name 
						FROM `master_cities` 
						JOIN master_search_avg_prices ON master_search_avg_prices.city_id = master_cities.id
						WHERE master_cities.country='".$country."' AND master_cities.state_id !=".$city->state_id."
						GROUP By master_cities.id
						ORDER By master_cities.state,name"));


		    			$new_state_array =  array();


		    		foreach ($states_cities as $key => $state_city) {
		    			$new_state_array[$key]['state_id'] = "0_".$state_city->state_id;
		    			 $new_state_array[$key]['id'] = $state_city->id;
		    			 $new_state_array[$key]['state_name'] = $state_city->state;
		    			 $new_state_array[$key]['name'] = $state_city->name;

		    		}


		    		//echo "<pre>"; print_r($new_state_array); exit();

		    	/*$query2 = DB::table("master_cities");
		    	$query2->select(DB::raw("id,CONCAT(city,', ',state_code) as name"));
		    	$query2->where("country","!=",$record->country->name);
		    	$query2->orderByRaw("name ASC");
		    	$data2 = $query2->get();*/

		    	//$new["user_state_data"] = $stateLevel;
		    	//$new["user_location"] = $city;
		    	//$new["user_city_id"] = $city->id;
		    	//print_r($data1); exit();
		    	//$list_city = array_merge($data1,$data2);
		    	//$new["list_city"] = array_slice($list_city,0,3);
		    	$new["near_cities"] = $data;
		    	$new["near_other_cities"] = $data1;
		    	$new["rest_cities"] = $data2;
		    	$new["state"] = $state_data;
		    	$new['new_state_array'] = $new_state_array;
		    	/*$new["user_city"] = $city->name;
		    	$new["user_state"] = $city->state;
		    	$new["user_state_id"] = $city->state_id;
		    	$new["user_country"] = $country;*/

		    	$result["data"] = $new;

		    	//echo "<pre>"; print_r($data);exit;		        

		    }

		}