<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetSocialPriceController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_social_prices";        
				$this->permalink   = "get_social_price";    
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
		        $socialData = array();
		        if(isset($postdata["city"]) && !empty($postdata["city"])){
		        	$social = DB::table("master_social_prices")
		        		->where("city_id",$postdata["city"])
		        		->orderBy("id","DESC")
		        		->limit(2)
		        		->get();

		        	if($social->count() > 1){
		        		$differnt_price = $social[0]->price - $social[1]->price;
		        		if($social[1]->price != 0){
		        			$differnt_percent = $differnt_price / $social[1]->price;
		        		}else{
		        			$differnt_percent = 0;
		        		}
		        		$socialData["price"] = $social[0]->price;
		        		$socialData["price_different"] = number_format($differnt_price,2);
		        		$socialData["price_different_percent"] = number_format($differnt_percent,2);

		        	}else{
		        		$socialData["price"] = $social[0]->price;
		        		$socialData["price_different"] = "0.00";
		        		$socialData["price_different_percent"] = "0";
		        	}

		        	//print_r($social);exit;
		        }
		        $result["data"] = $socialData;

		    }

		}