<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Session;
use Request;
use DB;
use CRUDBooster;
use GeoIp2\Database\Reader;
		class ApiSetdispreviewController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_reviews";        
				$this->permalink   = "setdispreview";    
				$this->method_type = "post";    
				$this->user_token =uniqid(); 
				$this->user_id = 0;
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				// $postdata["remember_token"] = $this->user_token;
		    	// $postdata["referral_id"] = $this->user_token;
				
				$clientIp = Request::getClientIp(true);

		        $record = $this->reader->city($clientIp);
				$postdata["country"] = $record->country->name;
		        $postdata["state"] = $record->mostSpecificSubdivision->name;
		        $postdata["city"] = $record->city->name;
		        $postdata["latitude"] = $record->location->latitude;
		        $postdata["longitude"] = $record->location->longitude;
		        $postdata["ip"] = $record->traits->ipAddress;
		        unset($postdata["is_terms"]);
		        unset($postdata["user_id"]);

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		       $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {
				$password = mt_rand(111111,999999);
		    	
		        $user = DB::table("cms_users")->select("id")->where("email",$postdata["email"])->first();

				if(isset($user)){
		        	$user_id = $user->id;
		        }else{

		        	$user_id = DB::table("cms_users")->insertGetId(array("name"=>$postdata["name"], "email"=>$postdata["email"],"id_cms_privileges"=>4,"status"=>"Active","password"=>Hash::make($password),"remember_token"=>$postdata["remember_token"],"referral_id"=>$postdata["referral_id"],"country"=>$postdata["country"],"state"=>$postdata["state"],"city"=>$postdata["city"],"ip"=>$postdata["ip"],"latitude"=>$postdata["latitude"],"longitude"=>$postdata["longitude"]));

		        	 $data = ['email'=>$postdata['email'],'password'=>$password];

					if(isset($user_id) && !empty($user_id))
					{
						CRUDBooster::sendEmail(['to'=>$postdata['email'],'data'=>$data,'template'=>'register_mail']);	
											
				    }			        	
		        } 

				DB::table("master_users_paid_for")->where("user_id",$postdata["user_id"])->update(array("user_id"=>$user_id));
		        DB::table("cms_logs")->where("id_cms_users",$postdata["user_id"])->update(array("id_cms_users"=>$user_id));

				$result["user_id"] = $user_id;
				$getUserData = DB::table("cms_users")->where("id",$user_id)->first();
				$new_data = array();
	        	$new_data["user_id"] = $user_id;
	        	$new_data["disp_id"] = $postdata["disp_id"];
	        	$new_data["rating"] = $postdata["rating"];
	        	$new_data["title"] = $postdata["title"];
	        	$new_data["message"] = $postdata["message"];
	        	$new_data["status"] = 0;
				$new_data["email"] = $getUserData->email;
	        	$new_data["name"] = $getUserData->name;

	        	$id = DB::table("master_dispensary_reviews")->insertGetId($new_data);

				$result['api_status'] = 1;
				$result['api_message'] = 'success';
				$result["id"] = $id;

		    	// if(!empty($result['id'])){
				// 	$getResultData = DB::table($this->table.' as master_dispensary_review')->where('id',$result['id'])->select('master_dispensary_review.*',DB::raw('(SELECT count(id) from master_dispensary_reviews WHERE disp_id=master_dispensary_review.disp_id) as total_reviews'),DB::raw('(SELECT AVG(rating) from master_dispensary_reviews WHERE disp_id=master_dispensary_review.disp_id) as avg_rating'))->first();
				// 	if(!empty($getResultData)){
				// 		DB::table('master_locations')->where('id',$getResultData->disp_id)->update(['ratings'=>$getResultData->avg_rating , 'reviews'=>$getResultData->total_reviews ]);
				// 	}
				// }


		    }

		}