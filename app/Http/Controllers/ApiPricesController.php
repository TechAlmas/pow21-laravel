<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use GeoIp2\Database\Reader;
		use CRUDBooster;

		class ApiPricesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "prices";    
				$this->method_type = "get";   
				//$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');
		    }
		

		    public function hook_before(&$postdata) {

		        //This method will be execute before run the main process		        

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {

		    	

		    	/*$clientIp = Request::getClientIp(true);
		    	$record = $this->reader->city('96.53.46.190');

		    	$city_name = $record->city->name;
		    	$state_name = $record->mostSpecificSubdivision->name;
		    	$country_name = $record->country->name;

		    	$state_code = $record->mostSpecificSubdivision->isoCode ;*/

		    	/*if(isset($postdata["city"]) && !empty($postdata["city"])){
		    		$condition = "city";
			        $city = explode(",", $postdata["city"]);			        

			        if(count($city) > 2){
			        	$condition = "city";
			        }else if(count($city) > 1){
			        	$condition = "state";
			        }else{
			        	$condition = "country";
			        }

			        $value = $city[0];
			    }*/

		       if($postdata["type"] == "dispensaries"){

		       //	echo "HEllo"; exit();


		       

		       	if(strpos($postdata["city"], '0_') !== false)
		       	{
		       		//echo "HEllo"; exit();
		       		$state_id = str_replace("0_","",$postdata["city"]);
		       		$city_id = 0;

		       		$state = DB::table("master_states")
		       		->select("state")
		       		->where("id",$state_id)
		       		->first();

		       	}
		       	else
		       	{
		       		
		       		$state = DB::table("master_cities")
		       		->select("state","state_id","id","city")
		       		->where("id",$postdata["city"])
		       		->first();

		       		$state_id = $state->state_id;
		       		$city_id = $state->id;

		       	}

		       	//echo $state_id;
		       /*	echo $city_id; exit();*/
		       	//echo "<pre>"; print_r($postdata);exit;

		       	//echo $postdata["state"];exit;

		       		if($postdata["strain"] == 0){

		       			$loc_array = array();
		       			
			       		/*$total = DB::table("master_prices")
			       					->select(DB::raw("master_prices.location_id,master_locations.name,master_prices.price,master_locations.logoUrl"))
			       					->join('master_locations', 'master_locations.id', '=', 'master_prices.location_id')	 
			       					->where("master_prices.price",">",0)
			       					->where("master_prices.city_id",$postdata["city"])
			       					->whereOr("master_prices.state_id",$postdata["state"])
			       					->where("master_prices.mass_id",$postdata["mass"])
			       					->groupBy("master_prices.location_id")
			       					->orderBy("master_prices.price", "ASC")
			       					->limit(4)
			       					->get();*/
			       		$sql = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.city_id = ".$city_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));

			       			foreach ($sql as $sq) {
		    				$id = $sq->id;
		    				array_push($loc_array,$id);
		    			}

		    			//print_r($loc_array); exit();

		    			if(count($loc_array) > 0)
		    			{


		    			$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.id NOT IN (".implode(',',$loc_array).") AND master_locations.state_id = ".$state_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}
		    			else
		    			{
		    				//echo"Harsh"; exit();
		    				$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.state_id = ".$state_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}

		    			$new_array = array_merge($sql,$sql1);

			       		//exit;
			       		$resData = array_slice($new_array,0,4);

		    			//$resData = DB::select( DB::raw($sql.' LIMIT 4') );

			       	}else{
			       		/*$total = DB::table("master_prices")
			       					->select(DB::raw("master_prices.location_id,master_locations.name,master_prices.price,master_locations.logoUrl"))
			       					->join('master_locations', 'master_locations.id', '=', 'master_prices.location_id')	 
			       					->where("master_prices.price",">",0)
			       					->where("master_prices.city_id",$postdata["city"])
			       					->whereOr("master_prices.state_id",$postdata["state"])
			       					->where("master_prices.strain_id",$postdata["strain"])
			       					->where("master_prices.mass_id",$postdata["mass"])
			       					->orderBy("master_prices.price", "ASC")
			       					->limit(4)
			       					->get();*/
			       					$loc_array = array();

			       		$sql = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.city_id = ".$city_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));

			       		foreach ($sql as $sq) {
		    				$id = $sq->id;
		    				array_push($loc_array,$id);
		    			}
		    			if(count($loc_array) > 0)
		    			{

		    			$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.id NOT IN (".implode(',',$loc_array).") AND master_locations.state_id = ".$state_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}
		    			else
		    			{
		    				$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.state_id = ".$state_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}

		    			$new_array = array_merge($sql,$sql1);
		    			$resData = array_slice($new_array,0,4);

		    			//$resData = DB::select( DB::raw($sql.' LIMIT 4') );
			       	}
			       //	echo "<pre>"; print_r($resData);exit;
			       	$result["state"] = $state->state;
			       	$result["city"] = $state->city;
			       	$result["data"] = $resData;
		       		

			   }elseif($postdata["type"] == "top") {		

	       			$top_strains = DB::select("SELECT master_prices.strain_id, master_strains.name, AVG(master_prices.price) as avg_price FROM master_locations LEFT JOIN master_prices ON master_prices.location_id = master_locations.id LEFT JOIN master_strains ON master_strains.id = master_prices.strain_id WHERE master_locations.city = '".$city_name."' AND master_prices.strain_id IS NOT NULL AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.price > 0 GROUP BY master_prices.strain_id ORDER BY avg_price limit 3");

	       			$final_data = array();

	       			foreach ($top_strains as $key => $strain) {

	       				$final_data[$key]["id"] = $strain->strain_id;
	       				$final_data[$key]["name"] = $strain->name;
	       				$final_data[$key]["avg_price"] = $strain->avg_price;
	       					
	       				$total = DB::table("master_prices")
	       							->select(DB::raw("master_prices.id,master_prices.location_id,master_locations.name,master_prices.price,master_locations.logoUrl"))
	       							->join('master_locations', 'master_locations.id', '=', 'master_prices.location_id')	       							
	       							->where("master_locations.city",$city_name)
	       							->where("master_prices.price",">",0)
	       							->where("master_prices.mass_id",$postdata["mass"])
	       							->where("master_prices.strain_id",$strain->strain_id)
	       							->whereNotNull("master_prices.strain_id")
	       							->groupBy("master_prices.location_id")
	       							->orderBy("master_prices.price", "ASC")
	       							->limit(3)
	       							->get()
	       							->toArray();
	       				$final_data[$key]["disp"] = $total; 

	       				$top_strains_s = DB::select("SELECT AVG(master_prices.price) as avg_price FROM master_prices LEFT JOIN master_locations ON master_prices.location_id = master_locations.id LEFT JOIN master_strains ON master_strains.id = master_prices.strain_id WHERE (master_locations.state = '".$state_name."' OR master_locations.state = '".$state_code."') AND master_prices.strain_id IS NOT NULL AND master_prices.strain_id = ".$strain->strain_id." AND master_prices.mass_id = ".$postdata["mass"]." AND master_prices.price > 0 GROUP BY master_prices.strain_id ORDER BY avg_price limit 1");   

	       				$final_data[$key]["state_avg_price"] = $top_strains_s[0]->avg_price;

	       			}

	       			$new = array();
	       			$new["user_city"] = $record->city->name;
		    		$new["user_state"] = $record->mostSpecificSubdivision->name;
		    		$new["user_state_code"] = $record->mostSpecificSubdivision->isoCode;
		    		$new["user_country"] = $record->country->name;
		    		$new["strains"] = $final_data;
		    		
	       			$result["data"] = $new;
	       			//echo "<pre>"; print_r($final_data);exit;
	       	  }elseif($postdata["type"] == "avg") {

	       	  	//echo $postdata['city']; exit();

	       	  	if($postdata['city'] == '0')
	       	  	{
	       	  		//echo "Hey"; exit();
	       	  		$city_id = 0 ;
	       	  		$state_id = $postdata['state'];
	       	  	}
	       	  	elseif(strpos($postdata["city"], '0_') !== false)
		       	{
		       		//echo "HEllo"; exit();
		       		$state_id = str_replace("0_","",$postdata["city"]);
		       		//$city_id = 0;

		       		/*$city = DB::table("master_cities")
		       		->select("id")
		       		->where("state_id",$state_id)
		       		->first();*/

		       		$city_id = 0;

		       	}
		       	else
		       	{
		       		$city_id = $postdata["city"];
		       		$state_id = 0;
		       	}
		      // 	echo $state_id; exit();
		       	/*echo  $state_id;
		       	echo $city_id; exit();*/
	       	  	$avg_price = array();
	       	  	if($state_id == 0)
	       	  	{
	       	  		$avg_price = DB::table("master_search_avg_prices")
		       						->select("master_search_avg_prices.avg_price","master_search_avg_prices.high_price","master_search_avg_prices.low_price","master_search_avg_prices.differ_price","master_search_avg_prices.differ_percent","master_strains.name as strain_name","master_mass.name as mass_name","master_cities.city as city_name")
		       						->leftjoin('master_cities', 'master_cities.id', '=', 'master_search_avg_prices.city_id')	 
		       						->leftjoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')	 
		       						->leftjoin('master_mass', 'master_mass.id', '=', 'master_search_avg_prices.mass_id')	 
		       						->where("master_search_avg_prices.city_id",$city_id)
		       						->where("master_search_avg_prices.strain_id",$postdata["strain"])
		       						->where("master_search_avg_prices.mass_id",$postdata["mass"])
		       						->first();
	       	  	}
	       	  	else
	       	  	{
	       	  		$avg_price = DB::table("master_search_avg_prices")
		       						->select("master_search_avg_prices.avg_price","master_search_avg_prices.high_price","master_search_avg_prices.low_price","master_search_avg_prices.differ_price","master_search_avg_prices.differ_percent","master_strains.name as strain_name","master_mass.name as mass_name","master_cities.city as city_name")
		       						->leftjoin('master_cities', 'master_cities.id', '=', 'master_search_avg_prices.city_id')	 
		       						->leftjoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')	 
		       						->leftjoin('master_mass', 'master_mass.id', '=', 'master_search_avg_prices.mass_id')	 
		       						->where("master_search_avg_prices.state_id",$state_id)
		       						->where("master_search_avg_prices.strain_id",$postdata["strain"])
		       						->where("master_search_avg_prices.mass_id",$postdata["mass"])
		       						->first();
	       	  	}
		       		

		       		if(count((array) $avg_price) == 0){

		       			$state = DB::table("master_cities")->select("state_id")->where("id",$postdata["city"])->first();

		       			if(count((array) $state) > 0){
		       				$avg_price = DB::table("master_search_avg_prices")
		       						->select("master_search_avg_prices.avg_price","master_search_avg_prices.high_price","master_search_avg_prices.low_price","master_search_avg_prices.differ_price","master_search_avg_prices.differ_percent","master_strains.name as strain_name","master_mass.name as mass_name","master_cities.city as city_name")
		       						->leftjoin('master_cities', 'master_cities.id', '=', 'master_search_avg_prices.city_id')	 
		       						->leftjoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')	 
		       						->leftjoin('master_mass', 'master_mass.id', '=', 'master_search_avg_prices.mass_id')	 
		       						->where("master_search_avg_prices.state_id",$state->state_id)
		       						->where("master_search_avg_prices.strain_id",$postdata["strain"])
		       						->where("master_search_avg_prices.mass_id",$postdata["mass"])
		       						->first();
		       			}

		       			
		       		}
		       		

		       		$result["data"] = $avg_price;  

		       }else{

		       		 $avg_price = array();

		       		$state = DB::table("master_cities")->select("state_id")->where("id",$postdata["city"])->first();

		       		$city_id = $postdata["city"];
		       		$state_id = $state->state_id;
		       		$country = $postdata['country'];
		       		//echo $city_id; exit;
		       		$avg_price = DB::table("master_search_avg_prices")
		       						->select("master_search_avg_prices.city_id","master_search_avg_prices.state_id","master_search_avg_prices.country","master_search_avg_prices.avg_price","master_search_avg_prices.high_price","master_search_avg_prices.low_price","master_search_avg_prices.differ_price","master_search_avg_prices.differ_percent","master_strains.name as strain_name","master_mass.name as mass_name","master_cities.city as city_name")
		       						->leftjoin('master_cities', 'master_cities.id', '=', 'master_search_avg_prices.city_id')	 
		       						->leftjoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')	 
		       						->leftjoin('master_mass', 'master_mass.id', '=', 'master_search_avg_prices.mass_id')	 
		       						
		       						->where("master_search_avg_prices.strain_id",$postdata["strain"])
		       						->where("master_search_avg_prices.mass_id",$postdata["mass"])
			       					->where(function($result) use ($city_id,$state_id,$country) {
									         $result->orwhere("master_search_avg_prices.city_id",$city_id)
									           ->orWhere("master_search_avg_prices.state_id",$state_id)
									           ->orwhere("master_search_avg_prices.country",$country);
									     })
		       						->get();

		       		//print_r($avg_price); exit();
		       		
		       			$result["data"] = $avg_price;          		
		       	
		       		
		       }

		    }

		}