<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminMissingStrainsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "name,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon_text";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "weedmaps_products";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Category Name","name"=>"category_name"];
			$this->col[] = ["label"=>"Source","name"=>"slug","callback"=>function($row) {
				return "Weedmaps";
			}];
			//$this->col[] = ["label"=>"Strain Id","name"=>"strain_id"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Keyword','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Master Strain','name'=>'own_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'master_strains,name'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Strain Id','name'=>'strain_id','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Category Name','name'=>'category_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Vendor Name','name'=>'vendor_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
	        //$this->addaction[] = ['label'=>'Add to Master','url'=>CRUDBooster::mainpath('create-master/[id]'),'icon'=>'fa fa-plus','color'=>'primary'];
	        $this->addaction[] = ['label'=>'Add to Master','url'=>CRUDBooster::mainpath('custom-add/[id]'),'icon'=>'fa fa-plus','color'=>'primary'];
	        //$this->addaction[] = ['label'=>'Set to Master','url'=>'javascript:void(0)','data-name'=>'create-master','icon'=>'fa fa-pencil','color'=>'success'];

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
			$this->button_selected[] = ['label'=>'Trash','icon'=>'fa fa-trash','name'=>'set_fake'];
			$this->button_selected[] = ['label'=>'Add to Master','icon'=>'fa fa-plus','name'=>'create_strains'];
	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert = array();    

	        
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
						  text: 'Are you sure want to '+title+' Selected ?',
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
	        
	        if($button_name == "set_fake"){
	        	foreach($id_selected as $id){
	        	/*	DB::table('weedmaps_products')
            			->where('id', $id)
            			->update(['fake' => 1]);*/

            		$st = DB::table("weedmaps_products")->select('name')->where('id',$id)->first();
            		if(isset($st->name) && !empty($st->name)){
            			DB::table('weedmaps_products')
            			->where('name', $st->name)
            			->update(['fake' => 1]);
            		}
            		//echo "<pre>"; print_r($st);exit;
	        	}
	        }

	        if($button_name == "create_strains"){

	        	foreach($id_selected as $id) {	

	        	$wmstrains = DB::select( DB::raw("SELECT wp.id, wp.name as s_name, wp.vendor_id, wp.vendor_name, wp.prices, wp.own_id as s_own_id, wp.b_own_id, wp.m_own_id, wd.own_id as d_own_id, wd.id as desp_id, wd.name, wd.city, wd.state, wd.address, wd.address, wp.category_name, wd.avatar_url as logoUrl, wd.zip_code, wd.country, wd.phone_number, wd.email, wd.city_id, wd.rating, wd.reviews_count, wd.license_type, wd.hours_of_operation, wd.description FROM weedmaps_products wp LEFT JOIN weedmaps_dispensaries wd on wd.id = wp.listing_id WHERE wp.fake = 0 AND wp.id = ".$id)); 

	        	$wm_strains[0] =  (array) $wmstrains[0];   

	        	$str_data = array();
	        	$str_data["name"] = $wm_strains[0]["s_name"];
	        	$str_data["category"] = $wm_strains[0]["category_name"];
	        	$str_data["reviews"] = 0;
	        	$str_data["ratings"] = 0;
	        	$str_data["source"] = "weedmaps";
	        	$str_data["description"] = "";
	        	$str_data["our_description"] = "";

	        	$s_own_id = DB::table("master_strains")->insertGetId($str_data);

			if(count($wm_strains) > 0){	

				foreach ($wm_strains as $key => $wmstrains) {					

					$city_id = $wmstrains["city_id"];
					$d_own_id = 0;
					$b_own_id = 0;

					$schedule = json_decode($wmstrains["hours_of_operation"],1);

					//echo "<pre>"; print_r($schedule);exit;

					$day_names = array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");

					$new_schedule = array();

					foreach ($day_names as $key => $day) {

						if(isset($schedule[$day])){

							$tmp_val = str_replace("am"," AM",$schedule[$day]);
							$tmp_va2 = str_replace("pm"," PM",$tmp_val);

							$new_schedule[$day] = $tmp_va2;
							
						}
					}

					//echo "<pre>"; print_r($wmstrains);exit;

					if($wmstrains["d_own_id"] == 0 && isset($wmstrains["desp_id"]) && !empty($wmstrains["desp_id"])){
						if($city_id == 0)	
						{

							$cityDet = DB::select( DB::raw("select id from master_cities where city='".$wmstrains["city"]."' and (state_code ='".$wmstrains["state"]."' or state='".$wmstrains["state"]."'  or state_name='".$wmstrains["state"]."') and (country='".$wmstrains["country"]."' or country_code ='".$wmstrains["country"]."')"));
							
							if(count($cityDet)>0){
								$city_id = $cityDet[0]->id;
							}
						}						

						$despData = array();

						$despData["name"] = $wmstrains["name"];
						$despData["city"] = $wmstrains["city"];
						$despData["state"] = $wmstrains["state"];
						$despData["address"] = $wmstrains["address"];
						$despData["zip_code"] = $wmstrains["zip_code"];
						$despData["country"] = $wmstrains["country"];
						//$despData["website"] = $wmstrains["web_url"];
						$despData["email"] = $wmstrains["email"];
						$despData["phone"] = $wmstrains["phone_number"];
						$despData["source"] = "weedmaps";
						$despData["city_id"] = $city_id;
						$despData["logoUrl"] = $wmstrains["logoUrl"];

						$despData["description"] = $wmstrains["description"];	
						$despData["schedule"] = json_encode($new_schedule);	
						$despData["ratings"] = $wmstrains["rating"];	
						$despData["reviews"] = $wmstrains["reviews_count"];
						$despData["license_type"] = $wmstrains["license_type"];

						//echo "<pre>"; print_r($despData);exit;

						$dispD = DB::table("weedmaps_dispensaries")->where("id",$wmstrains["desp_id"])->first();
						//echo "<pre>"; print_r($dispD);exit;

						if(count($dispD) > 0 && $dispD->own_id == 0){
							$d_own_id = DB::table("master_locations")->insertGetId($despData);
							DB::table("weedmaps_dispensaries")->where("id",$wmstrains["desp_id"])->update(array("own_id" => $d_own_id,"city_id"=>$city_id));
							//exit;
						}else{

							//echo "test";exit;

							$despData = array();

	    				 	$despData["description"] = $wmstrains["description"];	
							$despData["schedule"] = json_encode($new_schedule);	
							$despData["ratings"] = $wmstrains["rating"];	
							$despData["reviews"] = $wmstrains["reviews_count"];
							$despData["license_type"] = $wmstrains["license_type"];

							DB::table("master_locations")->where("id",$dispD->own_id)->update($despData);

	    				 	$d_own_id = $dispD->own_id;
						}
						

					}else{

						//echo "sdf";exit;

						$despData = array();

    				 	$despData["description"] = $wmstrains["description"];	
						$despData["schedule"] = json_encode($new_schedule);	
						$despData["ratings"] = $wmstrains["rating"];	
						$despData["reviews"] = $wmstrains["reviews_count"];
						$despData["license_type"] = $wmstrains["license_type"];



						DB::table("master_locations")->where("id",$wmstrains["d_own_id"])->update($despData);
						
						$d_own_id = $wmstrains["d_own_id"];
						$city_id = $wmstrains["city_id"];
					}									


					if($wmstrains["m_own_id"] == 0){

						$l_s_data = array();
						$l_s_data = array(
							'strain_id' => $s_own_id,
							'location_id' => $d_own_id,
							'name' =>	$wmstrains["s_name"],
							'thc' =>	"",
							'cbd' =>	"",
							'category_name' =>	$wmstrains["category_name"]
							//'image_url' =>	$wmstrains["imageUrl"]
						);
						$m_own_id = DB::table("master_locations_strains")->insertGetId($l_s_data);


					}else{
						$m_own_id = $wmstrains["m_own_id"];
						$l_s_data = array();
						$l_s_data = array(
							'strain_id' => $s_own_id,
							'location_id' => $d_own_id,
							'name' =>	$wmstrains["s_name"],
							'thc' =>	"",
							'cbd' =>	"",
							'category_name' =>	$wmstrains["category_name"]
							//'image_url' =>	$wmstrains["imageUrl"]
						);

						DB::table("master_locations_strains")->where("id",$m_own_id)->update($l_s_data);
					}			
					//echo $m_own_id;exit;
					$prices = json_decode($wmstrains["prices"]); 

					//echo "<pre>"; print_r($prices);exit;

					$units = array("gram"=>1,"two_grams"=>2,"eighth"=>7,"quarter"=>8,"half_ounce"=>9,"ounce"=>10);

					foreach ($prices as  $key => $value){

						$mass_id = $units[$key];
		    			$data_price =  array();
		    			$data_price['city_id'] = $city_id; 
		    		 	$data_price['strain_id'] = $s_own_id;
		    			$data_price['menu_id'] = $m_own_id; 
		    		  	$data_price['location_id'] = $d_own_id;
		    		 	$data_price['mass_id'] = $mass_id;
		    		 	$data_price['price'] = $value;

		    		 	//echo "<pre>"; print_r($data_price);exit;

		    		 	$query = DB::table("master_prices");

		    		 	$query->where("city_id",$data_price['city_id']);
		    		 	$query->where("strain_id",$data_price['strain_id']);
		    		 	$query->where("menu_id",$data_price['menu_id']);
						$query->where("location_id",$data_price['location_id']);
						$query->where("mass_id",$data_price['mass_id']);

						$dis11 = $query->first();

						//echo "<pre>"; print_r(count($dis11));exit;
						
						if (count($dis11) == 0){  
							$master_prices_id = DB::table("master_prices")->insertGetId($data_price);
						}else{
							$master_prices_id = $dis11->id;
							DB::table("master_prices")->where("id",$dis11->id)->update($data_price);
						}
						
						$data_price["master_prices_id"] = $master_prices_id;
						DB::table("master_prices_history")->insert($data_price);

					}

					if($wmstrains["b_own_id"] == 0 && isset($wmstrains["vendor_id"]) && !empty($wmstrains["vendor_id"]) ){

						$b_new = array();
						$b_new["name"] = $wmstrains["vendor_name"];
						$b_new["source"] = "weedmaps";

						$dis = DB::table("master_brands")->where("name",$wmstrains["vendor_name"])->first();

						if (count($dis) > 0){ 
							$b_own_id = $dis->id;
						}else{
							$b_own_id = DB::table("master_brands")->insertGetId($b_new);
						}

						DB::table("weedmaps_products")->where("id",$wmstrains["id"])->update(array("b_own_id" => $b_own_id));

					}else{
						$b_own_id = $wmstrains["b_own_id"];
					}

					if($b_own_id > 0){	  

						$query = DB::table("master_brands_strains");

						$query->where("menu_id", $m_own_id);
						$query->where("strain_id", $s_own_id);
						$query->where("brand_id", $b_own_id);

						$dis = $query->first();

						if (count($dis) == 0){
							$l_s_data = array();
							$l_s_data = array('menu_id' => $m_own_id,'strain_id' => $s_own_id, 'location_id' => $d_own_id, "brand_id"=>$b_own_id);
							DB::table("master_brands_strains")->insert($l_s_data);
						}
					}

					DB::table("weedmaps_products")->where("id",$wmstrains["id"])->update(array("own_id" => $s_own_id,"d_own_id"=>$d_own_id,"b_own_id"=>$b_own_id,"m_own_id" => $m_own_id));

				}


			}  		

            
	        	}

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

	        $query->where('fake',"0");
	        $query->where('own_id',"0");
	            
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
	    	//echo "<pre>"; print_r($postdata);exit;
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

	    	echo "Test";exit;

	    	$keyword = $postdata["name"];
	    	$s_own_id = $postdata["own_id"];
	    	$d_own_id = 0;
	    	$b_own_id = 0;

	    	$key = DB::table("master_strains_keywords")->where("keyword",$keyword)->count();

	    	if($key == 0){
	    		DB::table("master_strains_keywords")->insert(array("strain_id"=>$s_own_id,"keyword"=>$keyword));
	    	}
	        
	        //Your code here
	        $wmstrains = DB::table('weedmaps_products')
	        		->select('weedmaps_dispensaries.id as desp_id',"weedmaps_dispensaries.name","weedmaps_dispensaries.state","weedmaps_dispensaries.city","weedmaps_dispensaries.address","weedmaps_dispensaries.country","weedmaps_dispensaries.zip_code","weedmaps_dispensaries.phone_number","weedmaps_dispensaries.email","weedmaps_dispensaries.own_id as d_own_id","weedmaps_products.vendor_name","weedmaps_products.b_own_id")
	        		->join('weedmaps_dispensaries', 'weedmaps_dispensaries.id', '=', 'weedmaps_products.listing_id')
        			->where('weedmaps_products.id', $id)        			
        			->first();

        	if(isset($wmstrains->desp_id) && !empty($wmstrains->desp_id)){

        		// echo "<pre>"; print_r($wmstrains);exit;

        		if($wmstrains->d_own_id == 0){

					$despData = array();
					$despData["name"] = $wmstrains->name;
					$despData["city"] = $wmstrains->city;
					$despData["state"] = $wmstrains->state;
					$despData["address"] = $wmstrains->address;
					$despData["zip_code"] = $wmstrains->zip_code;
					$despData["country"] = $wmstrains->country;
					$despData["email"] = $wmstrains->email;
					$despData["phone"] = $wmstrains->phone_number;
					$despData["source"] = "weedmaps";

					$d_own_id = DB::table("master_locations")
						->insertGetId($despData);

					DB::table("weedmaps_dispensaries")
						->where("id",$wmstrains->desp_id)
						->update(array("own_id" => $d_own_id));
					
				}else{
					$d_own_id = $wmstrains->d_own_id;
				}

				$disp = DB::table("master_locations_strains")->where("strain_id",$s_own_id)->where("location_id",$d_own_id)->count();

				if($disp == 0){
					$l_s_data = array();
					$l_s_data = array('strain_id' => $s_own_id, 'location_id' => $d_own_id);
					DB::table("master_locations_strains")->insert($l_s_data);
				}
        		
        	}

        	if($wmstrains->b_own_id == 0 && isset($wmstrains->vendor_name) && !empty($wmstrains->vendor_name)){
				
				$b_new = array();
				$b_new["name"] = $wmstrains->vendor_name;
				$b_new["source"] = "weedmaps";

				$brand = DB::table("master_brands")->where("name",$wmstrains->vendor_name)->first();

				//echo "<pre>"; print_r($brand->id);exit;

				if(isset($brand->id)){
					$b_own_id = $brand->id;
				}else{
					$b_own_id = DB::table("master_brands")->insertGetId($b_new);					
				}	
				
			}else{
				$b_own_id = $wmstrains->b_own_id;
			}

        	if($b_own_id > 0){ 

				$brand_st = DB::table("master_brands_strains")->where("strain_id",$s_own_id)->where("brand_id",$b_own_id)->count();

				if($brand_st == 0){
					$l_s_data = array();
					$l_s_data = array('strain_id' => $s_own_id, 'location_id' => $d_own_id, "brand_id"=>$b_own_id);
					DB::table("master_brands_strains")->insert($l_s_data);
				}

        	}	    

        	DB::table("weedmaps_products")->where("id",$id)->update(array("own_id" => $s_own_id,"d_own_id"=>$d_own_id,"b_own_id"=>$b_own_id));	   

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

	    public function getCustomAdd($id){

	    	//Create an Auth
			  if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
			    CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			  }

		      $wp_strain = DB::table('weedmaps_products')
		      		->select("id","name")
        			->where('id', $id)
        			->first();

        	  $master_strain = DB::table('master_strains')
        	  			->select("id","name")
        	  			->orderby("name")
        	  			->get();

        	  $data = [];
			  $data['page_title'] = 'Add to Master';
			  $data['name'] = $wp_strain->name;
			  $data['raw_id'] = $wp_strain->id;
			  $data['master_strains'] = $master_strain;
			  
			  //Please use cbView method instead view method from laravel
			  $this->cbView('customadd',$data);

	    }

	    public function postSaveToMaster(){
	    	
	    	$name = Request::input('name');
	    	$keyword = Request::input('keywords');
	    	$own_id = Request::input('own_id');
	    	$raw_id = Request::input('raw_id');	    	

    		if(isset($name) && !empty($name) && isset($raw_id) && !empty($raw_id)){

    			$wmstrains = DB::select( DB::raw("SELECT wp.id, wp.name as s_name, wp.vendor_id, wp.vendor_name, wp.prices, wp.own_id as s_own_id, wp.b_own_id, wp.m_own_id, wd.own_id as d_own_id, wd.id as desp_id, wd.name, wd.city, wd.state, wd.address, wd.address, wp.category_name, wd.avatar_url as logoUrl, wd.zip_code, wd.country, wd.phone_number, wd.email, wd.city_id, wd.rating, wd.reviews_count, wd.license_type, wd.hours_of_operation, wd.description FROM weedmaps_products wp LEFT JOIN weedmaps_dispensaries wd on wd.id = wp.listing_id WHERE wp.fake = 0 AND wp.id = ".$raw_id));

	    		$wm_strains[0] =  (array) $wmstrains[0];      	          	

            	if($own_id > 0){

            		$s_own_id = $own_id;

            	}else{

            		$str_data = array();
		        	$str_data["name"] = $name;
		        	$str_data["category"] = $wm_strains[0]["category_name"];
		        	$str_data["reviews"] = 0;
		        	$str_data["ratings"] = 0;
		        	$str_data["source"] = "weedmaps";
		        	$str_data["description"] = "";
		        	$str_data["our_description"] = "";

		        	$s_own_id = DB::table("master_strains")->insertGetId($str_data);

            	}   

            	//echo "<pre>"; print_r($wmstrains);exit;

            	//echo $s_own_id;exit;

            	if(isset($keyword) && !empty($keyword)){
            		
            		$keys = explode(",", $keyword);

            		//echo "<pre>"; print_r($keys);exit;

            		foreach ($keys as $value) {

            			$dbC = DB::table("master_strains_keywords")->where("keyword",$value)->count();

				    	if($dbC == 0){
				    		DB::table("master_strains_keywords")->insert(array("strain_id"=>$s_own_id,"keyword"=>$value));
				    	}
            			
            		}

            	}         	

            	if(count($wm_strains) > 0){	

				foreach ($wm_strains as $key => $wmstrains) {					

					$city_id = $wmstrains["city_id"];
					$d_own_id = 0;
					$b_own_id = 0;

					$schedule = json_decode($wmstrains["hours_of_operation"],1);

					//echo "<pre>"; print_r($schedule);exit;

					$day_names = array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");

					$new_schedule = array();

					foreach ($day_names as $key => $day) {

						if(isset($schedule[$day])){

							$tmp_val = str_replace("am"," AM",$schedule[$day]);
							$tmp_va2 = str_replace("pm"," PM",$tmp_val);

							$new_schedule[$day] = $tmp_va2;
							
						}
					}

					//echo "<pre>"; print_r($wmstrains);exit;

					if($wmstrains["d_own_id"] == 0 && isset($wmstrains["desp_id"]) && !empty($wmstrains["desp_id"])){
						if($city_id == 0)	
						{

							$cityDet = DB::select( DB::raw("select id from master_cities where city='".$wmstrains["city"]."' and (state_code ='".$wmstrains["state"]."' or state='".$wmstrains["state"]."'  or state_name='".$wmstrains["state"]."') and (country='".$wmstrains["country"]."' or country_code ='".$wmstrains["country"]."')"));
							
							if(count($cityDet)>0){
								$city_id = $cityDet[0]->id;
							}
						}						

						$despData = array();

						$despData["name"] = $wmstrains["name"];
						$despData["city"] = $wmstrains["city"];
						$despData["state"] = $wmstrains["state"];
						$despData["address"] = $wmstrains["address"];
						$despData["zip_code"] = $wmstrains["zip_code"];
						$despData["country"] = $wmstrains["country"];
						//$despData["website"] = $wmstrains["web_url"];
						$despData["email"] = $wmstrains["email"];
						$despData["phone"] = $wmstrains["phone_number"];
						$despData["source"] = "weedmaps";
						$despData["city_id"] = $city_id;
						$despData["logoUrl"] = $wmstrains["logoUrl"];

						$despData["description"] = $wmstrains["description"];	
						$despData["schedule"] = json_encode($new_schedule);	
						$despData["ratings"] = $wmstrains["rating"];	
						$despData["reviews"] = $wmstrains["reviews_count"];
						$despData["license_type"] = $wmstrains["license_type"];

						//echo "<pre>"; print_r($despData);exit;

						$dispD = DB::table("weedmaps_dispensaries")->where("id",$wmstrains["desp_id"])->first();
						//echo "<pre>"; print_r($dispD);exit;

						if(count($dispD) > 0 && $dispD->own_id == 0){
							$d_own_id = DB::table("master_locations")->insertGetId($despData);
							DB::table("weedmaps_dispensaries")->where("id",$wmstrains["desp_id"])->update(array("own_id" => $d_own_id,"city_id"=>$city_id));
							//exit;
						}else{

							//echo "test";exit;

							$despData = array();

	    				 	$despData["description"] = $wmstrains["description"];	
							$despData["schedule"] = json_encode($new_schedule);	
							$despData["ratings"] = $wmstrains["rating"];	
							$despData["reviews"] = $wmstrains["reviews_count"];
							$despData["license_type"] = $wmstrains["license_type"];

							DB::table("master_locations")->where("id",$dispD->own_id)->update($despData);

	    				 	$d_own_id = $dispD->own_id;
						}
						

					}else{

						//echo "sdf";exit;

						$despData = array();

    				 	$despData["description"] = $wmstrains["description"];	
						$despData["schedule"] = json_encode($new_schedule);	
						$despData["ratings"] = $wmstrains["rating"];	
						$despData["reviews"] = $wmstrains["reviews_count"];
						$despData["license_type"] = $wmstrains["license_type"];



						DB::table("master_locations")->where("id",$wmstrains["d_own_id"])->update($despData);
						
						$d_own_id = $wmstrains["d_own_id"];
						$city_id = $wmstrains["city_id"];
					}									


					if($wmstrains["m_own_id"] == 0){

						$l_s_data = array();
						$l_s_data = array(
							'strain_id' => $s_own_id,
							'location_id' => $d_own_id,
							'name' =>	$wmstrains["s_name"],
							'thc' =>	"",
							'cbd' =>	"",
							'category_name' =>	$wmstrains["category_name"]
							//'image_url' =>	$wmstrains["imageUrl"]
						);
						$m_own_id = DB::table("master_locations_strains")->insertGetId($l_s_data);


					}else{
						$m_own_id = $wmstrains["m_own_id"];
						$l_s_data = array();
						$l_s_data = array(
							'strain_id' => $s_own_id,
							'location_id' => $d_own_id,
							'name' =>	$wmstrains["s_name"],
							'thc' =>	"",
							'cbd' =>	"",
							'category_name' =>	$wmstrains["category_name"]
							//'image_url' =>	$wmstrains["imageUrl"]
						);

						DB::table("master_locations_strains")->where("id",$m_own_id)->update($l_s_data);
					}			
					//echo $m_own_id;exit;
					$prices = json_decode($wmstrains["prices"]); 

					//echo "<pre>"; print_r($prices);exit;

					$units = array("gram"=>1,"two_grams"=>2,"eighth"=>7,"quarter"=>8,"half_ounce"=>9,"ounce"=>10);

					foreach ($prices as  $key => $value){

						$mass_id = $units[$key];
		    			$data_price =  array();
		    			$data_price['city_id'] = $city_id; 
		    		 	$data_price['strain_id'] = $s_own_id;
		    			$data_price['menu_id'] = $m_own_id; 
		    		  	$data_price['location_id'] = $d_own_id;
		    		 	$data_price['mass_id'] = $mass_id;
		    		 	$data_price['price'] = $value;

		    		 	//echo "<pre>"; print_r($data_price);exit;

		    		 	$query = DB::table("master_prices");

		    		 	$query->where("city_id",$data_price['city_id']);
		    		 	$query->where("strain_id",$data_price['strain_id']);
		    		 	$query->where("menu_id",$data_price['menu_id']);
						$query->where("location_id",$data_price['location_id']);
						$query->where("mass_id",$data_price['mass_id']);

						$dis11 = $query->first();

						//echo "<pre>"; print_r(count($dis11));exit;
						
						if (count($dis11) == 0){  
							$master_prices_id = DB::table("master_prices")->insertGetId($data_price);
						}else{
							$master_prices_id = $dis11->id;
							DB::table("master_prices")->where("id",$dis11->id)->update($data_price);
						}
						
						$data_price["master_prices_id"] = $master_prices_id;
						DB::table("master_prices_history")->insert($data_price);

					}

					if($wmstrains["b_own_id"] == 0 && isset($wmstrains["vendor_id"]) && !empty($wmstrains["vendor_id"]) ){

						$b_new = array();
						$b_new["name"] = $wmstrains["vendor_name"];
						$b_new["source"] = "weedmaps";

						$dis = DB::table("master_brands")->where("name",$wmstrains["vendor_name"])->first();

						if (count($dis) > 0){ 
							$b_own_id = $dis->id;
						}else{
							$b_own_id = DB::table("master_brands")->insertGetId($b_new);
						}

						DB::table("weedmaps_products")->where("id",$wmstrains["id"])->update(array("b_own_id" => $b_own_id));

					}else{
						$b_own_id = $wmstrains["b_own_id"];
					}

					if($b_own_id > 0){	  

						$query = DB::table("master_brands_strains");

						$query->where("menu_id", $m_own_id);
						$query->where("strain_id", $s_own_id);
						$query->where("brand_id", $b_own_id);

						$dis = $query->first();

						if (count($dis) == 0){
							$l_s_data = array();
							$l_s_data = array('menu_id' => $m_own_id,'strain_id' => $s_own_id, 'location_id' => $d_own_id, "brand_id"=>$b_own_id);
							DB::table("master_brands_strains")->insert($l_s_data);
						}
					}

					DB::table("weedmaps_products")->where("id",$wmstrains["id"])->update(array("own_id" => $s_own_id,"d_own_id"=>$d_own_id,"b_own_id"=>$b_own_id,"m_own_id" => $m_own_id));

					CRUDBooster::redirect(CRUDBooster::adminPath()."/missing-strains","Strain Added to master Successfully!","success");

				}


			}


	    	}else{
	    		CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Strain Name required !","warning");
	    	}
	    	

	    }

	    public function getCreateMaster($id) {

	    	echo $id;exit;

	    	$wp_strain = DB::table('weedmaps_products')
            			->where('id', $id)
            			->first();

           	$in_array = array("name" => $wp_strain->name, "category" => strtolower($wp_strain->category_name),"source"=>"weedmaps");
           	$own_id = DB::table('master_strains')->insertGetId($in_array);

           	DB::table('weedmaps_products')
            ->where('id', $id)
            ->update(['own_id' => $own_id]);

            CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Strain Created Successfully !","success");

            //echo "<pre>"; print_r($wp_strain);exit;

	    }

	    //By the way, you can still create your own method in here... :) 


	}