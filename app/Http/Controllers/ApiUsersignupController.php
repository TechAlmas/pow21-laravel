<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use GeoIp2\Database\Reader;

		class ApiUsersignupController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "usersignup";    
				$this->method_type = "post";   
				$this->user_token =uniqid(); 
				$this->user_id = 0;
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');
		    }
		
			

		    public function hook_before(&$postdata) {
		    

				$this->user_id = $postdata["user_id"];
		    	$postdata["password"] = Hash::make($postdata["password"]);
		    	$postdata["remember_token"] = $this->user_token;
		    	$postdata["referral_id"] = $this->user_token;

		    	$clientIp = Request::getClientIp(true);

		        $record = $this->reader->city($clientIp);

		        //print_r($record);exit;

		       

		        $postdata["country"] = $record->country->name;
		        $postdata["state"] = $record->mostSpecificSubdivision->name;
		        $postdata["city"] = $record->city->name;
		        $postdata["latitude"] = $record->location->latitude;
		        $postdata["longitude"] = $record->location->longitude;
		        $postdata["ip"] = $record->traits->ipAddress;
		        unset($postdata["is_terms"]);
		        unset($postdata["user_id"]);
		    	
		       // echo "<pre>"; print_r($postdata);exit;
		       
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

			$existedDB = DB::table($this->table)
                              ->where('email', $postdata["email"])
			      ->where('id','!=', $result["id"])
                              ->first();

			 if ($existedDB != null && isset($existedDB)) {


			 	if($existedDB->id_cms_privileges=="3")
			 	{
			 		DB::table($this->table)->where('id', $existedDB->id)->delete();
			 		$existedUser = DB::table($this->table)
                              ->where('id', $result["id"])
                              ->update(array('id'=>$existedDB->id));

                    $newUser = DB::table($this->table)
		                      ->where('id', $existedDB->id)
		                      ->first();
					$result['data'] = $newUser;

                    $result['api_status']="1";

                    DB::table("master_users_paid_for")->where("user_id",$this->user_id)->update(array("user_id"=>$newUser->id));
		        	DB::table("cms_logs")->where("id_cms_users",$this->user_id)->update(array("id_cms_users"=>$newUser->id));
				    
			 	}else
			 	{
			 		
			 		DB::table($this->table)->where('id', $result["id"])->delete();
			 		$result['api_status']="0";
				    $result['api_message']="Email address already Exist!";
			 	}		
								
				
			}else
			{

				$existedUser = DB::table($this->table)
		                      ->where('id', $result["id"])
		                      ->first();
				$result['data'] = $existedUser;

				DB::table("master_users_paid_for")->where("user_id",$this->user_id)->update(array("user_id"=>$existedUser->id));
		        	DB::table("cms_logs")->where("id_cms_users",$this->user_id)->update(array("id_cms_users"=>$existedUser->id));
			}
		    	//print_r($result);exit;
		    }

		}
