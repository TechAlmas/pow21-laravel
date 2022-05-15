<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
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
				$this->postdata = $postdata;
				
		    	//if($_FILES['file']){
		        	//$part = storage_path('app')."/uploads/dispensaries/";
		        	//$filename = $_FILES['file']['name'];
					//$tmp_name = $_FILES['file']['tmp_name'];
		        	//$destinationfile = $part.$filename;
					//$allowedExt = array("jpg","jpeg","png","pdf");
		        	/*if(move_uploaded_file($tmp_name,$destinationfile)){
		        		//$postdata['file'] = "uploads/dispensaries/".$filename;
						$files = DB::table('master_locations')
									   ->where('logoUrl',$postdata['logoUrl']);
										
		        	}*/
		        //}
		        //unset($postdata['file']);
		        //unset($_FILES);
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
					return $result;
		        }
				else
				{
					$update_disp = DB::table('master_locations')
				                      ->where('id',$postdata['id'])
									  ->first();
					$result['data'] = $update_disp;
					return $result;
				}

		    }

		}