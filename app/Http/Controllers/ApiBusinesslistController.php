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
		                ->join('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')
						->where('dispensaries_users.user_id',$postdata['user_id'])
						->get();
				//$total = (array)$disp;
				foreach ($disp as $key => $value) {
					$contributors = DB::table('dispensaries_users')
									->join('cms_users','dispensaries_users.user_id','cms_users.id')
									->where('dispensaries_users.dispansary_id',$value->dispansary_id)
									->get();
					$disp[$key]->{"contributors"} = $contributors;
					$follow_count = DB::table('master_dispensary_followers')
									->where('master_dispensary_followers.dispansary_id',$value->dispansary_id)
									->count();
					$disp[$key]->{"follow_count"} = $follow_count;
				}
		        $result['data'] = $disp;
				
		    }

		}