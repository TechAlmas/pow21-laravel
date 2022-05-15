<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Website;
    use App\WebsiteMeta;
    use App\Category;
    use App\Product;
    use App\ProductMeta;
    use App\MasterBrand;
    use App\MasterStrain;
    use App\MasterBrandsStrain;
    use KubAT\PhpSimple\HtmlDomParser;
    use Goutte;
    use Illuminate\Support\Str;
    use Goutte\Client;
    use GuzzleHttp\Client as GuzzleClient;
	use Artisan;

	class AdminBccStrainsController extends \crocodicstudio\crudbooster\controllers\CBController {

		 public function __construct()
        {
            $this->website_slug = 'scrape-bcc';
            $this->website_url = 'https://www.bccannabisstores.com/';
        }

	    public function cbInit() {
			
			
			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "product_title";
			$this->limit = "20";
			$this->orderby = "product_title,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "bcc_products";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Name","name"=>"product_title"];
			$this->col[] = ["label"=>"Category","name"=>"category"];
			$this->col[] = ["label"=>"Business Type","name"=>"business_type"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'product_title','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Slug','name'=>'slug','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Url','name'=>'url','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'Please enter a valid URL'];
			$this->form[] = ['label'=>'Category','name'=>'category','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = array("label"=>"Photo","name"=>"image","type"=>"upload");	
			//$this->form[] = ['label'=>'Reviews','name'=>'reviews','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Ratings','name'=>'ratings','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Description','name'=>'product_description','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Attributes','name'=>'attributes','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Images','name'=>'images','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Vendor','name'=>'vendor','type'=>'text','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Own Id','name'=>'own_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'master_strains,name'];
			//$this->form[] = ['label'=>'Strain Id','name'=>'strain_id','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE


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
	        $this->script_js = "$(document).ready(function(){
            $('.editor').summernote({
                height: 300,
                popover: {
                image: [],
                link: [],
                air: []
                }
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
	        $this->pre_index_html = NULL;
	        if(Request::has('cat_id')){
	        	$cat_id = Request::get('cat_id');
	        	
		        $category = Category::where(['website_slug'=>$this->website_slug,'id'=>$cat_id])->first();
		       // scrape_msg
		        $this->pre_index_html .= '<div class="row"><div class="col-lg-6 ">';
				if(Session::has('scrape_msg')){
						$this->pre_index_html .= '<div class="alert alert-success" role="alert">
										'.Session::get('scrape_msg').'</div>';
				}
				$this->pre_index_html .='</div><div class="col-lg-6 text-right">
	                             <a href="'.route('scrape.bcc.scrapeProduct',[$category->id]).'" class="btn btn-sm btn-primary">Scrape '.$category->name.'</a>
	                        </div></div>';
	        }
	        
	        
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
	        $this->load_js = array('//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.min.js');
	        
	        
	        
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
	        $this->load_css = array('//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css');
	        
	        
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
	        $cat_id = Request::get('cat_id');
	        if(Request::has('cat_id'))
	        $query->where('cat_id',$cat_id);
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			if($column_index == 3){
				if($column_value == 2)
					return $column_value = 'Dispensary';
				else
				    return $column_value = 'Government';
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
		
		public function getDetail($product_id) {
			//Create an Auth
			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['product']   = Product::find($product_id);
			$data['category']   = Category::find($product->cat_id);
        
			$data['characteristics'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'characteristics'])->first();
		
			$data['variants'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'variants'])->first();
			
			$data['terpenes'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'terpene'])->first();
		
			$data['gallery'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'gallery'])->first();
		
			$data['additional_info'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'additional_info'])->first();
		
			$data['short_description'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'short_descriptions'])->first();
		
			
			//Please use cbView method instead view method from laravel
			$this->cbView('scrape.product_view',$data);
		}
		
		public function getEdit($product_id) {
		  //Create an Auth
		  if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		  }
		  
			$data = [];
			$data['product']   				= Product::find($product_id);
			$data['master_brands']   	= Masterbrand::find($data['product']->producer_id);
			$data['brands']   				= MasterBrandsStrain::with('brand')->groupBy('brand_id')->get();
		//	return dd($data);
			$data['category']   			= Category::find($product->cat_id);
			$data['categories']   		= Category::where('name','!=' , 'Shop all')->get();

			$data['characteristics'] 	= ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'characteristics'])->first();

			$data['variants'] 				= ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'variants'])->first();

			$data['terpenes'] 				= ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'terpene'])->first();

			$data['galleries'] 				= ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'gallery'])->first();

			$data['additional_info'] 	= ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'additional_info'])->first();

			$data['short_description'] = ProductMeta::where(['product_id'=>$product_id,'meta_key'=>'short_descriptions'])->first();
			
			$data['attributes'] 			= ProductMeta::where(['product_id'=>$product_id])->whereNotIn('meta_key', ['gallery','additional_info','characteristics','short_descriptions','terpene','variants',])->get();
			
			//return dd($data['additional_info']);
			//Please use cbView method instead view method from laravel
			$this->cbView('scrape.product_edit',$data);
		}
		
		public function product_update(Request $request, $product_id){
			
			$product              					    = Product::findOrFail($product_id);
			$product->product_title  			    = Request::get('product_title');
			$product->product_description	= Request::get('product_description');
			$product->cat_id      					= Request::get('cat_id');
			$product->producer_id      			= Request::get('producer_id');
			$product->business_type      		= Request::get('business_type');
			$master_strain 							= MasterStrain::where(['slug'=>$product->slug,'source'=>'bccannabisstores'])->first();
			$mbs 											= MasterBrandsStrain::where(['strain_id'=>$master_strain->id,'brand_id'=>$product->brand_id])->first();
			//return dd($mbs);
			if($mbs){
				$mbs->brand_id = Request::get('brand_id');
				$mbs->save();
			}
			$product->brand_id      				= Request::get('brand_id');
			if(Request::has('image')){
				$file           				= Request::file('image')->getClientOriginalName();

				$real_name      		= pathinfo($file, PATHINFO_FILENAME);

				$md5Name        	 	= md5_file(Request::file('image')->getRealPath());
				$guessExtension		= Request::file('image')->guessExtension();

				$image_name 		= $real_name.'-'.$md5Name.'.'.$guessExtension;
				$destinationPath 	= public_path('/uploads/product_images');
				Request::file('image')->move($destinationPath, $image_name);
				$product->image 	= 'uploads/product_images/'.$image_name;
			}
			
			$product->save();
			
			$galleries        				= ProductMeta::firstOrNew(['meta_key'=>'gallery','product_id'=>$product_id]);
		
			$model_images 			= [];
			if(Request::has('gallery')){
				foreach(Request::file('gallery') as $key=>$g_image){
					$file           			= $g_image->getClientOriginalName();
					$real_name         = pathinfo($file, PATHINFO_FILENAME);

					$md5Name        	= md5_file($g_image->getRealPath());
					$guessExtension	= $g_image->guessExtension();
					
					$image_name 	= $real_name.'-'.$md5Name.'.'.$guessExtension;
					$destinationPath = public_path('/uploads/product_images');
					$g_image->move($destinationPath, $image_name);
					$model_images[] = ['image'=>'uploads/product_images/'.$image_name];
				}
			}
			
			if(isset($galleries->meta_value)){
				$gallery_urls   = json_decode($galleries->meta_value,true);
				$new_gallery  = array_merge($gallery_urls,$model_images);
				$galleries->meta_value = json_encode($new_gallery);
				$galleries->save();
			}else{
				$new_gallery  = $model_images;
				$galleries->meta_value = json_encode($new_gallery);
				$galleries->save();			
			}
			
			if(Request::has('attribute')){
				$attributes = Request::get('attribute');
				if(!empty($attributes)){
					foreach($attributes as $meta_key=>$attribute){
						$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product->id,'meta_key'=>$meta_key]);
						$product_meta->meta_value = trim($attribute);
						//return dd($product_meta);
						$product_meta->save();
					}
				}
			}
			
			if(Request::has('variants')){
				$variants = Request::get('variants');
				$variants_array = [];
				foreach($variants as $v_key=>$variant){
					$variants_array[] = trim($variant);
				}
				
				if(!empty($variants_array)){
					$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product->id,'meta_key'=>'variants']);
					$product_meta->meta_value = json_encode($variants_array);
					$product_meta->save();
				}
				//return dd($product_meta);
			}
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Product Updated Successfully!","success");
			// return back()->withStatus(__('Product updated successfully.'));
		}
		
		/*
		*
		*/
		public function product_remove_gallery_link(Request $request, $index, $product_id){
			
			$galleries           = ProductMeta::where(['meta_key'=>'gallery','product_id'=>$product_id])->first();
						
			if(isset($galleries->meta_value)){
				$gallery_links 	  = json_decode($galleries->meta_value,true);
				unset($gallery_links[$index]);
				$json_arr = array_values($gallery_links);
				$gallery_links = json_encode($json_arr);
				$galleries->meta_value = $gallery_links;
				$galleries->save();
			}
			return back()->withStatus(__('Model updated successfully.'));
		}
		
		public function load_brands(){
			$term = Request::get('q');
			$brands = MasterBrand::where('name', 'like', "{$term}%")->get();
			foreach ($brands as $key => $brand) {
				# code...
				$brand_arr[] = ['id'=>$brand->id,'text'=>$brand->name];
			}
			return response()->json($brand_arr);
		}

	}