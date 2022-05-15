<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Str;
	use App\MasterCategory;
	use App\Category;
	use App\MasterProductType;
	use App\CategoryProductType;
	
	class AdminMasterCategoriesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "master_categories";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			//$this->col[] = ["label"=>"Category","name"=>"name"];
			$this->col[] = ["label"=>"Parent","name"=>"name"];
			$this->col[] = ["label"=>"Product Types","name"=>"id"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form	 = [];
			$this->form[] = ['label'=>'Category','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Webiste Slug','name'=>'website_slug','type'=>'hidden','value'=>'scrape-bcc'];
			//$this->form[] = ['label'=>'Parent','name'=>'parent_id','type'=>'select2','validation'=>'','width'=>'col-sm-10','datatable'=>'master_categories,name','value'=>'master_categories.parent_id'];
			$this->form[] = ['label'=>'Product Types','name'=>'product_types','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'master_product_types,name','multiple'=>'multiple'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Name","name"=>"name","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"You can only enter the letter only"];
			//$this->form[] = ["label"=>"Parent Id","name"=>"parent_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"parent,id"];
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
			$this->sub_module[] = ['label'=>'Add Keywords','path'=>'category-keywords','button_color'=>'warning','button_icon'=>'fa fa-pencil-square-o','parent_columns'=>'id,name','foreign_key'=>'category_id'];	

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
	        $this->style_css = ".select2-container--default .select2-selection--multiple .select2-selection__choice{color: #333}";
	        
	        
	        
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
	         return $query->whereNotNull('parent_id');
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			if($column_index == 2){
				$type = CategoryProductType::where(['cat_id'=>$column_value])->join('master_product_types as type', 'type.id', '=', 'category_product_types.product_type_id')->pluck('name')->toArray();
				if($type)
				 $column_value = implode(',',$type);
				else
				 $column_value = '';
				return $column_value;
				//return dd($column_value);
			}
				
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
			/* unset($postdata['product_types']);
			$slug 					= $this->createSlug($postdata['name']);
			$postdata['slug'] 	= $slug;
			return $postdata; */
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
			/* if(Request::has('product_types')){
				$product_types = Request::get('product_types');
				
				//foreach($product_types as $key=>$product_type){
					$type = CategoryProductType::firstOrNew(['cat_id'=>$id,'product_type_id'=>$product_types]);
					$type->save();
				//}		
			}
			return $id; */
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
			$slug 					= $this->createSlug($postdata['name'],$id);
			$postdata['slug'] 	= $slug;
			return $postdata;
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

		public function getAdd() {
			  //Create an Auth
			
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
		  
			$data = [];
			//return dd($data['master_brands']);
			//$data['category']   			    = MasterCategory::find($cat_id);
			$data['categories']   	 	    = MasterCategory::where('name','!=' , 'Shop all')->whereNotNull('parent_id')->get();
			$data['product_types']   	    = MasterProductType::all();
			$data['cat_product_type']   = [];
			
			//Please use cbView method instead view method from laravel
			$this->cbView('scrape.category_add',$data);
		}
		public function getEdit($cat_id) {
		  //Create an Auth
			
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
		  
			$data = [];
			//return dd($data['master_brands']);
			$data['category']   			    = MasterCategory::find($cat_id);
			$data['categories']   	 	    = MasterCategory::where('name','!=' , 'Shop all')->where('id','!=' , $data['category']->id)->get();
			$data['product_types']   	    = MasterProductType::all();
			$data['cat_product_type']   	= CategoryProductType::where(['cat_id'=>$cat_id])->pluck('product_type_id')->toArray();
			//return dd($data['cat_product_type']);
			
			//Please use cbView method instead view method from laravel
			$this->cbView('scrape.category_edit',$data);
		}
		
		public function postAddCategory(){
			$slug 										      = $this->createSlug(Request::get('name'));
			$master_category 					      = new MasterCategory;
			//$master_category->parent_id = Request::get('parent_id');
			$master_category->slug 		      = $slug;
			$master_category->website_slug = 'scrape-bcc';
			$master_category->parent_id       = '999';
			$master_category->name 		      = Request::get('name');
			$master_category->save();
			
			if(Request::has('product_types')){
				$product_types = Request::get('product_types');
				foreach($product_types as $key=>$product_type){
					$type = CategoryProductType::firstOrNew(['cat_id'=>$master_category->id,'product_type_id'=>$product_type]);
					$type->save();
				}		
			}
			CRUDBooster::redirect(CRUDBooster::adminPath('categories'),"Category Added Successfully!","success");
		}
		public function postUpdateCategory($category_id){
			$master_category 					= MasterCategory::find($category_id);
			$master_category->name 		= Request::get('name');
			//$master_category->parent_id = Request::get('parent_id');
			$master_category->save();

			if(Request::has('product_types')){
				$product_types  = Request::get('product_types');
				CategoryProductType::where(['cat_id'=>$category_id])->whereNotIn('product_type_id',$product_types)->delete();
				
				$mtr_ids = MasterProductType::where(['master_cat_id'=>$category_id])->whereNotIn('id',$product_types)->update(['master_cat_id' =>null]);
			    
				//return dd($mtr_ids,$product_types);
				foreach($product_types as $key=>$product_type){
					$type = CategoryProductType::firstOrNew(['cat_id'=>$category_id,'product_type_id'=>$product_type]);
					$type->save();
					
					$master_product_type = MasterProductType::find($product_type);
					$master_product_type->master_cat_id = $category_id;
					$master_product_type->save();
					
				}		
			}else{
				$type = CategoryProductType::firstOrNew(['cat_id'=>$category_id]);
				$type->delete();
			}
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Category Updated Successfully!","success");
		}
		
		public function getDetail($cat_id){
			$data = [];
			//return dd($data['master_brands']);
			$data['category']   			    = MasterCategory::find($cat_id);
			$data['categories']   	 	    = MasterCategory::where('name','!=' , 'Shop all')->where('id','!=' , $data['category']->id)->get();
			$data['cat_product_type']   	= CategoryProductType::where(['cat_id'=>$cat_id])->pluck('product_type_id')->toArray();
			
			$data['product_types']   	    = MasterProductType::whereIn('id',$data['cat_product_type'])->pluck('name')->toArray();
			
			$this->cbView('scrape.category_view',$data);
		}
		public function postAddSave1(){
			return
			$master_category = MasterCategory::find($category_id);
			$master_category->parent_id = Request::get('parent_id');
			$master_category->save();
			
			if(Request::has('product_types')){
				$product_types = Request::get('product_types');
				
				foreach($product_types as $key=>$product_type){
					$type = CategoryProductType::firstOrNew(['cat_id'=>$category_id,'product_type_id'=>$product_type]);
					$type->save();
				}		
			}else{
				$type = CategoryProductType::firstOrNew(['cat_id'=>$category_id]);
				$type->delete();
			}
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Category Updated Successfully!","success");
		}

	    //By the way, you can still create your own method in here... :) 
		
		public function createSlug($title, $id = 0)
		{
			$slug = str_slug($title);
			$allSlugs = $this->getRelatedSlugs($slug, $id);
			if (! $allSlugs->contains('slug', $slug)){
				return $slug;
			}

			$i = 1;
			$is_contain = true;
			do {
				$newSlug = $slug . '-' . $i;
				if (!$allSlugs->contains('slug', $newSlug)) {
					$is_contain = false;
					return $newSlug;
				}
				$i++;
			} while ($is_contain);
		}
		protected function getRelatedSlugs($slug, $id = 0)
		{
			return MasterCategory::select('slug')->where('slug', 'like', $slug.'%')
			->where('id', '<>', $id)
			->get();
		}


	}