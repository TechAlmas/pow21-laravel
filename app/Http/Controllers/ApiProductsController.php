<?php namespace App\Http\Controllers;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		class ApiProductsController extends \crocodicstudio\crudbooster\controllers\ApiController {
		    function __construct() {    
				$this->table       = "master_strains";        
				$this->permalink   = "product_listing";    
				$this->method_type = "get";    
		    }
		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		    }
		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        //$query->orderby("name","asc");
		        $query->limit(0);
		    }
		    public function hook_after($postdata,&$result) {
		    	//echo "Hello"; exit();
		    	//echo json_encode(array("test 1" => array(1,2,3),"test 2" => array(1,2,3)));   	exit;
		       //print_r($postdata);exit;
		    	if (strpos($postdata["cityId"], '0_') !== false)
				{
					$state_id_data = str_replace("0_","",$postdata["cityId"]);
					$postdata["state_id"] =0;
					$postdata["cityId"] =0;
					//echo $state_id_data; exit();
				}
				else
				{
					$state_id_data =0;
				}
		        if(isset($postdata["cityId"]) && !empty($postdata["cityId"])){
		        	//echo "jay"; exit();
		        	$strains = array();
		        	//echo $postdata["cityId"];exit;
		        	$strains = DB::table("master_search_avg_prices")
		        			->select("master_search_avg_prices.strain_id","master_strains.name","master_strains.slug")
		        			->leftJoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')
		        			->where("city_id",$postdata["cityId"])
		        			->groupBy("master_search_avg_prices.strain_id")
		        			->orderBy('name','ASC')
		        			->get();
		        	if(count($strains) == 0){
		        		if($postdata["cityId"] == 0 && isset($postdata["state_id"])){
		        			$stateId = $postdata["state_id"];
		        		}else{
		        			$state = DB::table("master_cities")->select("state_id")->where("id",$postdata["cityId"])->first();
		        			$stateId = $state->state_id;
		        		}
		        		//print_r($state);exit;
		        		if($stateId > 0){
		        			$strains = DB::table("master_search_avg_prices")
			        			->select("master_search_avg_prices.strain_id","master_strains.name","master_strains.slug")
			        			->leftJoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')
			        			->where("state_id",$stateId)
			        			->groupBy("master_search_avg_prices.strain_id")
			        			->orderBy('name','ASC')
			        			->get();
		        		}		        		
		        	}	        	
		        	$result["data"] = $strains;		
		        }else if((isset($postdata["positive"]) && !empty($postdata["positive"])) || (isset($postdata["negative"]) && !empty($postdata["negative"])) || (isset($postdata["flavour"]) && !empty($postdata["flavour"])) || (isset($postdata["medical"]) && !empty($postdata["medical"])))
				{
		        	$city_id = (int) $postdata["city_id"];
			    	$user_id = (int) $postdata["user_id"];	
			    	$page = (int) $postdata["page"];
			    	if($page == 1){
			    		$offset = 0;			    		
			    	}else{
			    		$offset = ($page - 1) * 48;
			    	}
			    	$main_cond_in = "";
			    	$sub_cond_having = "";
			    	if(isset($postdata["positive"]) && !empty($postdata["positive"])){
			    		$main_cond_in .= "1,";
			    		$posData = explode(",",$postdata["positive"]);
		        		//print_r($posData);exit;
		        		$positive = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",1)
		        			->whereIn("name",$posData)
		        			->first();
		        		foreach ($positive as $key => $value) {
		        			$sub_cond_having .= '(grp_sub like "'.$value.'" OR grp_sub like "'.$value.',%" OR grp_sub like "%,'.$value.',%" OR grp_sub like "%,'.$value.'") AND';
		        		}
			    	}
			    	if(isset($postdata["medical"]) && !empty($postdata["medical"])){
			    		$main_cond_in .= "2,";
			    		$posData = explode(",",$postdata["medical"]);
		        		//print_r($posData);exit;
		        		$positive = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",2)
		        			->whereIn("name",$posData)
		        			->first();
		        		foreach ($positive as $key => $value) {
		        			$sub_cond_having .= '(grp_sub like "'.$value.'" OR grp_sub like "'.$value.',%" OR grp_sub like "%,'.$value.',%" OR grp_sub like "%,'.$value.'") AND';
		        		}
			    	}
			    	if(isset($postdata["negative"]) && !empty($postdata["negative"])){
			    		$main_cond_in .= "3,";
			    		$posData = explode(",",$postdata["negative"]);
		        		//print_r($posData);exit;
		        		$positive = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",3)
		        			->whereIn("name",$posData)
		        			->first();
		        		foreach ($positive as $key => $value) {
		        			$sub_cond_having .= '(grp_sub not like "'.$value.'" OR grp_sub not like "'.$value.',%" OR grp_sub not like "%,'.$value.',%" OR grp_sub not like "%,'.$value.'") AND';
		        		}
			    	}
			    	if(isset($postdata["flavour"]) && !empty($postdata["flavour"])){
			    		$main_cond_in .= "8,";
			    		$posData = explode(",",$postdata["flavour"]);
		        		//print_r($posData);exit;
		        		$positive = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",8)
		        			->whereIn("name",$posData)
		        			->first();
		        		foreach ($positive as $key => $value) {
		        			$sub_cond_having .= '(grp_sub like "'.$value.'" OR grp_sub like "'.$value.',%" OR grp_sub like "%,'.$value.',%" OR grp_sub like "%,'.$value.'") AND';
		        		}
			    	}
			    	$main_cond_in = rtrim($main_cond_in,",");
			    	$sub_cond_having = rtrim($sub_cond_having," AND");
			    	$sql = 'SELECT master_strains.*,CEILING(master_strains.reviews*96.5/100) as reviews_count,(SELECT id FROM master_users_price_alerts WHERE strain = master_strains.id AND city = 1065 AND user_id = 10) as alert_id,CEILING(master_strains.reviews*96.5/100) as reviews,(SELECT id FROM master_users_favorite_strains WHERE strain = master_strains.id AND city = 1065 AND user_id = 10) as fav_id, GROUP_CONCAT(sub_attribute_id) as grp_sub FROM `master_strains_attributes` 
						LEFT JOIN master_strains ON master_strains.id = master_strains_attributes.strain_id
						WHERE master_strains_attributes.attribute_id IN ('.$main_cond_in.') AND value > 0
						GROUP BY strain_id
						HAVING '.$sub_cond_having.'
						ORDER BY `master_strains`.`ratings` DESC';
			    	$strains = DB::select( DB::raw($sql.' LIMIT 48 OFFSET '.$offset) );
			    	$strains_count = DB::select( DB::raw($sql));
			    	$result["data"] = $strains;
			    	$result["total_count"] = count($strains_count);
			    	//echo "<pre>"; print_r($strains);exit;
		        	/*$query = DB::table("master_strains_attributes");
		        	$query->select("master_strains.*",DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews_count"),DB::raw("(SELECT id FROM master_users_price_alerts WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as alert_id"),DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews"),DB::raw("(SELECT id FROM master_users_favorite_strains WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as fav_id"));
		        	$query->join('master_strains', 'master_strains.id', '=', 'master_strains_attributes.strain_id');
		        	if(isset($postdata["positive"]) && !empty($postdata["positive"])){
		        		$posData = explode(",",$postdata["positive"]);
		        		//print_r($posData);exit;
		        		$positive = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",1)
		        			->whereIn("name",$posData)
		        			->first();	
		        		$tmp_pos = 	 explode(",",$positive->pois);       		
		        		$query->whereIn("master_strains_attributes.sub_attribute_id",$tmp_pos);
		        	}
		        	if(isset($postdata["medical"]) && !empty($postdata["medical"])){
		        		$posData = explode(",",$postdata["medical"]);
		        		//print_r($posData);exit;
		        		$medical = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",2)
		        			->whereIn("name",$posData)
		        			->first();	
		        		$tmp_pos = 	 explode(",",$medical->pois);       		
		        		$query->whereIn("master_strains_attributes.sub_attribute_id",$tmp_pos);
		        	}
		        	if(isset($postdata["negative"]) && !empty($postdata["negative"])){
		        		$posData = explode(",",$postdata["negative"]);
		        		//print_r($posData);exit;
		        		$negative = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",3)
		        			->whereIn("name",$posData)
		        			->first();	
		        		$tmp_pos = 	 explode(",",$negative->pois);       
		        		//print_r($tmp_pos);exit;		
		        		$query->whereNotIn("master_strains_attributes.sub_attribute_id",$tmp_pos);
		        	}
		        	if(isset($postdata["flavor"]) && !empty($postdata["flavor"])){
		        		$posData = explode(",",$postdata["flavor"]);
		        		//print_r($posData);exit;
		        		$flavor = DB::table("master_attributes_sub")
		        			->select(DB::raw("GROUP_CONCAT(id) as pois"))
		        			->where("attribute_id",8)
		        			->whereIn("name",$posData)
		        			->first();	
		        		$tmp_pos = 	 explode(",",$flavor->pois);  
		        		$query->whereIn("master_strains_attributes.sub_attribute_id",$tmp_pos);
		        	}
		        	$query->groupBy("master_strains_attributes.strain_id");
		        	//$query->orderBy("master_strains.name","ASC");
		        	$query->orderBy('master_strains.ratings','DESC');
		        	$query2 = clone $query;		        	
		        	$query->limit(48);
		        	$query->offset($offset);
		        	$res = $query->get();
		        	$strains_count = $query2->get()->count();
		        	$result["total_count"] = $strains_count;
		        	$result["data"] = $res;*/ 
			    }else{
			    	//echo "Hello"; exit();
			    	if($postdata["state_id"] > 0){
			    		$strains = DB::table("master_search_avg_prices")
			        			->select("master_search_avg_prices.strain_id","master_strains.name","master_strains.slug")
			        			->leftJoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')
			        			->where("state_id",$postdata["state_id"])
			        			->groupBy("master_search_avg_prices.strain_id")
			        			->orderBy('name','ASC')
			        			->get();
			        	$result["data"] = $strains;
			    	}else if($state_id_data > 0) {
			    		//echo "Hello123"; exit();
			    		$strains = DB::table("master_search_avg_prices")
			        			->select("master_search_avg_prices.strain_id","master_strains.name","master_strains.slug")
			        			->leftJoin('master_strains', 'master_strains.id', '=', 'master_search_avg_prices.strain_id')
			        			->where("state_id",$state_id_data)
			        			->groupBy("master_search_avg_prices.strain_id")
			        			->orderBy('name','ASC')
			        			->get();
			        	$result["data"] = $strains;
			    	}else{
			    		//print_r($postdata);exit;
				    	$city_id = (int) $postdata["city_id"];
				    	$user_id = (int) $postdata["user_id"];
				    	$page = (int) $postdata["page"];
				    	if($page == 1){
				    		$offset = 0;			    		
				    	}else{
				    		$offset = ($page - 1) * 48;
				    	}
				    	//exit;
				    	$strains = DB::table("master_strains")
				    		->select("master_strains.*","master_strains.id as strain_id",DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews"),DB::raw("(SELECT id FROM master_users_price_alerts WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as alert_id"),DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews"),DB::raw("(SELECT id FROM master_users_favorite_strains WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as fav_id","master_strains.slug"))
				    		->limit(48)
				    		->offset($offset)
				    		//->orderBy('name','ASC')
				    		->orderBy('master_strains.ratings','DESC')
				    		->get();
				    	//	print_r($strains); exit();
				    	$strains_count = DB::table("master_strains")
				    		->select("master_strains.*",DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews"),DB::raw("(SELECT id FROM master_users_price_alerts WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as alert_id"),DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews"),DB::raw("(SELECT id FROM master_users_favorite_strains WHERE strain = master_strains.id AND city = $city_id AND user_id = $user_id) as fav_id"))			    		
				    		//->orderBy('name','ASC')
				    		->orderBy('master_strains.ratings','DESC')
				    		->count();
				    	$result["total_count"] = $strains_count;
				    	$result["data"] = $strains;
			    	}
			    }
			    //echo "<pre>"; print_r($result);exit;
		        /*if(isset($postdata["city"]) && !empty($postdata["city"])){
		        	$result["data"] = DB::select("SELECT master_prices.strain_id,master_strains.name FROM master_locations LEFT JOIN master_prices ON master_prices.location_id = master_locations.id LEFT JOIN master_strains ON master_strains.id = master_prices.strain_id WHERE master_locations.city = '".$postdata["city"]."' AND master_prices.strain_id IS NOT NULL GROUP BY master_prices.strain_id,master_strains.name ORDER BY master_prices.strain_id");		        	
		        }	*/       
		    }
		}