<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetstatenameController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_cities";        
				$this->permalink   = "getstatename";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    	//$query->limit(0);
		    }

		    public function hook_after($postdata,&$result) {
		    	//print_r($postdata); exit();
		        //This method will be execute after run the main process

		    }

		}