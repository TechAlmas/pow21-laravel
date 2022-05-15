<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetUserStrainReviewsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_reviews";        
				$this->permalink   = "get_user_strain_reviews";    
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
		        
		        $user_id = $postdata["user_id"];

		        $reviews = DB::table("master_users_reviews")
		        			->select("master_users_reviews.*",DB::raw("master_strains.name as strain_name"))
		        			->leftjoin("master_strains","master_strains.id","=","master_users_reviews.strain_id")
		        			->where("master_users_reviews.user_id",$user_id)
		        			//->where("master_users_reviews.status",1)
		        			->get();
		       // echo "<pre>"; print_r($reviews);exit;
		        $result["data"] = $reviews;


		    }

		}