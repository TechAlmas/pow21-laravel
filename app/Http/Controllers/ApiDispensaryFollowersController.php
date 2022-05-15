<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDispensaryFollowersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_dispensary_followers";        
				$this->permalink   = "dispensary_followers";    
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
				$dispansary = DB::table('master_dispensary_followers')
									->join('cms_users','cms_users.id','master_dispensary_followers.user_id')
									->join('master_locations','master_locations.id','master_dispensary_followers.dispansary_id')
									->where('slug',$postdata['slug'])
									->select('master_locations.id as id',
												'master_locations.name as name',
												'master_locations.address as address',
												'master_locations.address2 as address2',
												'master_locations.city as city',
												'master_locations.state as state',
												'master_locations.zip_code as zip_code',
												'master_locations.country as country',
												'master_locations.website as website',
												'master_locations.email as email2',
												'master_locations.phone as phone',
												'master_locations.source as source',
												'master_locations.city_id as city_id',
												'master_locations.state_id as state_id',
												'master_locations.logoUrl as logoUrl',
												'master_locations.description as description',
												'master_locations.ratings as ratings',
												'master_locations.reviews as reviews',
												'master_locations.schedule as schedule',
												'master_locations.license_type as license_type',
												'master_locations.status as status',
												'master_dispensary_followers.created_at as created_at',
												'master_locations.updated_at as updated_at',
												'master_locations.slug as slug',
                                                'cms_users.id as user_id', 'cms_users.name as user_name','cms_users.gender as user_gender','cms_users.birthday as user_birthday','cms_users.photo as user_photo','cms_users.email as user_email',
									            'cms_users.password as user_password','cms_users.id_cms_privileges as user_id_cms_privileges','cms_users.remember_token as user_remember_token','cms_users.referral_id as user_referral_id','cms_users.referrer_id as user_referrer_id',
											    'cms_users.status as user_status','cms_users.country as user_country','cms_users.state as user_state','cms_users.city as user_city','cms_users.ip as user_ip','cms_users.is_updates as user_is_updates'
											     ,'cms_users.is_terms as user_is_terms','cms_users.is_age_validation as user_is_age_validation','cms_users.latitude as user_latitude','cms_users.longitude as user_longitude','cms_users.auto_state as user_auto_state')
									->get();		
				//dd($dispansary);
				$result['data'] = $dispansary;
				return $result;
		    }

		}