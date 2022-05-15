<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetmetadataController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "meta_data";        
				$this->permalink   = "getmetadata";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        if( $result['api_status'] == "0")
		        {
		        	$result = DB::table($this->table)
		                      ->where('url', '/')
		                      ->first();
		            $result->api_status="1";
				    $result->api_message="success";
				    $result->api_http = 200;
				    //api_http	200
		        }

		    }

		}