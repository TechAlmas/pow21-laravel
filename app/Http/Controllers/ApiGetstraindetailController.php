<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetstraindetailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_strains";        
				$this->permalink   = "getstraindetail";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {

		    	//$strainName = str_replace('_',' ',$postdata['name']);
		    	//$postdata['slug'] = $strainName;
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    	//DB::raw("CEILING(master_strains.reviews*96.5/100) as reviews_count")
		    	$query->select("*",DB::raw("FORMAT(master_strains.ratings,2) as ratings"),DB::raw("image as image"));
		    }

		    public function hook_after($postdata,&$result) {
		       $result['image'] = basename($result['image']);
		    	//echo "test";exit;
		       unset($result["source"]);
		       unset($result["description"]);
		       unset($result["created_at"]);
		       unset($result["updated_at"]);
		    }

		}