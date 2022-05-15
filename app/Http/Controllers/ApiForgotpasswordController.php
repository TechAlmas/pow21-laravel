<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiForgotpasswordController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "forgotpassword";    
				$this->method_type = "post";
				$this->passwordNew = $this->randomPassword();    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        //$passwordNew = $this->randomPassword()
		    	$postdata["password"] = Hash::make($this->passwordNew);

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		        $existedDB = DB::table($this->table)
                              ->where('email', $postdata["email"])			      
                              ->first();

					 if ($existedDB != null && isset($existedDB)) {
						
						DB::table($this->table)->where('email', $postdata["email"])->update(array('password'=>Hash::make($this->passwordNew)));
						
				        $data = ['password'=>$this->passwordNew];
						CRUDBooster::sendEmail(['to'=>$postdata['email'],'data'=>$data,'template'=>'forgot_password_user']);
						
						
					}else
					{
						$result['api_status']="0";
						$result['api_message']="Email address does not exist!";
					}

		    }
		    public function randomPassword() {
			    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			    $pass = array(); //remember to declare $pass as an array
			    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			    for ($i = 0; $i < 8; $i++) {
			        $n = rand(0, $alphaLength);
			        $pass[] = $alphabet[$n];
			    }
			    return implode($pass); //turn the array into a string
			}

		}