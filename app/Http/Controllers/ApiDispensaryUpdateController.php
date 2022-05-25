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
				$filename = $_FILES['file']['name'];
				$postdata['logoUrl'] = $filename;
				$this->files = $_FILES;
		        $this->postdata = $postdata;
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
				}
				if(!empty($postdata['assign_user'])){
					$postdata['assign_user'] = serialize($postdata['assign_user']);
				}
		        // unset($postdata['file']);
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

				if(!empty($result['data']->id && !empty($result['data']->assign_user))){
					$assignUserData = unserialize($result['data']->assign_user);
					$updateUserData = [];
					if(!empty($assignUserData)){
						foreach($assignUserData as $assignUserKey => $assignUserVal){
							$updateUserData[$assignUserKey]['user_id'] = $assignUserVal;
							$updateUserData[$assignUserKey]['dispansary_id'] = $result['data']->id;
						}
						if(!empty($updateUserData)){

							DB::table('dispensaries_users')->insert($updateUserData);
						}
					}

					
				}

				return $result;

		    }

		}