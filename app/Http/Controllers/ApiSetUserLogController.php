<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSetUserLogController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_logs";        
				$this->permalink   = "set_user_log";    
				$this->method_type = "post"; 
				$this->user_token =time();    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		        $postdata['ipaddress']=$this->get_client_ip();
		        $postdata['useragent']=$_SERVER['HTTP_USER_AGENT'];

		        if($postdata['user_id']=='0'){

		        	//echo "if";
		        	$postdata['id_cms_users'] = $this->user_token;	
		        }
		        else
		        {
		        	$postdata['id_cms_users'] = $postdata['user_id'];
		        }

		       	       
		         unset($postdata["user_id"]);
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        //DB::table("cms_logs")->where("id",$result->id)->update(array("id_cms_users"=>$postdata['id_cms_users']));

		    	//print_r($postdata); exit;
		    	$result['user_id'] = $postdata['id_cms_users'];
		    }
		    // Function to get the client IP address
			function get_client_ip() {
			    $ipaddress = '';
			    if (isset($_SERVER['HTTP_CLIENT_IP']))
			        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			    else if(isset($_SERVER['HTTP_X_FORWARDED']))
			        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			    else if(isset($_SERVER['HTTP_FORWARDED']))
			        $ipaddress = $_SERVER['HTTP_FORWARDED'];
			    else if(isset($_SERVER['REMOTE_ADDR']))
			        $ipaddress = $_SERVER['REMOTE_ADDR'];
			    else
			        $ipaddress = 'UNKNOWN';
			    return $ipaddress;
			}

		}