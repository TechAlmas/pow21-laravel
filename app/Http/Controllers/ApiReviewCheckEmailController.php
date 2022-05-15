<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiReviewCheckEmailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "review_check_email";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {

		    	//echo "HEllo"; exit();
		    	//print_r($postdata); exit();

		    	$count_data = DB::table('cms_users')->where('email',$postdata['email'])->count();

		    	//print_r($data); exit();

		    	$result["data"] = $count_data;

		        //This method will be execute after run the main process

		    }

		}