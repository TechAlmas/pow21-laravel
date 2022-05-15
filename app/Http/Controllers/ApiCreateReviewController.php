<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use GeoIp2\Database\Reader;

		class ApiCreateReviewController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_users_reviews";        
				$this->permalink   = "create_review";    
				$this->method_type = "post"; 
				$this->user_token =uniqid(); 
				$this->user_id = 0;
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');   
		    }
		

		    public function hook_before(&$postdata) {

		    	//$password = '123456';
		    	//$postdata["password"] = Hash::make($password);
		    	$postdata["remember_token"] = $this->user_token;
		    	$postdata["referral_id"] = $this->user_token;

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
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    	//$query->limit(0);
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
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

		        $new_data = array();
	        	$new_data["user_id"] = $user_id;
	        	$new_data["strain_id"] = $postdata["strain_id"];
	        	$new_data["rating"] = $postdata["rating"];
	        	$new_data["rating_apperance"] = $postdata["rating_apperance"];
	        	$new_data["rating_aroma"] = $postdata["rating_aroma"];
	        	$new_data["rating_high"] = $postdata["rating_high"];
	        	$new_data["rating_quality"] = $postdata["rating_quality"];
	        	$new_data["rating_flavor"] = $postdata["rating_flavor"];
	        	$new_data["rating_value"] = $postdata["rating_value"];
	        	$new_data["title"] = $postdata["title"];
	        	$new_data["message"] = $postdata["message"];
	        	$new_data["status"] = 0;

	        	$id = DB::table("master_users_reviews")->insertGetId($new_data);

	        	//DB::statement("UPDATE master_strains SET ratings = CASE WHEN ratings IS NULL THEN '".$postdata["rating"]."' ELSE (ratings + ".$postdata["rating"].")/2 END WHERE id = ".$postdata["strain_id"]);

	        	//DB::statement("UPDATE master_strains SET reviews = reviews + 1 WHERE id = ".$postdata["strain_id"]);

		        $result["id"] = $id;

		    }

		}