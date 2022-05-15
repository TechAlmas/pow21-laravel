<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\User;

		class ApiUserUpdateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "user_update";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				/*if($_FILES['photo']){
		        	$part = storage_path('app')."/uploads/users/";
		        	$filename = $_FILES['photo']['name'];
		        	$destinationfile = $part.$filename;
		        	if(move_uploaded_file($_FILES['photo']['tmp_name'], $destinationfile)){
		        		$postdata['logoUrl'] = "uploads/users/".$filename;
		        	}
		        	unset($postdata['photo']);
		        	unset($_FILES);
		        }*/
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				
				if($postdata['id'] == '')
				{
					//$postdata['status'] = 'inactive';
		        	$new_user         = User::create($postdata);
					$result['data']   = $new_user;
					return $result;
		        }
				else
				{
					$update_user = DB::table('cms_users')
				                      ->where('id',$postdata['id'])
									  ->first();
					//dd($user_update);
					$result['data'] = $update_user;
					return $result;
				}

		    }

		}