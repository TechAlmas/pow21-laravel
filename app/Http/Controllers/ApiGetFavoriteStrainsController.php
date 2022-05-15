<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetFavoriteStrainsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_favorite_strains";        
				$this->permalink   = "get_favorite_strains";    
				$this->method_type = "get";    
				$this->postdata  = array();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		    	$this->postdata = $postdata;
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		        $query->limit(0);
		        

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        $finalData = array();
        		$user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();

        		//print_r($user); exit();

        		if(isset($user)){

		        	//echo $user_id = $user->id; exit;

		            $cnt = 0;

		            $alerts = DB::table("master_users_favorite_strains")
		                ->select("master_states.state as state_name","master_strains.slug","master_strains.reviews",DB::raw("FORMAT(master_strains.ratings,2) as ratings")/*,"master_search_avg_prices.avg_price","master_search_avg_prices.differ_price","master_search_avg_prices.differ_percent"*/,"master_users_favorite_strains.*",DB::raw("(CASE WHEN master_strains.name IS NULL THEN 'Marijuana' ELSE master_strains.name END) as strain_name"),DB::raw("master_cities.city as city_name"),"master_cities.state_code",DB::raw("(SELECT AVG(low_price) FROM master_search_avg_prices_history WHERE city_id = master_users_favorite_strains.city AND strain_id = master_users_favorite_strains.strain AND mass_id = 1) as week_low_price"),DB::raw("(SELECT AVG(high_price) FROM master_search_avg_prices_history WHERE city_id = master_users_favorite_strains.city AND strain_id = master_users_favorite_strains.strain AND mass_id = 1) as week_high_price"))
		                /*->leftjoin('master_search_avg_prices', function($join){
		                   $join->on("master_search_avg_prices.city_id","=","master_users_favorite_strains.city");
        				   $join->on("master_search_avg_prices.strain_id","=","master_users_favorite_strains.strain");
        				   $join->on("master_search_avg_prices.mass_id","=","master_users_favorite_strains.mass");
		                })*/ 
		                ->leftjoin("master_cities","master_cities.id","master_users_favorite_strains.city")
		                ->leftJoin("master_strains","master_strains.id","master_users_favorite_strains.strain")
		                ->leftjoin("master_states","master_states.id","master_users_favorite_strains.state")
		                ->where("master_users_favorite_strains.user_id",$user->id)
		                //->where("master_search_avg_prices.mass_id",1)
		               // ->groupBy("master_search_avg_prices.city_id","master_search_avg_prices.strain_id","master_search_avg_prices.mass_id")
		                ->get();

		               //print_r($alerts);exit;

		               // print_r($alerts);exit;

		               /* foreach ($alerts as $key => $alert) {
		                    
		                    $finalData[$cnt]["alert_id"] = $alert->id;
		                    $finalData[$cnt]["set_date"] = $alert->created_at;
		                    $finalData[$cnt]["strain_id"] = $alert->strain;
		                    $finalData[$cnt]["city_id"] = $alert->city;
		                    $finalData[$cnt]["strain"] = $alert->strain_name;
		                    $finalData[$cnt]["location"] = $alert->city_name.", ".$alert->state_code;
		                    $finalData[$cnt]["week_low_price"] = number_format($alert->week_low_price,2);
		                    $finalData[$cnt]["week_high_price"] = number_format($alert->week_high_price,2);		                   
		                    $finalData[$cnt]["today_price"] = number_format($latest_price->avg_price,2);
		                    $finalData[$cnt]["different_price"] = number_format($different_price,2);
		                    $finalData[$cnt]["different_percent"] = number_format($different_percent,2);
		                    $cnt++;
		                }*/



		        }

		        $result["data"] = $alerts;  
        		//exit;

		        /*if(isset($this->postdata["email"]) && !empty($this->postdata["email"])){	
		        	$query = DB::table("master_users_price_alerts");
		        	$query->select("master_users_price_alerts.*","master_search_avg_prices.*","master_cities.city","master_cities.state_code",DB::raw("master_strains.name as strain_name"),DB::raw("master_users_price_alerts.id as fav_id"));	        	
		        	$query->leftjoin("master_cities","master_cities.id","master_users_price_alerts.city");
        			$query->leftJoin("master_strains","master_strains.id","master_users_price_alerts.strain");
		        	$query->leftJoin('cms_users', 'cms_users.id', '=', 'master_users_price_alerts.user_id');
		        	$query->leftJoin("master_search_avg_prices", function($join){
        					$join->on("master_search_avg_prices.city_id","=","master_users_price_alerts.city");
        					$join->on("master_search_avg_prices.strain_id","=","master_users_price_alerts.strain");
        				});
		        	$query->where("master_search_avg_prices.mass_id",1);
		        	$query->where("cms_users.email",$this->postdata["email"]);
		        	$result["data"] = $query->get();
		        }*/

		    }

		}