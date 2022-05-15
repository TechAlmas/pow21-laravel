<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetUserDispensaryReviewsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_reviews";        
				$this->permalink   = "get_user_dispensary_reviews";    
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
		    	$user_id = $postdata["user_id"];
		    	$reviews = DB::table("master_dispensary_reviews")
		        			->select("master_dispensary_reviews.*",DB::raw("master_locations.name as disp_name"))
		        			->leftjoin("master_locations","master_locations.id","=","master_dispensary_reviews.disp_id")
		        			->where("master_dispensary_reviews.user_id",$user_id)
		        			->get();
		        $result["data"] = $reviews;
		        //This method will be execute after run the main process

		    }

		}