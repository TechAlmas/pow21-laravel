<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	//echo "string"; exit();

	class AdminUsersReviewsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "title";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "master_users_reviews";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"User","name"=>"user_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Strain","name"=>"strain_id","join"=>"master_strains,name"];
			$this->col[] = ["label"=>"Rating","name"=>"rating"];
			$this->col[] = ["label"=>"Title","name"=>"title"];
			$this->col[] = ["label"=>"Message","name"=>"message","width"=>"4"];
			$this->col[] = ["label"=>"Status","name"=>"status","callback" => function($row){
				if($row->status==1){ return "Active";}else{ return "InActive";}
			}];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'User Id','name'=>'user_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'cms_users,name'];
			$this->form[] = ['label'=>'Strain Id','name'=>'strain_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'master_strains,name'];
			$this->form[] = ['label'=>'Rating','name'=>'rating','type'=>'number','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Title','name'=>'title','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Message','name'=>'message','type'=>'textarea','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'1|Active;0|InActive'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'User Id','name'=>'user_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'cms_users,name'];
			//$this->form[] = ['label'=>'Strain Id','name'=>'strain_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'master_strains,name'];
			//$this->form[] = ['label'=>'Rating','name'=>'rating','type'=>'number','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Title','name'=>'title','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			//$this->form[] = ['label'=>'Message','name'=>'message','type'=>'textarea','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'1|Active;0|InActive'];
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
	        //$this->button_selected[] = ['label'=>'Merge Dispensaries','icon'=>'fa fa-plus','name'=>'merge_dispensaries'];

	                
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
	         $this->script_js = "
	        		$(document).ready(function () {	 

	        			$('.selected-action ul li a').click(function (event) {
							
                			var name = $(this).data('name');
                			$('#form-table input[name=\"button_name\"]').val(name);

                			if(name == 'delete'){

	        					var title = $(this).attr('title');

			                    swal({
								  title: 'Confirmation',
								  text: 'Are you sure want to '+title+' ?',
								  type: 'warning',
								  showCancelButton: true,
								  confirmButtonColor: '#008D4C',
								  confirmButtonText: 'Yes',
								  closeOnConfirm: false,
								  showLoaderOnConfirm: true
								},
								function () {
								  $('#form-table').submit();
								});

	        				}	        				
	        				 
	        			});

	        		})
	        	";


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


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
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
	    	$review = DB::table("master_users_reviews")->where("id",$id)->first();

	    	//DB::statement("UPDATE master_strains SET ratings = CASE WHEN ratings IS NULL THEN '".$review->rating."' ELSE (ratings + ".$review->rating.")/2 END WHERE id = ".$review->strain_id);

	       // DB::statement("UPDATE master_strains SET reviews = reviews + 1 WHERE id = ".$review->strain_id);
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

	    	$review = DB::table("master_users_reviews")->where("id",$id)->first();

	    	/*$review_data = DB::table("master_users_reviews")->where("strain_id",$review->strain_id)->get();
	    	$st_review = DB::table("master_strains")->where("id",$review->strain_id)->select('ratings')->first();
	    	$sum = $st_review->ratings;*/


	    	$rating = DB::select(DB::raw("SELECT COUNT(master_users_reviews.id) as no_of_rows,SUM(master_users_reviews.rating) as sum,master_strains.original_rating,master_strains.original_review FROM `master_users_reviews` LEFT JOIN master_strains ON master_strains.id = master_users_reviews.strain_id WHERE master_users_reviews.status = 1 AND  master_users_reviews.strain_id =".$review->strain_id));
	    	//print_r($rating); exit();


	    	$sum = $rating[0]->sum + $rating[0]->original_rating;
	    	$ratings = $sum/($rating[0]->no_of_rows+1);
	    	$reviews = $rating[0]->original_review+$rating[0]->no_of_rows;

	    	DB::statement("UPDATE master_strains SET ratings =".$ratings.",reviews = ".$reviews."  WHERE id = ".$review->strain_id);

	    	//echo $ratings; exit();

	    	//echo"<pre>";print_r($review_data); exit();

	    	/*foreach($review_data as $data)
	    	{
	    		$sum = $sum + $data->rating;
	    	}*/

	    	//echo $sum; exit();
	    	//$count = count($review_data)+1;

	    	//echo $count; exit();
	    	//$rating = $sum/$count;

	    	//echo $rating; exit();

	        //Your code here 
	        //$review = DB::table("master_users_reviews")->where("id",$id)->first();

	    	//DB::statement("UPDATE master_strains SET ratings = CASE WHEN ratings IS NULL THEN '".$review->rating."' ELSE (ratings + ".$review->rating.")/2 END WHERE id = ".$review->strain_id);

	        //DB::statement("UPDATE master_strains SET reviews = reviews + 1 WHERE id = ".$review->strain_id);
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



	    //By the way, you can still create your own method in here... :) 


	}