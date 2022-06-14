<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiBusinesslistController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "businesslist";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		        //$result['data'] =$postdata['user_id'];
		        $first = 
		        $disp = DB::table('master_locations')
		                // ->leftJoin('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')
						// ->where('dispensaries_users.user_id',$postdata['user_id'])
						->select('master_locations.*',DB::raw('null as claim_status'))->get()->toArray();

			
				$disp = json_decode(json_encode($disp), true);
			
				//$total = (array)$disp;
				$dispArray = [];
				$dispCount = 0;
				if(!empty($disp)){

					foreach ($disp as &$value) {
						if(!empty($value['assign_user'])){
							$assignUserArray = unserialize($value['assign_user']);
							if(is_array($assignUserArray)){

								if(in_array($postdata['user_id'],$assignUserArray)){
									$dispArray[$dispCount] = $value;
									$assignedUsers =$assignUserArray;
									$assignedUsersNames = [];
									if(!empty($assignedUsers)){
										foreach($assignedUsers as $aKey => $aVal){
											$assignedUsersNames[$aKey] = DB::table('cms_users')->where('id',$aVal)->value('name');
										}
									}
									$dispArray[$dispCount]['contributors'] = $assignedUsersNames;
									$follow_count = DB::table('master_dispensary_followers')
									->where('master_dispensary_followers.dispansary_id',$value['id'])
									->count();
									$dispArray[$dispCount]['follow_count'] = $follow_count;
									$checkClaimStatus = DB::table('claim_listings')->where('listing_id',$value['id'])->first();
			
									$dispArray[$dispCount]['claim_status'] = !empty($checkClaimStatus) ? $checkClaimStatus->status : 'Unverified';
								}
							}
	
						}
						
						
						$dispCount++;
						// $disp[$key]->{"contributors"} = $assignedUsersNames;
					
						// $disp[$key]->{"follow_count"} = $follow_count;
					}
				}
		        $result['data'] = $dispArray;
				
				
		    }

		}