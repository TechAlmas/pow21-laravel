<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Session;
use Request;
use DB;
use CRUDBooster;
use App\User;
use GeoIp2\Database\Reader;

		class ApiBusinessContributersUpdateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "business_contributers_update";    
				$this->method_type = "post";  
				$this->retail_store = [];  
				$this->user_token =uniqid(); 
				$this->reader = new Reader('/home/miopro/public_html/admin/vendor/geoip2/geoip2/maxmind-db/GeoIP2-City.mmdb'); 
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$this->postdata = $postdata;
				if(!empty($postdata['retail_store']) && is_array($postdata['retail_store'])){
					$this->retail_store = $postdata['retail_store'];
				}
				


				$postdata["remember_token"] = $this->user_token;

				$postdata["referrer_id"] = $this->user_token;

				$clientIp = Request::getClientIp(true);

				$record = $this->reader->city($clientIp);
				$postdata['country'] = $record->country->name;
				$postdata['state'] = $record->mostSpecificSubdivision->name;
				$postdata['city'] = $record->city->name;
				$postdata['latitude'] = $record->location->latitude;
				$postdata['longitude'] = $record->location->longitude;
				$postdata['ip'] = $record->traits->ipAddress;
				if(!empty($postdata['type'])){
					$this->type = $postdata['type'];
				}

				unset($postdata['first_name']);
				unset($postdata['last_name']);
				unset($postdata['type']);
				unset($postdata['retail_store']);
			
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
				

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				if(!empty($this->type) && $this->type == 'delete'){
					DB::table('cms_users')->where('id',$postdata['id'])->delete();
					return $result;
				}
				if(!empty($postdata['id']) ){
					$updatedData = DB::table($this->table)
				                      ->where('id',$postdata['id'])
									  ->first();
					$result['data'] = $updatedData;
				}else{
					$insertData = User::create($postdata);
					$result['data'] = $insertData;
					
				}

				if(!empty($result['data']->id)){
					$password = mt_rand(111111,999999);
					$password = Hash::make($password);
					DB::table("cms_users")->where("id",$result['data']->id)->update(['password' => $password]);
					
						//removing the assigned user and reassigning it again.
						$getDispData = DB::table('master_locations')->get()->toArray();
						$getDispData = json_decode(json_encode($getDispData), true);
						if(!empty($getDispData)){
							foreach($getDispData as $dispVal){
								$assign_user = !empty($dispVal['assign_user']) ? unserialize($dispVal['assign_user']) : [];
								if($key = array_search($result['data']->id, $assign_user) ){

									unset($assign_user[$key]);
								}
								DB::table("master_locations")->where("id",$dispVal['id'])->update(['assign_user' => serialize($assign_user)]);

							}
						}
					if(!empty($this->retail_store)){
						foreach($this->retail_store as $storeVal){
							$getStoreAssignedUsers = DB::table('master_locations')->where('id',$storeVal)->value('assign_user');
							if(!empty($getStoreAssignedUsers)){
								$getStoreAssignedUsers = unserialize($getStoreAssignedUsers);
								array_push($getStoreAssignedUsers,$result['data']->id);
								$getStoreAssignedUsers = serialize($getStoreAssignedUsers);
								DB::table('master_locations')->where('id',$storeVal)->update(['assign_user' => $getStoreAssignedUsers]);
							}
						}
					}
				}
				return $result;

					
		    }

		}