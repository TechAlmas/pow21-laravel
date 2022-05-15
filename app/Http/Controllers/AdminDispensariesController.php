<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminDispensariesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "master_locations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Logo","name"=>"logoUrl","image"=>true];
			$this->col[] = ["label"=>"Name","name"=>"name","width"=>"20px;"];
			$this->col[] = ["label"=>"Address","name"=>"address"];
			$this->col[] = ["label"=>"City","name"=>"city"];
			$this->col[] = ["label"=>"State","name"=>"state"];
			$this->col[] = ["label"=>"Zip Code","name"=>"zip_code"];
			$this->col[] = ["label"=>"Country","name"=>"country"];
			$this->col[] = ["label"=>"Email","name"=>"email"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address 2','name'=>'address2','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'City','name'=>'city','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'State','name'=>'state','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Zip Code','name'=>'zip_code','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Country','name'=>'country','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Logo','name'=>'logoUrl','type'=>'upload','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tags','name'=>'license_type','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Website','name'=>'website','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'email','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email 2','name'=>'email2','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Phone','name'=>'phone','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'1|Active;0|InActive'];
			$icon_columns[] = ['label'=>'Text','name'=>'option_value','type'=>'text','required'=>false];
			$icon_columns[] = ['label'=>'Icon','name'=>'option_icon','type'=>'datamodal','width'=>'col-sm-9','datamodal_table'=>'master_icons','datamodal_columns'=>'name','datamodal_size'=>'large','datamodal_columns_alias_name'=>'"Name"'];
			//$this->form[] = ['label'=>'Store Meta','name'=>'icon','type'=>'child','validation'=>'required|string','width'=>'col-sm-9','columns'=>$icon_columns,'table'=>'test','foreign_key'=>'dispansary_id'];
			$this->form[] = ['label'=>'Store Meta','name'=>'user','type'=>'child','validation'=>'string','width'=>'col-sm-9','columns'=>$icon_columns,'table'=>'dispensaries_store_icons','foreign_key'=>'dispansary_id'];

			$user_columns[] = ['label'=>'Assign Business','name'=>'user_id','type'=>'datamodal','width'=>'col-sm-9','datamodal_table'=>'cms_users','datamodal_columns'=>'name','datamodal_size'=>'large','datamodal_columns_alias_name'=>'"Name"'];
			$this->form[] = ['label'=>'Business Owners','name'=>'user','type'=>'child','width'=>'col-sm-9','columns'=>$user_columns,'table'=>'dispensaries_users','foreign_key'=>'dispansary_id'];


			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Address 2','name'=>'address2','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'City','name'=>'city','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'State','name'=>'state','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Zip Code','name'=>'zip_code','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Country','name'=>'country','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Logo','name'=>'logoUrl','type'=>'upload','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Tags','name'=>'license_type','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Website','name'=>'website','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'email','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email 2','name'=>'email2','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Phone','name'=>'phone','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'1|Active;0|InActive'];
			//$this->form[] = ['label'=>'icon','name'=>'icon','type'=>'child','validation'=>'required|string','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'user','name'=>'user','type'=>'child','validation'=>'required|string','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'icon','name'=>'test_icon','type'=>'datamodal','validation'=>'required','width'=>'col-sm-9','datamodal_table'=>'master_icons','datamodal_columns'=>'name','datamodal_size'=>'large'];
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
	        $this->button_selected[] = ['label'=>'Merge Dispensaries','icon'=>'fa fa-plus','name'=>'merge_dispensaries'];
	        //$this->button_selected[] = ['label'=>'Assign To Group','icon'=>'fa fa-plus','name'=>'group_dispensaries'];

	                
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
	        			console.log($('#iframe-modal-storemetaoption_icon table tbody tr td').text());
	        			$('.selected-action ul li a').click(function (event) {
							
                			var name = $(this).data('name');
                			$('#form-table input[name=\"button_name\"]').val(name);

                			if(name == 'group_dispensaries'){
	        					swal({
								  title: 'Are you Sure?',
								  text: 'Provide Group Name, which required to assing',
								  type: 'input',
								  showCancelButton: true,
								  closeOnConfirm: false,
								  inputPlaceholder: ''
								}, function (inputValue) {
								  if (inputValue === false) return false;
								  if (inputValue === '') {
								    swal.showInputError('You need to write something!');
								    return false
								  }
								  var tt = '<input id=\"group_name\" type=\"hidden\" name=\"group_name\" value=\"\" /> ';
								  	$('#form-table').append(tt);
								  	$('#group_name').val(inputValue);
								  	$('#form-table').submit();
								});
	        				}else if(name == 'merge_dispensaries'){

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

	        		});
	        		$(document).on('click','#storemetaoption_icon button',function(){
	        			setTimeout(function(){
	        				$.each($('#iframe-modal-storemetaoption_icon').contents().find('#table_dashboard tbody tr td:nth-child(2)'),function(index,value){
	        					console.log(this.html());
	        				});
	        			}, 4000)
					});
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
	        
	        $this->style_css = 'div#form-group-status div div {
								    float: left;
								    margin-right: 20px;
								}';
	        
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

	        if($button_name == "merge_dispensaries"){

	        	$change_id = min($id_selected);

	        	$chData = DB::table("master_locations")->select("email")->where("id",$change_id)->first();

	        	$emiailData = "";

	        	foreach ($id_selected as $key => $id) {

	        		if($change_id != $id){

	        			$repData = DB::table("master_locations")->select("email")->where("id",$id)->first();

	        			if($chData->email != $repData->email){
	        				$emiailData .= $repData->email.",";
	        			}

	        			$updateVal1 = array("own_id" => $change_id);
	        			$updateVal2 = array("d_own_id" => $change_id);
	        			$updateVal3 = array("location_id" => $change_id);

	        			DB::table("master_locations_strains")->where("location_id",$id)->update($updateVal3);
	       				DB::table("master_prices")->where("location_id",$id)->update($updateVal3);
	        			DB::table("master_prices_history")->where("location_id",$id)->update($updateVal3);
	        			DB::table("master_brands_strains")->where("location_id",$id)->update($updateVal3);

						DB::table("leafy_dispensaries")->where("own_id",$id)->update($updateVal1);
	        			DB::table("weedmaps_dispensaries")->where("own_id",$id)->update($updateVal1);

	        			DB::table("leafy_dispensaries_menu")->where("d_own_id",$id)->update($updateVal2);
	        			DB::table("weedmaps_products")->where("d_own_id",$id)->update($updateVal2);

	        			DB::table("master_locations")->where("id",$id)->update(array("status" => 2));

	        			$logDet = array();
	        			$logDet["action"] = "Dispensary Merged";
	        			$logDet["detail"] = $id." with ".$change_id;
	        			DB::table("admin_action_track")->insert($logDet);

	        		}
	        	}

	        	$emiailData = rtrim($emiailData,',');
	        	DB::table("master_locations")->where("id",$change_id)->update(array("email2" => $emiailData));
	        	//echo "<pre>"; print_r($id_selected);exit;
	        	      	

	        }else if($button_name == "group_dispensaries"){




	        }	       	
	       
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
	        if(CRUDBooster::isSuperadmin()){
	        	$query->where("status","!=",2);
	        }else{
	        	//echo 'asdfsf'; exit();
	        	$query->join('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')->where('dispensaries_users.user_id',CRUDBooster::myId());
	        }
	        
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