<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDispensaryFollowController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_followers";        
				$this->permalink   = "dispensary_follow";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$this->postdata = $postdata;
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				$exists = DB::table('master_dispensary_followers')
						//->where('user_id', $postdata['user_id'])
						->where('dispansary_id', $postdata['dispansary_id'])
						->first();
				// $result['data'] = $follow;
				// return $result;
				if($exists){
					DB::table('master_dispensary_followers')
					->where('id',$exists->id)
					->delete();
					$result['data'] = "unfollow";
				    return $result;
				}
				else
				{
					$follow = DB::table('master_dispensary_followers')
									->insert($postdata);
					
				    $result['data'] = "follow";
				    return $result;
				}
		    }

		}