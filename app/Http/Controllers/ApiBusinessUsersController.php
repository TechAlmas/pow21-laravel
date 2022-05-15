<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiBusinessUsersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "business_users";    
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
			  	$where_sub = DB::table('master_locations')->select('master_locations.id')
					->join('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')
					->where('dispensaries_users.user_id',$postdata['user_id'])
					->get();
				$listings = array();
	  			foreach ($where_sub as $key => $value) {
	  				$listings[] = $value->id;
	  			}
			  	$disp = DB::table('cms_users')->join('dispensaries_users','cms_users.id', 'dispensaries_users.user_id')->whereIn('dispensaries_users.dispansary_id',$listings)->select('cms_users.*')->distinct()->get();
			  	// $disp = DB::table('cms_users')->join('dispensaries_users',function($join){
		  		// 	$join->on('cms_users.id', '=', 'dispensaries_users.user_id')
		  		// 	->whereIn('dispensaries_users.dispansary_id',function($query){
		  		// 		return $query->select('master_locations.id')
		  		// 				->from('master_locations')
		  		// 				->join('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')
		  		// 				->where('dispensaries_users.user_id',59);
		  		// 	});
		  		// })->get();
				$result['data'] = $disp;
		    }

		}