<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiTopStrainsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_strains";        
				$this->permalink   = "top_strains";    
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

		        //echo "<pre>"; print_r($postdata);exit;

		        $final_data = array();

		        $strains = DB::table("master_prices")
		        			->select("master_search_avg_prices.*","master_strains.name as strain_name")		        			
		        			->Leftjoin('master_search_avg_prices', function($join)
								 {
								   $join->on('master_search_avg_prices.strain_id', '=', 'master_prices.strain_id');
								  // $join->on('master_search_avg_prices.city_id', '=', '$postdata["city"]');

								 })
		        			->Leftjoin("master_strains","master_strains.id","=","master_prices.strain_id")
		        			->Leftjoin("master_locations","master_locations.id","=","master_prices.location_id")
		        			->where("master_search_avg_prices.city_id",$postdata["city"])
		        			->where("master_prices.city_id",$postdata["city"])
		        			->where("master_prices.mass_id",1)
		        			->where("master_prices.price",">",0)
		        			->whereNotNull("master_prices.strain_id")
		        			->groupBy("master_prices.strain_id")
		        			->orderBy("avg_price", "ASC")
		        			->limit(3)
		        			->get();
		        			//->toArray();

		        
		        
		        if(count($strains) > 0){

		        	foreach ($strains as $key => $strain) {

		        		//echo "<pre>"; print_r($strain);exit;

		        		$total = DB::table("master_prices")
	       							->select(DB::raw("master_prices.id,master_prices.location_id,master_locations.name,master_prices.price,master_locations.logoUrl"))
	       							->join("master_locations","master_locations.id","=","master_prices.location_id")
	       							->where("master_prices.city_id",$postdata["city"])
	       							->where("master_prices.price",">",0)
	       							->where("master_prices.mass_id",1)
	       							->where("master_prices.strain_id",$strain->strain_id)
	       							->whereNotNull("master_prices.strain_id")
	       							->groupBy("master_prices.location_id")
	       							->orderBy("master_prices.price", "ASC")
	       							->limit(3)
	       							->get()
	       							->toArray();

	       				$final_data[$key] = $strain;	       				
	       				$final_data[$key]->disp = $total;

		        	}

		        	// echo "<pre>"; print_r($final_data);exit;

		        }

		       
		        $result["data"] = $final_data;

		    }

		    

		}