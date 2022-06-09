<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		use Illuminate\Support\Facades\Hash;


		use GeoIp2\Database\Reader;

		class ApiClaimLingingsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "claim_listings";        
				$this->permalink   = "claim_lingings";    
				$this->method_type = "post";
				$this->user_token =uniqid(); 
				$this->claim_listing_with_signup = false;
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb');  
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        $this->files = $_FILES;
		        $this->postdata = $postdata;
		        if($_FILES['file']){
		        	$uploaded_files = array();
		        	for ($i=0; $i < count($_FILES['file']); $i++) {
		        		$path = storage_path('app')."/uploads/claims/";
		        		$filename = $postdata['listing_id'].'-'.$_FILES['file']['name'][$i];
		        		$tmp_name = $_FILES['file']['tmp_name'][$i];
		        		$destinationfile = $path.$filename;
		        		if(move_uploaded_file($tmp_name,$destinationfile)){
			        		//$postdata['file'] = "uploads/dispensaries/".$filename;
							$uploaded_files[] = $filename;
			        	}
		        		$postdata['files'] = serialize($uploaded_files);
		        	}
		        }
		        unset($postdata['file']);

				// User signup along with claim submit
				if(!empty($postdata['claim_listing_with_signup'])){
					$password= Hash::make($postdata["password"]);
					$postdata["remember_token"] = $this->user_token;

					$postdata["referrer_id"] = $this->user_token;


					$clientIp = Request::getClientIp(true);

					$record = $this->reader->city($clientIp);

					//print_r($record);exit;

				

					$country = $record->country->name;
					$state = $record->mostSpecificSubdivision->name;
					$city = $record->city->name;
					$latitude = $record->location->latitude;
					$longitude = $record->location->longitude;
					$ip = $record->traits->ipAddress;



				$user_id = DB::table("cms_users")->insertGetId(array("name"=>$postdata["first_name"]." ".$postdata["last_name"], "email"=>$postdata["e_mail"],"id_cms_privileges"=>$postdata["id_cms_privileges"],"status"=>"Active","password"=>Hash::make($password),"remember_token"=>$postdata["remember_token"],"referral_id"=>$postdata["referrer_id"],"country"=>$country,"state"=>$state,"city"=>$city,"ip"=>$ip,"latitude"=>$latitude,"longitude"=>$longitude ));

					$data = ['email'=>$postdata['e_mail'],'password'=>$password];
					if(isset($user_id) && !empty($user_id))
					{
						CRUDBooster::sendEmail(['to'=>$postdata['e_mail'],'data'=>$data,'template'=>'register_mail']);	

											
				    }	

					$this->claim_listing_with_signup = true;

					unset($postdata['password']);
					unset($postdata['is_updates']);
					unset($postdata['status']);
					unset($postdata['id_cms_privileges']);
					unset($postdata['referrer_id']);

					unset($postdata['claim_listing_with_signup']);

					unset($postdata['remember_token']);

				}

		    }
			public function execute_api(){
				$posts = Request::all();
				
				$result = [];
				$result['api_status'] = 0;
				$result['api_http'] = 401;
				$result['data'] = [];
				$debug_mode_message = 'You are in debug mode !';
				if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
					$result['api_authorization'] = $debug_mode_message;
				}
				if(!empty($posts['listing_id'])){

					if(!empty($posts['e_mail'])){
						$checkIfEmailExists = DB::table($this->table)->where('listing_id',$posts['listing_id'])->where('e_mail',$posts['e_mail'])->first();
						if(!empty($checkIfEmailExists)){
							$result['data']['message'] = "The email already exists";
							return response()->json($result, 200);
						}
					}

					$checkIfClaimAlreadyExists = DB::table($this->table)->where('listing_id',$posts['listing_id'])->first();
					if(!empty($checkIfClaimAlreadyExists)){
						$result['data']['message'] = "The claim request already exists for this store.";
						$result['data']['e_mail'] = $checkIfClaimAlreadyExists->e_mail;
						return response()->json($result, 200);
					}

				}else{
					$result['data']['message'] = "The listing id field is required";
					return response()->json($result, 200);
				}

				return Parent::execute_api();

				
			}

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				if(!empty($result['id'])){
					$getClaimData = DB::table($this->table)->where('id',$result['id'])->first();
					if(!empty($getClaimData)){
						$data = ['name'=>$getClaimData->first_name];

						if($this->claim_listing_with_signup ){
							CRUDBooster::sendEmail(['to'=>$getClaimData->e_mail,'data'=>$data,'template'=>'claim_listing']);	
						}else{

							CRUDBooster::sendEmail(['to'=>$getClaimData->e_mail,'data'=>$data,'template'=>'claim_listing_signup']);	
						}
						$getAdminEmail = DB::table('cms_users')->where('id_cms_privileges',1)->value('email');
						if(!empty($getAdminEmail)){

							//Send Email to Admin
							CRUDBooster::sendEmail(['to'=>$getAdminEmail,'data'=>$data,'template'=>'claim_listing_notify_admin']);
						}

					

					}
				}
		    }

		}