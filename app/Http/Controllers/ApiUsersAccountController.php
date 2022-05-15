<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUsersAccountController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "users_account";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        if(isset($postdata["password"]) && $postdata["password"]!="")
		        {
		        	$postdata["password"] = Hash::make($postdata["password"]);
		        }	

		        //print_r($postdata["password"])	        ;exit;

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        $existedUser = DB::table($this->table)
		                      ->where('id', $postdata["id"])
		                      ->first();
				$result['data'] = $existedUser;

		    }

		}