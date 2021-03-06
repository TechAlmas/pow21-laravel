<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster,File;
		use App\MasterLocation;

		class ApiDispensaryUpdateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "dispensary_update";    
				$this->method_type = "post";    
		    }
		
		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				//$file = $_FILES['file'];
				$this->files = $_FILES;
		        $this->postdata = $postdata;

				if(!empty($_FILES['file'])){
					$path = storage_path('app')."/uploads/store_images/";
					$filename =  $postdata['id'].'-'.$_FILES['file']['name'][0];
					$tmp_name = $_FILES['file']['tmp_name'][0];
					$destinationfile = $path.$filename;
					if(!File::isDirectory($path)){

						File::makeDirectory($path, 0777, true, true);
						
					}
					if(move_uploaded_file($tmp_name,$destinationfile)){
						$postdata['logoUrl'] = "/uploads/store_images/".$filename;
					}
					unset($postdata['file']);
				}else{
					//Delete the logo url from Database if user not uploaded logo.
					$checkIfLogoImageExists = DB::table($this->table)->where('id',!empty($postdata['id'] )? $postdata['id'] : 0 )->value('logoUrl');
					if(!empty($checkIfLogoImageExists)){
						$path = storage_path('app')."/uploads/store_images/";
						$filePath = $path.$checkIfLogoImageExists;
						if(file_exists($filePath)){
							unlink($filePath);
						}
						DB::table($this->table)->where('id',!empty($postdata['id'] )? $postdata['id'] : 0 )->update(['logoUrl' => '']);
					}
				}

		        if($_FILES['store_images']){
					$uploaded_files = array();
					if(!empty($postdata['id'])){
						$getStoreData = DB::table($this->table)->where('id',$postdata['id'])->first();
						if(!empty($getStoreData->store_images)){
							$storeImages = unserialize($getStoreData->store_images);
						}
					}
		        	for ($i=0; $i < count($_FILES['store_images']); $i++) {
						
						$path = storage_path('app')."/uploads/store_images/";
		        		$filename = $postdata['id'].'-'.$_FILES['store_images']['name'][$i];
		        		$tmp_name = $_FILES['store_images']['tmp_name'][$i];
		        		$destinationfile = $path.$filename;
						if(!File::isDirectory($path)){

							File::makeDirectory($path, 0777, true, true);
							
						}
						if(!empty($storeImages)){
							if(!in_array($filename,$storeImages)){
								if(move_uploaded_file($tmp_name,$destinationfile)){
									//$postdata['file'] = "uploads/dispensaries/".$filename;
									$uploaded_files[] = $filename;
								}
							}
						}else{
							if(move_uploaded_file($tmp_name,$destinationfile)){
								//$postdata['file'] = "uploads/dispensaries/".$filename;
								$uploaded_files[] = $filename;
							}
						}

						if(!empty($postdata['removed_images'])){

							foreach($postdata['removed_images'] as $imageVal){
								if(in_array($imageVal,$uploaded_files)){
									if (($key = array_search($imageVal, $uploaded_files)) !== false) {
										unset($uploaded_files[$key]);
										
									}
									
								}
							}
						}
		        		
		        		$postdata['store_images'] = serialize($uploaded_files);
		        	}
		        }
				// print_r($postdata['store_images']);die;

				if(!empty($postdata['id'])){
					$storeImages = [];
					$getStoreData = DB::table($this->table)->where('id',$postdata['id'])->first();
					if(!empty($getStoreData->store_images)){
						$storeImages = unserialize($getStoreData->store_images);
							if(!empty($postdata['removed_images'])){

								foreach($postdata['removed_images'] as $imageVal){
									if(in_array($imageVal,$storeImages)){
										if (($key = array_search($imageVal, $storeImages)) !== false) {
											unset($storeImages[$key]);
											$path = storage_path('app')."/uploads/store_images/";
											$filename = $postdata['id'].'-'.$imageVal;
											$filePath = $path.$filename;
											if(file_exists($filePath)){
												unlink($filePath);
											}
										}
										
									}
								}
							}
						
					}
					if(!empty($postdata['store_images'])){
						if(!empty($storeImages)){
							$storeImagesData = unserialize($postdata['store_images']);
							foreach($storeImages as $iVal){
								$storeImagesData[] = $iVal;
							}
							$postdata['store_images'] = serialize($storeImagesData);
						}
					}else{
						$postdata['store_images'] = serialize($storeImages);
					}
					unset($postdata['removed_images']);
				}

				if(!empty($postdata['store_meta'])){
					$postdata['store_meta'] = serialize($postdata['store_meta']);
				}else{
					$postdata['store_meta'] = '';
				}
				if(!empty($postdata['assign_user'])){
					$postdata['assign_user'] = serialize($postdata['assign_user']);
				}else{
					$postdata['assign_user'] = '';
				}

				
		        
		    }
			public function execute_api() {
				$posts = Request::all();
				if(empty($posts['type'])){
					return Parent::execute_api();
				}else{
					$result = [];
					$result['api_status'] = 0;
					$result['api_message'] = 'error';
					$result['api_http'] = 200;
					$result['data'] = [];
					$debug_mode_message = 'You are in debug mode !';
					if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
						$result['api_authorization'] = $debug_mode_message;

					}
					if(!empty($posts['type']) && $posts['type'] == 'delete' && !empty($posts['id'])){
						DB::table('master_locations')->where('id',$posts['id'])->delete();
						$result['api_message'] = 'success';
						$result['api_status'] = 1;
					}else if(!empty($posts['type']) && $posts['type'] == 'suspend' && !empty($posts['id'])){
						//Updating Current Status
						DB::table('master_locations')->where('id',$posts['id'])->update(['status' =>3 ]);
						$result['api_message'] = 'success';
						$result['api_status'] = 1;
					}else if(!empty($posts['type']) && $posts['type'] == 'resume' && !empty($posts['id'])){

						DB::table('master_locations')->where('id',$posts['id'])->update(['status' =>1 ]);

						$result['api_message'] = 'success';
						$result['api_status'] = 1;
					}
					return $result;
				}
			}
		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		       // $result['data'] = $postdata;
		        //return $result;
		       /* if($postdata['id'] == ''){
		        	$postdata['slug'] = str_slug($postdata['name']);
		        	$new_claim_request = MasterLocation::create($postdata);
					$result['data'] = $new_claim_request;
					return $result;
		        }*/
				
				if($postdata['id'] == '')
				{
					$postdata['slug'] = str_slug($postdata['name']);
		        	$new_claim_request = MasterLocation::create($postdata);
					$result['data'] = $new_claim_request;
					
		        }
				else
				{
					
					$update_disp = DB::table('master_locations')
				                      ->where('id',$postdata['id'])
									  ->first();
					$result['data'] = $update_disp;
				}
				
				// if(!empty($result['data']->id )){
				// 	DB::table('dispensaries_users')->where('dispansary_id',$result['data']->id )->delete();
				// 	if(!empty($result['data']->assign_user)){

				// 		$assignUserData = unserialize($result['data']->assign_user);
				// 		$updateUserData = [];
				// 		if(!empty($assignUserData)){
				// 			foreach($assignUserData as $assignUserKey => $assignUserVal){
				// 				$updateUserData[$assignUserKey]['user_id'] = $assignUserVal;
				// 				$updateUserData[$assignUserKey]['dispansary_id'] = $result['data']->id;
				// 			}
				// if(!empty($result['data']->id )){
				// 	// DB::table('dispensaries_users')->where('dispansary_id',$result['data']->id )->delete();
				// 	if(!empty($result['data']->assign_user)){

				// 		$assignUserData = unserialize($result['data']->assign_user);
				// 		$updateUserData = [];
				// 		if(!empty($assignUserData)){
				// 			foreach($assignUserData as $assignUserKey => $assignUserVal){
				// 				$updateUserData[$assignUserKey]['user_id'] = $assignUserVal;
				// 				$updateUserData[$assignUserKey]['dispansary_id'] = $result['data']->id;
				// 			}
							
				// 			if(!empty($updateUserData)){
				// 				DB::table('dispensaries_users')->insert($updateUserData);
				// 			}
				// 		}
				// 	}

					
				// }

				return $result;

		    }

		}