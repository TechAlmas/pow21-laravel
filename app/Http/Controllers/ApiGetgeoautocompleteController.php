<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetgeoautocompleteController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "master_geo";        
				$this->permalink   = "getgeoautocomplete";    
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
		    	
		    	$data = DB::table('master_geo')
		    		->select(DB::raw('CONCAT(city_name,", ", 	subdivision_1_name) as name'))
		    		->where('city_name', 'like',$postdata['name'].'%')
		    		->where("country_name",$postdata['country'])
		    		->get();


		    	//$data1 = $data->toArray();
		    	//print_r($data); exit;
		    	 
                //$locationResult[] = $loc["name"];
            	// echo json_encode($data[0]);
		    	//$result['data'] = $data;
		    	//$array = array("Test","Test 2","Test 3");
		    	/*$data = array();
		    	$data[0]["name"] = "Ahmedabad";
		    	$data[1]["name"] = "Ahmreli";*/
		    	//echo json_encode($data);

		    	$result["data"] = $data;
		    	//echo '["red","green","blue","cyan","magenta","yellow","black"]';
		    	//exit;
		        //This method will be execute after run the main process

		    }

		}