<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetStrainAttributeInDetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_strains_attributes";        
				$this->permalink   = "get_strain_attribute_in_detail";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    	$query->limit(0);
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		        $details = DB::table("master_strains_attributes")
		        			->select("master_strains_attributes.*",DB::raw("REPLACE(master_attributes_sub.name,'/','-') as name"),DB::raw("CEILING(master_strains_attributes.value) as percent_value"),DB::raw("LOWER(REPLACE(master_attributes_sub.name,' ','-')) as slug"))
		        			->leftJoin('master_attributes_sub', 'master_attributes_sub.id', '=', 'master_strains_attributes.sub_attribute_id')
	        				->where("master_strains_attributes.strain_id",$postdata["strain_id"])
	        				->where("master_strains_attributes.attribute_id",$postdata["attribute_id"])
	        				->where("master_strains_attributes.value",">",0)
	        				->orderBy("master_strains_attributes.value","DESC")
	        				->get();
	        	$result["data"] = $details;
		    	//echo "<pre>"; print_r($details);exit;
		    }

		}