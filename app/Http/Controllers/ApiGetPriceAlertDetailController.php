<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class ApiGetPriceAlertDetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

    function __construct() {    
		$this->table       = "master_users_price_alerts_detail";        
		$this->permalink   = "get_price_alert_detail";    
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
        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();

       // print_r($user);exit;

        if(isset($user)){

        	$user_id = $user->id;
            $cnt = 0;

            $alerts = DB::table("master_users_price_alerts")
                ->select("master_states.state as state_name","master_strains.reviews","master_strains.slug","master_search_avg_prices_history.avg_price","master_users_price_alerts.*",DB::raw("FORMAT(master_strains.ratings,2) as ratings"),DB::raw("(CASE WHEN master_strains.name IS NULL THEN 'Marijuana' ELSE master_strains.name END) as strain_name"),DB::raw("master_cities.city as city_name"),"master_cities.state_code",DB::raw("(SELECT AVG(low_price) FROM master_search_avg_prices_history WHERE city_id = master_users_price_alerts.city AND strain_id = master_users_price_alerts.strain AND mass_id = 1) as week_low_price"),DB::raw("(SELECT AVG(high_price) FROM master_search_avg_prices_history WHERE city_id = master_users_price_alerts.city AND strain_id = master_users_price_alerts.strain AND mass_id = 1) as week_high_price"))
                ->leftjoin('master_search_avg_prices_history', function($join){
                   $join->on('master_search_avg_prices_history.master_search_id', '=', 'master_users_price_alerts.set_price_id');
                   $join->on('master_search_avg_prices_history.id', '=', 'master_users_price_alerts.set_price_history_id');
                }) 
                ->leftjoin("master_cities","master_cities.id","master_users_price_alerts.city")
                ->leftJoin("master_strains","master_strains.id","master_users_price_alerts.strain")
                ->leftjoin("master_states","master_states.id","master_users_price_alerts.state")
                ->where("master_users_price_alerts.user_id",$user->id)
                ->orderBy("master_search_avg_prices_history.id","ASC")
                ->get();

              //  print_r($alerts);exit;

                foreach ($alerts as $key => $alert) {
                    
                    $finalData[$cnt]["alert_id"] = $alert->id;
                    $finalData[$cnt]["set_date"] = $alert->created_at;
                    $finalData[$cnt]["strain_id"] = $alert->strain;
                    $finalData[$cnt]["city_id"] = $alert->city;
                    $finalData[$cnt]["strain"] = $alert->strain_name;
                    $finalData[$cnt]["location"] = $alert->city_name.", ".$alert->state_code;
                    $finalData[$cnt]["set_price"] = number_format($alert->avg_price,2);
                    $finalData[$cnt]["week_low_price"] = number_format($alert->week_low_price,2);
                    $finalData[$cnt]["week_high_price"] = number_format($alert->week_high_price,2);
                    $finalData[$cnt]["target_price"] = "";
                    $finalData[$cnt]["target_action"] = "";
                    $finalData[$cnt]["type"] = 1;
                    $finalData[$cnt]["slug"] = $alert->slug;
                    $finalData[$cnt]["reviews"] = $alert->reviews;
                    $finalData[$cnt]["ratings"] = $alert->ratings;
                     $finalData[$cnt]["city"] =$alert->city_name;
                      $finalData[$cnt]["state_name"] =$alert->state_name;

                    $latest_price = DB::table("master_search_avg_prices_history")
                            ->select("id","avg_price")
                            ->where("master_search_id",$alert->set_price_id)
                            ->orderBy("master_search_avg_prices_history.id","DESC")
                            ->first();

                    

                    if($alert->set_price_history_id == $latest_price->id){
                        $different_price = 0;
                        $different_percent = 0;
                    }else{
                        //$latest_price->avg_price = 9.25;
                        $different_price = $latest_price->avg_price - $alert->avg_price;
                        if($different_price != 0){
                            $different_percent = ($different_price * 100) / $latest_price->avg_price;
                        }else{
                            $different_percent = 0;
                        }

                    }
                    $finalData[$cnt]["today_price"] = number_format($latest_price->avg_price,2);
                    $finalData[$cnt]["different_price"] = number_format($different_price,2);
                    $finalData[$cnt]["different_percent"] = number_format($different_percent,2);
                    $cnt++;
                }

                $target_alerts = DB::table("master_users_price_alerts_detail")
                ->select("master_strains.slug","master_states.state as state_name","master_search_avg_prices_history.avg_price","master_users_price_alerts_detail.*",DB::raw("(CASE WHEN master_strains.name IS NULL THEN 'Marijuana' ELSE master_strains.name END) as strain_name"),DB::raw("master_cities.city as city_name"),"master_cities.state_code",DB::raw("(SELECT AVG(low_price) FROM master_search_avg_prices_history WHERE city_id = master_users_price_alerts_detail.city AND strain_id = master_users_price_alerts_detail.strain AND mass_id = 1) as week_low_price"),DB::raw("(SELECT AVG(high_price) FROM master_search_avg_prices_history WHERE city_id = master_users_price_alerts_detail.city AND strain_id = master_users_price_alerts_detail.strain AND mass_id = 1) as week_high_price"))
                ->join('master_search_avg_prices_history', function($join){
                   $join->on('master_search_avg_prices_history.master_search_id', '=', 'master_users_price_alerts_detail.set_price_id');
                   $join->on('master_search_avg_prices_history.id', '=', 'master_users_price_alerts_detail.set_price_history_id');
                }) 
                ->leftjoin("master_cities","master_cities.id","master_users_price_alerts_detail.city")
                ->leftJoin("master_strains","master_strains.id","master_users_price_alerts_detail.strain")
                 ->leftjoin("master_states","master_states.id","master_users_price_alerts_detail.state")
                ->where("master_users_price_alerts_detail.user_id",$user->id)
                ->orderBy("master_search_avg_prices_history.id","ASC")
                ->get();

                foreach ($target_alerts as $key => $alert) {
                    
                    $finalData[$cnt]["alert_id"] = $alert->id;
                    $finalData[$cnt]["set_date"] = $alert->created_at;
                    $finalData[$cnt]["strain_id"] = $alert->strain_id;
                    $finalData[$cnt]["city_id"] = $alert->city_id;
                    $finalData[$cnt]["strain"] = $alert->strain_name;
                    $finalData[$cnt]["location"] = $alert->city_name.", ".$alert->state_code;
                    $finalData[$cnt]["set_price"] = number_format($alert->avg_price,2);
                    $finalData[$cnt]["target_price"] = number_format($alert->price,2);
                    $finalData[$cnt]["target_action"] = $alert->action;
                    $finalData[$cnt]["week_low_price"] = number_format($alert->week_low_price,2);
                    $finalData[$cnt]["week_high_price"] = number_format($alert->week_high_price,2);
                    $finalData[$cnt]["type"] = 2;
                    $finalData[$cnt]["city"] =$alert->city_name;
                      $finalData[$cnt]["state_name"] =$alert->state_name;
                      $finalData[$cnt]["slug"] = $alert->slug;

                    $latest_price = DB::table("master_search_avg_prices_history")
                            ->select("id","avg_price")
                            ->where("master_search_id",$alert->set_price_id)
                            ->orderBy("master_search_avg_prices_history.id","DESC")
                            ->first();

                    

                    if($alert->set_price_history_id == $latest_price->id){                    
                        $different_price = 0;
                        $different_percent = 0;
                    }else{
                        //$latest_price->avg_price = 9.25;
                        $different_price = $latest_price->avg_price - $alert->avg_price;
                        if($different_price != 0){
                            $different_percent = ($different_price * 100) / $latest_price->avg_price;
                        }else{
                            $different_percent = 0;
                        }

                    }
                    $finalData[$cnt]["today_price"] = number_format($latest_price->avg_price,2);
                    $finalData[$cnt]["different_price"] = number_format($different_price,2);
                    $finalData[$cnt]["different_percent"] = number_format($different_percent,2);
                    $cnt++;
                }

        	$result["data"] = $finalData;       	


        }

    }

}