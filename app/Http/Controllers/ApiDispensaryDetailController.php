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
		    			->select("master_locations.*",DB::raw("FORMAT(master_locations.ratings,2) as ratings"),'master_states.state as state_name_table','master_cities.city as city_name_table','master_locations.id as disp_id',DB::raw("count(master_dispensary_followers.id) as follow_count"),DB::raw('null as claim_status'))
		    			->first();
				
				if(!empty($disp))
				{
					$checkClaimStatus = DB::table('claim_listings')->where('listing_id',$disp->id)->first();
					
					$disp->claim_status = !empty($checkClaimStatus) ? $checkClaimStatus->status : 'Unverified';

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
					if(!empty($disp->store_images)){
						$disp->store_images = unserialize($disp->store_images);
						$storeImagesData = [];
						if(!empty($disp->store_images)){
							foreach($disp->store_images as $imageKey =>  $imageVal){
								$imageUrl = asset('/uploads/store_images/'.$imageVal);
								$storeImagesData[$imageKey]['name'] = $imageVal; 
								$storeImagesData[$imageKey]['link'] = $imageUrl;
							}
						}
						$disp->store_images = $storeImagesData;
					}
					$disp->store_meta_dropdown = DB::table('store_meta')->select('title','id')->get()->toArray();
					if(!empty($disp->store_meta)){
						$disp->store_meta = unserialize($disp->store_meta);
					}
					$disp->assign_user_dropdown = DB::table('dispensaries_users')->leftJoin('cms_users','cms_users.id','dispensaries_users.user_id')->where('dispensaries_users.dispansary_id',$disp->id)->select('cms_users.name','cms_users.id')->groupBy('cms_users.id')->get()->toArray();
					if(!empty($disp->assign_user)){
						$disp->assign_user = unserialize($disp->assign_user);
					}
				}
				
		    	$result['data'] = $disp;

		    	// /unset($result["source"]);
		        //This method will be execute after run the main process

		    }

		}