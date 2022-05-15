<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;
use App\Claim;

class ApiClaimListingController extends \crocodicstudio\crudbooster\controllers\ApiController
{
	
	function __construct() {    
		$this->table       = "claim_listings";        
		$this->permalink   = "claim_listing";    
		$this->method_type = "post"; 
   
	}
	
	public function hook_before(&$postdata) {
		//This method will be execute before run the main process
	
	}

	public function hook_query(&$query) {
		//This method is to customize the sql query
	}

	public function hook_after($postdata,&$result) {
		//This method will be execute after run the main process
		$new_claim_request = Claim::create($postdata);
		$result['data'] = $new_claim_request;
		return $result;
	}
}
