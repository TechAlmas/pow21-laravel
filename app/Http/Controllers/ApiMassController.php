<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiMassController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {   

				$this->table       = "master_mass";        
				$this->permalink   = "mass";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->orderby("name","asc");
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {

		    	//print_r($postdata); exit();
	    		$masses = array();
	    		if($postdata["cityId"] == '0')
		        {
		        	$state_id = $postdata['state'];
		        	$city_id = 0;
		        }
		        elseif(strpos($postdata["cityId"], '0_') !== false)
		        {
		        	//echo "Hello"; exit();
		        	$state_id = str_replace("0_","",$postdata["cityId"]);
		        	$city_id = 0;
		        }
		        else
		        {
		        	//echo "Hey";exit();
		        	$state_id = 0;
		        	$city_id = $postdata["cityId"];
		        }

		       // echo $state_id; exit();

		        if($state_id == 0)
		        {

	    		$masses = DB::table("master_mass")
	        			->select("master_search_avg_prices.mass_id","master_mass.name")
	        			->join("master_search_avg_prices","master_search_avg_prices.mass_id","=", "master_mass.id")
	        			->where("master_search_avg_prices.city_id",$city_id)
	        			->where("master_search_avg_prices.strain_id",$postdata["strain_id"])
	        			->groupBy("master_search_avg_prices.mass_id")
	        			->get();
	        	}
	        	else
	        	{
	        		$masses = DB::table("master_mass")
	        			->select("master_search_avg_prices.mass_id","master_mass.name")
	        			->join("master_search_avg_prices","master_search_avg_prices.mass_id","=", "master_mass.id")
	        			->where("master_search_avg_prices.state_id",$state_id)
	        			->where("master_search_avg_prices.strain_id",$postdata["strain_id"])
	        			->groupBy("master_search_avg_prices.mass_id")
	        			->get();
	        	}

	        	if(count($masses) == 0){

	        		$state = DB::table("master_cities")->select("state_id")->where("id",$postdata["cityId"])->first();

	        		if(count($state) > 0){
	        			$masses = DB::table("master_mass")
	        			->select("master_search_avg_prices.mass_id","master_mass.name")
	        			->join("master_search_avg_prices","master_search_avg_prices.mass_id","=", "master_mass.id")
	        			->where("master_search_avg_prices.state_id",$state->state_id)
	        			->where("master_search_avg_prices.strain_id",$postdata["strain_id"])
	        			->groupBy("master_search_avg_prices.mass_id")
	        			->get();
	        		}
	        		
		    	}

		    	$result["data"] = $masses;	
			}

		}