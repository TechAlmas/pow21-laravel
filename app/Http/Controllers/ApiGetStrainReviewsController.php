<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetStrainReviewsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_reviews";        
				$this->permalink   = "get_strain_reviews";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        //$query->select("created_at");
		      //  $query->join('cms_users', 'cms_users.id', '=', 'master_users_reviews.user_id');
		       // $query->join("cms_users","cms_users.id","=","master_users_reviews.user_id")
		        $query->where("status",1);

		    }

		    public function hook_after($postdata,&$result) {
		    	//echo "Hello"; exit();
		        //This method will be execute after run the main process

		    }

		}