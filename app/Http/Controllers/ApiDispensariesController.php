<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDispensariesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "dispensaries";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		       	//echo "<pre>"; print_r($postdata);exit;

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {

		    	//echo "Hello"; exit;

		    	if($result["api_status"] == 1){

		    		if(isset($postdata["page"]) && !empty($postdata["page"])){

		    			if($postdata["page"] > 0){		    				
		    				$limit = 10;
		    				$offset = $limit*($postdata["page"]-1);
		    			}else{
		    				$offset = 0;
		    				$limit = 10;
		    			}

		    		}else{
		    			$offset = 0;
		    			$limit = 10;
		    		}	

		    		if (strpos($postdata["city"], '0_') !== false)
		    		{
		    			$state_id = str_replace("0_","",$postdata["city"]);
		    			$city_id_data = 0;
		    			$st = $sql = DB::select( DB::raw("SELECT * FROM master_states where id=".$state_id));
		    			
		    			$state_name=$st[0]->state; 


		    		}
		    		else
		    		{
		    			$state = DB::table("master_cities")->select("state","state_id","id")->where("id",$postdata["city"])->first();
		    			$state_id = $state->state_id;
		    			$city_id_data = $state->id;
		    			$state_name = $state->state;
		    		}
		    	
		    		//echo $state_id; exit();	

		    		if($postdata["strain"] == 0){

		    			//echo "Hello12345"; exit;

		    			$loc_array = array();
		    			$sql = array();
		    			$sql1 = array();



		    			$sql = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.city_id = ".$city_id_data." AND master_prices.mass_id = 1 AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));

		    			//print_r($sql); exit;

		    			foreach ($sql as $sq) {
		    				$id = $sq->id;
		    				array_push($loc_array,$id);
		    			}
		    			//array_push($loc_array,'6471');

		    			//print_r($loc_array); exit();

		    			if(count($loc_array) > 0)
		    			{
		    				$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.id NOT IN (".implode(',',$loc_array).") AND master_locations.state_id = ".$state_id." AND master_prices.mass_id = 1 AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}
		    			else
		    			{
		    				$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price FROM `master_locations` LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.state_id = ".$state_id." AND master_prices.mass_id = 1 AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}

		    			

		    			$new_array = array_merge($sql,$sql1);

		    			//print_r($new_array);exit();



		    			//$resData = DB::select( DB::raw($sql.' LIMIT 10 OFFSET '.$offset) );
		    			$resData = array_slice($new_array,$offset,$limit);

			    		$recCnt = count($new_array);
			    		//print_r($new_array); exit();
			    		//print_r($resData); exit();

			    		//print_r($resData); exit();


		    			/*$dispensaries = DB::table("master_prices")
			       					->select(DB::raw("master_prices.price,master_locations.*"))
			       					->join('master_locations', 'master_locations.id', '=', 'master_prices.location_id')	 
			       					->where("master_prices.price",">",0)
			       					->where("master_prices.city_id",$postdata["city"])
			       					//->where("master_prices.strain_id",$postdata["strain"])
			       					->where("master_prices.mass_id",1)
			       					->groupBy("master_prices.location_id")
			       					->orderBy("master_prices.price", "ASC");

			       		$recCnt = $dispensaries->get()->count();

       					$dispensaries->offset($offset);
       					$dispensaries->limit($limit);
       					$resData = $dispensaries->get();*/



			       		/*$dispensaries = DB::table("master_locations_strains1");
       					$dispensaries->select(DB::raw("master_locations.*"));
       					$dispensaries->leftjoin('master_locations', 'master_locations.id', '=', 'master_locations_strains.location_id');
       					$dispensaries->leftjoin('master_prices', function ($join) {
		    				$join->on('master_prices.location_id', '=', 'master_locations_strains.location_id')
		    					 ->where('master_prices.mass_id',1)
		    					 ->where("master_prices.price",">",0);
		    			});
       					$dispensaries->where("master_locations.city_id",$postdata["city"]);
       					$dispensaries->groupBy("master_locations_strains.location_id");
       					$dispensaries->orderBy("master_locations_strains.location_id");
       					$recCnt = $dispensaries->get()->count();
       					$dispensaries->offset($offset);
       					$dispensaries->limit($limit);       					
       					$resData = $dispensaries->get();*/

       					/*$dispensaries1 = DB::table("master_locations_strains");
       					$dispensaries1->join('master_locations', 'master_locations.id', '=', 'master_locations_strains.location_id');	
       					$dispensaries1->where("master_locations.city_id",$postdata["city"]);
       					$dispensaries1->groupBy("master_locations.id");
       					$recCnt = $dispensaries1->get()->count();*/

			       	}else{


			       		//echo "Hello"; exit();
			       		$loc_array = array();
			       		$sql = array();
		    			$sql1 = array();

			       		$sql = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.city_id = ".$city_id_data." AND master_prices.mass_id = 1 AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));

			       		foreach ($sql as $sq) {
		    				$id = $sq->id;
		    				array_push($loc_array,$id);
		    			}

		    			if(count($loc_array) > 0)
		    			{

		    			$sql1 = DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.id NOT IN (".implode(',',$loc_array).") AND master_locations.state_id = ".$state_id." AND master_prices.mass_id = 1 AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}

		    			else
		    			{
		    			$sql1 =	DB::select( DB::raw("SELECT master_locations.*,master_cities.city,master_states.state, master_prices.price,master_strains.category  FROM `master_locations`
			       			LEFT JOIN master_strains ON master_strains.id =". $postdata["strain"]."
			       			 LEFT JOIN master_cities ON master_cities.id = master_locations.city_id LEFT JOIN master_states ON master_states.id = master_locations.state_id RIGHT JOIN master_prices ON master_prices.city_id = master_cities.id AND master_prices.location_id = master_locations.id WHERE master_locations.state_id = ".$state_id." AND master_prices.mass_id = 1 AND master_prices.strain_id = ".$postdata["strain"]." AND master_prices.price > 0 GROUP BY master_prices.location_id ORDER BY master_prices.price ASC"));
		    			}

		    			//print_r($sql); exit();

		    			$new_array = array_merge($sql,$sql1);
		    			
		    			$resData = array_slice($new_array,$offset,$limit);

			    		$recCnt = count($new_array);



		    			//$resData = DB::select( DB::raw($sql.' LIMIT 10 OFFSET '.$offset) );

			    		//$recCnt = count(DB::select( DB::raw($sql)));

			       		/*$dispensaries = DB::table("master_prices1")
			       					->select(DB::raw("master_prices.price,master_locations.*"))
			       					->join('master_locations', 'master_locations.id', '=', 'master_prices.location_id')	 
			       					->where("master_prices.price",">",0)
			       					->where("master_prices.city_id",$postdata["city"])
			       					->where("master_prices.strain_id",$postdata["strain"])
			       					->where("master_prices.mass_id",1)
			       					//->groupBy("master_prices.location_id")
			       					->orderBy("master_prices.price", "ASC");

			       		$recCnt = $dispensaries->get()->count();

       					$dispensaries->offset($offset);
       					$dispensaries->limit($limit);
       					$resData = $dispensaries->get();*/

			       		/*$dispensaries = DB::table("master_locations_strains");
       					$dispensaries->select(DB::raw("master_locations.*"));
       					$dispensaries->leftjoin('master_locations', 'master_locations.id', '=', 'master_locations_strains.location_id');
       					$dispensaries->leftjoin('master_prices', function ($join) {
		    				$join->on('master_prices.location_id', '=', 'master_locations_strains.location_id')
		    					 ->where('master_prices.mass_id',1)
		    					 ->where("master_prices.strain_id",$postdata["strain"])
		    					 ->where("master_prices.price",">",0);
		    			});
       					$dispensaries->where("master_locations.city_id",$postdata["city"]);
       					$dispensaries->where("master_locations_strains.strain_id",$postdata["strain"]);
       					$dispensaries->groupBy("master_locations_strains.location_id");
       					$dispensaries->orderBy("master_locations_strains.location_id");
       					$recCnt = $dispensaries->get()->count();
       					$dispensaries->offset($offset);
       					$dispensaries->limit($limit);        					
       					$resData = $dispensaries->get();*/

       					/*$dispensaries1 = DB::table("master_locations_strains");
       					$dispensaries1->join('master_locations', 'master_locations.id', '=', 'master_locations_strains.location_id');	
       					$dispensaries1->where("master_locations.city_id",$postdata["city"]);
       					$dispensaries1->where("master_locations_strains.strain_id",$postdata["strain"]);
       					$dispensaries1->groupBy("master_locations_strains.location_id");
       					$recCnt = $dispensaries1->get()->count();*/
			       		
			       	}
		    		$result["state"] = $state_name;
		    		$result["data"] = $resData;
		    		$result["total_data"] = $recCnt;


		    	}		        

		    }

		}