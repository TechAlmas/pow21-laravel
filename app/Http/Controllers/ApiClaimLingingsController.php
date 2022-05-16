<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiClaimLingingsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "claim_listings";        
				$this->permalink   = "claim_lingings";    
				$this->method_type = "post";
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
		    }

		}