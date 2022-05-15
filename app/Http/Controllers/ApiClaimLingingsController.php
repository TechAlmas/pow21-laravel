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

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		    }

		}