<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetStrainPricesDetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_search_avg_prices_history";        
				$this->permalink   = "get_strain_prices_detail";    
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

		        $finalData = array();

		        $massData = array(1,7,10);

		        $dates = DB::table("master_search_avg_prices_history")
		        			->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),"mass_id","avg_price")
		        			->where("city_id",$postdata["city_id"])
		        			->where("strain_id",$postdata["strain_id"])
		        			->whereIn("mass_id",$massData)
		        			->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"),"master_search_id","mass_id")
		        			->get();

		        $cnt = -1;
		        $tmp_date = "";

		        foreach ($dates as $key => $value) {

		        	if($tmp_date != $value->date){
		        		$tmp_date = $value->date;
		        		/*if($key > 0){
		        			$cnt++;
		        		}*/	
		        		$cnt++;	        		
		        	}

		        	$finalData[$cnt]["date"] = $value->date;
		        	$finalData[$cnt][$value->mass_id] = $value->avg_price;
		        }

		        //echo "<pre>"; print_r($finalData);exit;

		        

		        /*$finalData = array();

		        foreach ($massData as $key => $mass) {
		        	$tmp_price = DB::table("master_search_avg_prices_history")
		        					->where("city_id",$postdata["city_id"])
		        					->where("strain_id",$postdata["strain_id"])
		        					->where("mass_id",$mass)
		        					->orderBy("created_at","DESC")
		        					->groupBy("created_at")
		        					->get()
		        					->toArray();
		        	$finalData[$mass] = $tmp_price;		        	
		        }*/
		        $result["data"] = $finalData;

		    }

		}