<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetstrainreviewController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_reviews";        
				$this->permalink   = "getstrainreview";    
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

		    	//print_r($postdata); exit();

		    	$data = DB::table("master_users_reviews")
		    		->join("master_strains",'master_strains.id','master_users_reviews.strain_id')
		    		->select("master_users_reviews.*","master_strains.name as strain_name")

		    		->where("master_users_reviews.id",$postdata['id'])
		    		->first();

		    		$result['data'] = $data;

		        //This method will be execute after run the main process

		    }

		}