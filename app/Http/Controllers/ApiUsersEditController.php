<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUsersEditController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "users_edit";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {

		    	//print_r($postdata);exit;

		    	unset($postdata["first_name"]);
		    	unset($postdata["last_name"]);
		        //This method will be execute before run the main process
/*
			$part = public_path()."/uplode/";
		    	 $filename = "img".rand(9,9999).".jpg";
		    	 $res = array(); 
			$kode = ""; 
			$postdata['photo']="";
	             $pesan = ""; 
	             echo print_r($_FILES); exit;
	           	 if($_SERVER['REQUEST_METHOD'] == "POST")
	           	 {

	           	 	
	           	 	if($_FILES['photo'])
					{
						$destinationfile = $part.$filename; 

						if(move_uploaded_file($_FILES['photo']['tmp_name'], $destinationfile))
						{
							//echo "uploaded sucessfully"; exit;
							
							$postdata['photo'] = "uplode/".$filename; 				
						}
						
					}
					
	           	 }*/
	           	 

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        $existedUser = DB::table($this->table)
		                      ->where('id', $postdata["id"])
		                      ->first();
				$result['data'] = $existedUser;

		    }

		}
