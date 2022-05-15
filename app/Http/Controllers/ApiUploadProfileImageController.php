<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Storage;
		use File;

		class ApiUploadProfileImageController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "upload_profile_image";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		        //print_r($postdata);exit;
		    	

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		       // $query->limit(0);

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    	$file = $postdata["photo"];
		        $realname = str_slug(pathinfo($file, PATHINFO_FILENAME));
		        $extension = $file->getClientOriginalExtension();



		        //Storage::makeDirectory(date('Y-m'));

		        $filename = md5(str_random(5)).'.'.$extension;

		        $new_name = 'uploads/'.date('Y-m').'/'.$filename;

		        $folder_path = storage_path('app'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.date('Y-m'));

		        File::isDirectory($folder_path) or File::makeDirectory($folder_path, 0777, true, true);

		        if($file->move($folder_path,$filename)) {
					//echo asset('uploads/'.date('Y-m').'/'.$filename);
					DB::table("cms_users")->where("id",2)->update(array("photo"=>$new_name));
				}
          		/*if($file->move(public_path('uploads'), $new_name)){          			
          			DB::table("cms_users")->where("id",2)->update(array("photo"=>$new_name));
          		}*/
          		DB::table("cms_users")->where('id',$postdata['id'])->update(['photo'=>$new_name]);
          		$result["image"] = url('/')."/".$new_name;


		    }

		}