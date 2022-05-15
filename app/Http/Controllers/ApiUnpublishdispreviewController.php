<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUnpublishdispreviewController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_reviews";        
				$this->permalink   = "unpublishdispreview";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		    		$query->limit(0);
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {

		    	 DB::table("master_dispensary_reviews")->where('id',$postdata['id'])->update(['status'=>0]);
		        $result['data'] = 1;

		        //This method will be execute after run the main process

		    }

		}