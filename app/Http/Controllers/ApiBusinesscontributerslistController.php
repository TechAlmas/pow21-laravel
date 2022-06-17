<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiBusinesscontributerslistController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "businesscontributerslist";    
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
				$getUserData = [];
				if(!empty($postdata['id'] && $postdata['id'] != 'add')){
					$getUserData = DB::table('cms_users')->where('id',$postdata['id'])->first();
					if(!empty($getUserData)){
						$getName = explode(' ',$getUserData->name); 
						$getUserData->first_name = !empty($getName[0]) ? $getName[0] : '';
						$getUserData->last_name = !empty($getName[1]) ? $getName[1] : '';
					}

				}
				else if(!empty($postdata['user_id']) && $postdata['id'] != 'add'){
					$getUserData = DB::table('cms_users')->where('parent_id',$postdata['user_id'])->get();
					if($getUserData->isNotEmpty()){
						foreach($getUserData as $uVal){
							$disp = DB::table('master_locations')->get()->toArray();
							$disp = json_decode(json_encode($disp), true);
							$dispArray = [];
							$dispCount = 0;
							if(!empty($disp)){

								foreach ($disp as $value) {
									if(!empty($value['assign_user'])){

										$assignUserArray = unserialize($value['assign_user']);
										if(is_array($assignUserArray)){

											if(in_array($uVal->id,$assignUserArray)){

												$dispArray[$dispCount] = $value['name'];
												
											}
										}
									
									}
									
									$dispCount++;
									
								}
							
							}
							$uVal->retail_store = $dispArray;
							$uVal->created_at = date('d-m-Y',strtotime($uVal->created_at));
							$uVal->updated_at = date('d-m-Y',strtotime($uVal->updated_at));
						}
						
					}
				}
				$result['data'] = $getUserData;

				if(!empty($postdata['type'] && $postdata['type'] == 'add_edit' )){
					$disp = DB::table('master_locations')->where('status',0)->orWhere('status',3)->get()->toArray();
					$disp = json_decode(json_encode($disp), true);
				
					//$total = (array)$disp;
					$dispArray = [];
					$selectedStores = [];
					$dispCount = 0;
					if(!empty($disp)){

						foreach ($disp as $value) {
							if(!empty($value['assign_user'])){

								$assignUserArray = unserialize($value['assign_user']);
								if(is_array($assignUserArray)){

									if(in_array($postdata['user_id'],$assignUserArray)){

										$dispArray[$dispCount]['name'] = $value['name'];
										$dispArray[$dispCount]['id'] = $value['id'];
										
									}
									if(!empty($getUserData)){
	

										if(in_array($getUserData->id,$assignUserArray)){

											$selectedStores[$dispCount] =  $value['id'];
										}
									}
								}

							}
							
							$dispCount++;
							
						}
					
					}
					if(!empty($getUserData)){
						$getUserData->retail_store = $selectedStores;
					}
					$result['retail_store_dropdown'] = $dispArray;
				}

				

				return $result;
		    }

		}