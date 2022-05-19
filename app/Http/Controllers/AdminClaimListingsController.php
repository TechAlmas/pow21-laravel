<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use File;
	use Illuminate\Support\Facades\Hash;
	use App\User;
	use App\DispensoryUser;
	
	use crocodicstudio\crudbooster\helpers\MailHelper;

	class AdminClaimListingsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "claim_listings";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Listing Name","name"=>"listing_id","join"=>"master_locations,name"];
			$this->col[] = ["label"=>"First Name","name"=>"first_name"];
			$this->col[] = ["label"=>"Last Name","name"=>"last_name"];
			$this->col[] = ["label"=>"Telephone","name"=>"telephone"];
			$this->col[] = ["label"=>"E Mail","name"=>"e_mail"];
			$this->col[] = ["label"=>"Verification Details","name"=>"verification_details"];
			$this->col[] = ["label"=>"Submission Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Id','name'=>'listing_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'claim_listings,id'];
			$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Telephone','name'=>'telephone','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'E Mail','name'=>'e_mail','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Verification Details','name'=>'verification_details','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Files','name'=>'files','type'=>'upload','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select2','width'=>'col-sm-10','dataenum'=>'Verified|Verified;Unverified|Unverified;Pending|Pending'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Id','name'=>'listing_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'claim_listings,id'];
			//$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Telephone','name'=>'telephone','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'E Mail','name'=>'e_mail','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Verification Details','name'=>'verification_details','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Files','name'=>'files','type'=>'upload','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select2','width'=>'col-sm-10','dataenum'=>'1|Verified;2|Unverified;3|Pending'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
			$this->addaction[] = ['url'=>CRUDBooster::mainpath('change-status/approve/[id]'),'label'=>'Approve','color'=>'success','confirmation'=>true];
            $this->addaction[] = ['url'=>CRUDBooster::mainpath('change-status/reject/[id]'),'label'=>'Reject','color'=>'danger','confirmation'=>true];
	        


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();			

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
			
	        
	        
	    }
	    /*public function getIndex() {
	    	$filter_column = Request::get('filter_column');
	    	if($filter_column['claim_listings.status']['value'] == 'approve'){
	    		$filter_column['claim_listings.status']['value'] = 1;
	    	}
	    	parent::getIndex();
	    	//dd($filter_column['claim_listings.status']['value']);
	    	//CRUDBooster::redirect();
	    	return true;
	    }*/
		
		public function getDetail($id)
		{
			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) 
			{    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
		        
			$data = [];
			$data['page_title'] = 'Detail Claim Listings';
			$data['row'] = DB::table('claim_listings')->where('id',$id)->first();
		
			foreach ($data as $row) {
					$unserializedData = unserialize($row->files);
					//print_r($unserializedData);
					//public_html/admin/storage/app/uploads/claims
            }
			$data['file'] = $unserializedData;
			// dd($data);
			$this->cbView('custom_detail_view',$data);
		}


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    |// @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	    	$filter_column = Request::get('filter_column');
	    	$new_status = array();
			if(!empty($filter_column)){
				// if($filter_column['claim_listings.status']['value']){
				// 	if($filter_column['claim_listings.status']['value'] == 'Verified'){
				// 		$filter_column['claim_listings.status']['value'] = 1;
				// 	}elseif($filter_column['claim_listings.status']['value'] == 'Unverified'){
				// 		$filter_column['claim_listings.status']['value'] = 2;
				// 	}else{
				// 		$filter_column['claim_listings.status']['value'] = 3;
				// 	}
				// }
				Request::offsetSet('filter_column',$filter_column);
			}else{
				$query->where('claim_listings.status','Pending');
			}
	    	
	    	//dd(Request::get('filter_column'));
	        //Your code here
			
			
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    	if($column_index == 8){
	    		switch($column_value) {
				   	case 'Verified':
						$column_value = "<span class='label label-success'>Verified</span>";
				   		break;
				   	case 'Pending':
						$column_value = "<span class='label label-warning'>Pending</span>";
				   		break;
				   	default:
						$column_value = "<span class='label label-danger'>Unverified</span>";
				   	break;
				}	
	    	}
	    	
			/*if($column_index == 3) 
			{
				switch($column_value) {
				   case '1':
					 $column_value = "<span class='label label-success'>Verified</span>";
				   break;
				   case '2':
					$column_value = "<span class='label label-danger'>Unverified</span>";
				   break;
				   case '3':
					$column_value = "<span class='label label-warning'>Pending</span>";
				   break;
				   default:
					$column_value = "<span class='label label-default'>".$column_value."</span>";
				   break;
				}
			}*/
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			// if($postdata['filter_column']['claim_listings.status']['value'] == 'approve'){
			// 	$postdata['filter_column']['claim_listings.status']['value'] = 1;
			// }
			// return $postdata;
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function changeStatus($status,$dataId){
			$checkData = DB::table('claim_listings')->where('id',$dataId)->first();
			if(!empty($checkData)){
				if($status == 'approve'){
					DB::table('claim_listings')->where('id',$dataId)->update(['status'=>'Verified']);
					$checkIfUserAlreadyExists = DB::table('cms_users')->where('email',$checkData->e_mail)->first();
					if(empty($checkIfUserAlreadyExists)){
						$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
						$shfl = str_shuffle($str);
						$pwd = substr($shfl,0,8);
						$userData                  =  [];
						$userData['name']          =  $checkData->first_name ." ". $checkData->last_name;
						$userData['email']         =  $checkData->e_mail;
						$userData['password']      =  Hash::make($pwd);
						$userData['status']        =  'Active';  
						$userData['id_cms_privileges'] =  6 ;  

						$user = User::create($userData);

						//Assigning User
						DispensoryUser::create(['user_id'=>$user->id, 'dispansary_id' => $checkData->listing_id]);

					}
					//Sending Mail To the User
					$data = ['email'=>$checkData->e_mail,'password' => $pwd];
					CRUDBooster::sendEmail(['to'=>$checkData->e_mail,'data'=>$data,'template'=>'claim_listing_approve']);
				}else if($status == 'reject'){
					
					DB::table('claim_listings')->where('id',$dataId)->update(['status'=>'Unverified']);
					// // Sending Mail To the User
					$data = ['name'=>$checkData->first_name];
					CRUDBooster::sendEmail(['to'=>$checkData->e_mail,'data'=>$data,'template'=>'claim_listing_reject']);

				}
				CRUDBooster::redirectBack("Status changed successfully", "success");
			}else{
				CRUDBooster::redirect(CRUDBooster::adminPath('claim_listings'),trans("Something Went Wrong"));
			}
		}



	    //By the way, you can still create your own method in here... :) 


	}