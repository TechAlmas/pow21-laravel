<?php namespace App\Http\Controllers;

	use Session;
	use Carbon\Carbon;
	use Request;
	use DB;
	use CRUDBooster;
	use Route;
	use App\MasterPriceHistory;
	use App\MasterPrice;
	use App\MasterMass;
	use App\MasterStrain;
	use App\MasterState;
	use App\MasterCity;
	use App\MasterCountry;
	use App\LeafyDispensary;
	use App\WeedmapsDispensary;
	use App\MasterLocationStrain;
	use App\MasterLocation;

	class AdminStrainsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

	    	//echo "Hello"; exit();

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "name,asc";
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
			$this->table = "master_strains";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Category","name"=>"category"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Category','name'=>'category','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];

			$this->form[] = ['label'=>'Description','name'=>'description','type'=>'textarea','validation'=>'','width'=>'col-sm-10'];

			$this->form[] = ['label'=>'Our Description','name'=>'our_description','type'=>'wysiwyg','validation'=>'','width'=>'col-sm-10'];

			$columns = [];
			$columns[] = ['label'=>'Attributes','name'=>'sub_attribute_id','type'=>'datamodal','datamodal_table'=>'master_attributes_sub','datamodal_columns'=>'name,main_attribute_name','datamodal_select_to'=>'attribute_id:attribute_id','required'=>true];			
			$columns[] = ['label'=>'Main Attribute','name'=>'attribute_id', 'type'=>'text', 'required'=>true, 'datatable' => 'master_attributes,name', 'display_attr' => "master_attributes_name"];
			$columns[] = ['label'=>'Value','name'=>'value','type'=>'text','required'=>true];

			$this->form[] = ['label'=>'Attribute Detail','name'=>'attribute_details','type'=>'child','columns'=>$columns,'table'=>'master_strains_attributes','foreign_key'=>'strain_id'];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			//$this->form[] = ['label'=>'Category','name'=>'category','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
	        $this->sub_module[] = ['label'=>'Add Keywords','path'=>'strain-keywords','button_color'=>'warning','button_icon'=>'fa fa-pencil-square-o','parent_columns'=>'id,name','foreign_key'=>'strain_id'];	


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

        			

		            $('.selected-action ul li a').click(function () {

                		var name = $(this).data('name');
                		$('#form-table input[name=\"button_name\"]').val(name);
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
                	});



        		});";


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

	        
	        DB::table("master_locations_strains")->where("strain_id",$id)->delete();
	        DB::table("master_prices")->where("strain_id",$id)->delete();
	        DB::table("master_prices_history")->where("strain_id",$id)->delete();
	        DB::table("master_strains_attributes")->where("strain_id",$id)->delete();
	        DB::table("master_strains_keywords")->where("strain_id",$id)->delete();
	        DB::table("master_brands_strains")->where("strain_id",$id)->delete();

	        $updateVal = array("own_id" => 0, "d_own_id" => 0, "b_own_id" => 0, "m_own_id" => 0);

	        DB::table("leafy_dispensaries_menu")->where("own_id",$id)->update($updateVal);
	        DB::table("weedmaps_products")->where("own_id",$id)->update($updateVal);

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

	    public function getDetail($id)
    	{


			$this->cbLoader();
			$row = DB::table($this->table)->where($this->primary_key, $id)->first();

		   // $row->created_at = $row->created_at;

			/*$row->fav_strain = $this->form[] = array("label"=>"Password","name"=>"fav_strain","type"=>"link");*/

			if (! CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {
				CRUDBooster::insertLog(trans("crudbooster.log_try_view", [
					'name' => $row->{$this->title_field},
					'module' => CRUDBooster::getCurrentModule()->name,
				]));
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$module = CRUDBooster::getCurrentModule();

			$page_menu = Route::getCurrentRoute()->getActionName();
			$page_title = trans("crudbooster.detail_data_page_title", ['module' => $module->name, 'name' => $row->{$this->title_field}]);
			$command = 'detail';
			$count = DB::table("master_users_reviews")->where('strain_id',$id)->count();

			//print_r($count); exit();

			Session::put('current_row_id', $id);


			return view('straindetail', compact('count','row', 'page_menu', 'page_title', 'command', 'id'));
		}

	    //By the way, you can still create your own method in here... :) 
		
		public function getEdit($id)
		{
			$this->cbLoader();
			$row = DB::table($this->table)->where($this->primary_key, $id)->first();

			if (! CRUDBooster::isRead() && $this->global_privilege == false || $this->button_edit == false) {
				CRUDBooster::insertLog(trans("crudbooster.log_try_edit", [
					'name' => $row->{$this->title_field},
					'module' => CRUDBooster::getCurrentModule()->name,
				]));
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$page_menu = Route::getCurrentRoute()->getActionName();
			$page_title = trans("crudbooster.edit_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name, 'name' => $row->{$this->title_field}]);
			$command = 'edit';
			Session::put('current_row_id', $id);
			
			$pricings = MasterPriceHistory::where(['strain_id'=>$id])->where('state_id', '<>', '')->where('country_id', '<>', '')->get();
			$pricings_data = [];
			if($pricings){
				foreach($pricings as $pkey=>$pricing){
					
					$mass 		              = MasterMass::find($pricing->mass_id);
					$location_count		  = MasterPriceHistory::where(['strain_id'=>$pricing->strain_id])->where('state_id', '<>', '')->count();
					
					$city		  				      = MasterCity::where(['id'=>$pricing->state_id])->first();
					$state		  				  = MasterState::where(['id'=>$pricing->state_id])->first();
					$country		  			  = MasterCountry::where(['id'=>$pricing->country_id])->first();
					$strain						  = MasterStrain::where(['id'=>$pricing->strain_id])->first();
					$ml_strain				  = MasterLocationStrain::where(['strain_id'=>$pricing->strain_id])->first();
					$source_count		  = MasterStrain::where(['id'=>$pricing->strain_id])->count();
					
					if($strain->source == 'leafly'){
						$dis					  	  = MasterLocation::find(94);
						$dis                       = $dis->name;
					}elseif($strain->source == 'weedmaps'){
						$dis					  	 = MasterLocation::find(94);
						$dis                   	 = $dis->name;
					}else{
						$dis                    = 'BC Cannabis';
					}
					// return dd($dis);
					$pricing->created_at = new Carbon($pricing->created_at);
					$pricings_data[]  = ['price'=>$pricing->price,'created_at'=>$pricing->created_at->toDateTimeString(),'mass_id'=>$pricing->mass_id,'mass'=>$mass->name,'location_count'=>$location_count,'source_count'=>$source_count,'state'=>$state->state_code,'city'=>$city->city,'country'=>$country->country_name,'dis'=>$dis];
				}
			}
			
			return view('strain_edit', compact('id', 'row', 'page_menu', 'page_title', 'command','pricings_data'));
		}

	}