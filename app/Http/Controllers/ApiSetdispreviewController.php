<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSetdispreviewController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_reviews";        
				$this->permalink   = "setdispreview";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		       $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {
		    	if(!empty($result['id'])){
					$getResultData = DB::table($this->table.' as master_dispensary_review')->where('id',$result['id'])->select('master_dispensary_review.*',DB::raw('(SELECT count(id) from master_dispensary_reviews WHERE disp_id=master_dispensary_review.disp_id) as total_reviews'),DB::raw('(SELECT AVG(rating) from master_dispensary_reviews WHERE disp_id=master_dispensary_review.disp_id) as avg_rating'))->first();
					if(!empty($getResultData)){
						DB::table('master_locations')->where('id',$getResultData->disp_id)->update(['ratings'=>$getResultData->avg_rating , 'reviews'=>$getResultData->total_reviews ]);
					}
				}


		    }

		}