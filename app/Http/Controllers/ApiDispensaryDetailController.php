<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDispensaryDetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_locations";        
				$this->permalink   = "dispensary_detail";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		      //$query->select("*",DB::raw("FORMAT(master_locations.ratings,2) as ratings"),DB::raw("image as image"));

		    	$query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {
		    	
		    	$disp = DB::table('master_locations')
		    			->join('master_states','master_states.id','master_locations.state_id')
		    			->join('master_cities','master_cities.id','master_locations.city_id')
		    			->leftJoin('master_dispensary_followers', 'master_locations.id', '=', 'master_dispensary_followers.dispansary_id')
						->where('slug',$postdata['slug'])
		    			->select("master_locations.*",DB::raw("FORMAT(master_locations.ratings,2) as ratings"),'master_states.state as state_name_table','master_cities.city as city_name_table','master_locations.id as disp_id',DB::raw("count(master_dispensary_followers.id) as follow_count"))
		    			->first();
				
				if(!empty($disp))
				{
					$count = DB::table('master_dispensary_followers')
								  ->where('user_id',$postdata['user_id']) 
								  ->where('dispansary_id',$disp->disp_id) 
					              ->count('id');
						
					if($count > 0)
					{
						$disp->follow_status = true;
					}
					else
					{
						$disp->follow_status = false;
					}
				}
				
		    	$result['data'] = $disp;

		    	// /unset($result["source"]);
		        //This method will be execute after run the main process

		    }

		}