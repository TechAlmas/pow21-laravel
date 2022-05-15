<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUserloginController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "userlogin";    
				$this->method_type = "post";  
				$this->user_token =uniqid();   
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		     

		    }

		    public function hook_query(&$query) {
		    	
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		    	// echo "<pre>"; print_r($postdata);exit;
		        //This method will be execute after run the main process
		     //print $this->user_token;
		        
		      $existedUser = DB::table($this->table)
                              ->where('id', $result["id"])
                              ->update(array('remember_token'=>$this->user_token));
              $result["remember_token"] = $this->user_token; 

              //print_r($result);
		        
		    }

		}