<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Storage;
		use File;

		class ApiRemoveUserImageController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "remove_user_image";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		        $user = DB::table("cms_users")->select("photo")->where("id",$postdata["id"])->first();

		        if(Storage::exists($user->photo)) {
		        	Storage::delete($user->photo);
		       	}

		        //print_r($user);exit;

		        $postdata["photo"] = "";

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		    	$user = DB::table("cms_users")->where("id",$postdata["id"])->first();
		    	$result["data"] = $user;
		    }

		}