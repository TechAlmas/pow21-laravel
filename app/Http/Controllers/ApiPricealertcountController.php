<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class ApiPricealertcountController extends \crocodicstudio\crudbooster\controllers\ApiController {

    function __construct() {    
		$this->table       = "master_users_price_alerts";        
		$this->permalink   = "pricealertcount";    
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

        $price_count = 0;

        if(isset($this->postdata["email"]) && !empty($this->postdata["email"])){

            $user = DB::table('cms_users')->where("email",$this->postdata["email"])->first();            

        	$alert = DB::table("master_users_price_alerts")
    			->select("master_search_avg_prices_history.id")
                ->join('master_search_avg_prices_history', function($join){
                   $join->on('master_search_avg_prices_history.master_search_id', '=', 'master_users_price_alerts.set_price_id');
                   $join->on('master_search_avg_prices_history.id', '>', 'master_users_price_alerts.set_price_history_id');
                }) 
    			->where("master_users_price_alerts.user_id",$user->id)
    			->get()
    			->count();

    		$alert_target = DB::table("master_users_price_alerts_detail")
    			->select("master_search_avg_prices_history.id")
                ->join('master_search_avg_prices_history', function($join){
                   $join->on('master_search_avg_prices_history.master_search_id', '=', 'master_users_price_alerts_detail.set_price_id');
                   $join->on('master_search_avg_prices_history.id', '>', 'master_users_price_alerts_detail.set_price_history_id');
                }) 
                ->where("master_users_price_alerts_detail.user_id",$user->id)
    			->get()
    			->count();

    		$price_count = $alert_target + $alert;


        }

        $result["price_alert_count"] = $price_count;

    }

}