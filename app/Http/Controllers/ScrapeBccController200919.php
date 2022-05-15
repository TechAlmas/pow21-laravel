<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Session;
    use DB;
    use CRUDBooster;
    use App\Website;
    use App\WebsiteMeta;
    use App\Category;
    use App\Product;
    use App\ProductMeta;
    use App\MasterBrand;
    use App\MasterStrain;
    use App\MasterPrice;
    use App\MasterMass;
    use App\MasterBrandsStrain;
    use App\MasterCategory;
    use App\MasterProductName;
    use App\MasterProductType;
    use App\MasterLocationStrain;
    use App\MasterPriceHistory;
    use KubAT\PhpSimple\HtmlDomParser;
    use Goutte;
    use Illuminate\Support\Str;
    use Goutte\Client;
    use GuzzleHttp\Client as GuzzleClient;

    class ScrapeBccController extends \crocodicstudio\crudbooster\controllers\CBController {

        public function __construct()
        {
            $this->website_slug = 'scrape-bcc';
            $this->website_url = 'https://www.bccannabisstores.com/';
        }

       public function cbInit() {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "name";
            $this->limit = "20";
            $this->orderby = "name,asc";
            $this->where = "name,asc";
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
            $this->table = "bcc_categories";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Business Type","name"=>"business_type"];
            //$this->col[] = ["label"=>"Category","name"=>"category"];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
            $this->form[] = ['label'=>'Slug','name'=>'slug','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'readonly'];
            $this->form[] = ['label'=>'Url','name'=>'url','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'Please enter a valid URL', 'readonly'=>'readonly'];
			$this->form[] = ['label'=>'Business Type','name'=>'business_type','type'=>'select2','validation'=>'','width'=>'col-sm-10','datatable'=>'business_types,name'];
            //$this->form[] = ['label'=>'Category','name'=>'category','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            /* $this->form[] = ['label'=>'Reviews','name'=>'reviews','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Ratings','name'=>'ratings','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10']; */
            /* $this->form[] = ['label'=>'Description','name'=>'description','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Attributes','name'=>'attributes','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Images','name'=>'images','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Own Id','name'=>'own_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'master_strains,name'];
            $this->form[] = ['label'=>'Strain Id','name'=>'strain_id','type'=>'text','validation'=>'required|integer|min:0','width'=>'col-sm-10']; */
            # END FORM DO NOT REMOVE THIS LINE

  

            /* 
            | ---------------------------------------------------------------------- 
            | Sub Module
            | ----------------------------------------------------------------------     
            | @label          = Label of action 
            | @path           = Path of sub module
            | @foreign_key    = foreign key of sub table/module
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
            | @color       = Default is primary. (primary, warning, succecss, info)     
            | @showIf      = If condition when action show. Use field alias. e.g : [id] == 1
            | 
            */
            $this->addaction[] = array('label'=>'Products','url'=>url("admin/bcc-strains?cat_id=[id]"),'icon'=>'fa fa-tachometer');



            /* 
            | ---------------------------------------------------------------------- 
            | Add More Button Selected
            | ----------------------------------------------------------------------     
            | @label       = Label of action 
            | @icon        = Icon from fontawesome
            | @name        = Name of button 
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
			 $this->pre_index_html .= '<div class="row"><div class="col-lg-12 ">';
				if(Session::has('scrape_msg')){
						$this->pre_index_html .= '<div class="alert alert-success" role="alert">
										'.Session::get('scrape_msg').'</div>';
				}
            $website      = Website::where('slug',$this->website_slug)->first();
            $website_meta = WebsiteMeta::where('website_id',$website->id)->get();
            $meta_values  = $website_meta->pluck('meta_value','meta_key');
            $meta_values->all();
            $this->pre_index_html .= '<div class="box">
        <div class="box-header"><div class="col-12"><form method="post" action="'.route("scrape.bcc.update").'" autocomplete="off"> 
                    <input type="hidden" name="_token" value=" '.csrf_token().'">   
                   
                    <input type="hidden" name="website_id" value="'.$website->id .'">
                    <div class="row">
                    <div class="col-sm-10 form-group>
                        <label for="product_category">Product Category</label>
                        <input type="text" name="metas[category_url]" class="form-control" value="'.$meta_values["category_url"] .'" id="product_category" readonly>
                    </div>
                    <div class="col-sm-2">
                        <a href="'.route('scrape.bcc.scrapeCategory',[$website->id,'category_url']) .'" style="margin-top: 30px;display: inherit;">Start Scrape</a>
                    </div>
                    </div>                 
            </form></div></div></div>';
            
            
            
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
            $query->whereNotNull('parent_id');   
            $query->where('name','!=' , 'Shop all');   
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

           /*
    *
    */

    public function scrapeCategory(Request $request, $website_id, $action){
			// return dd(phpinfo());
			$crawler = Goutte::request('GET', $this->website_url);
			 global $scrape_status;
			$scrape_status = false;

			global $dom_changed;
			$dom_changed = '';
			
			global $updated;
			$updated = 0;
			
			global $added;
			$added = 0;
			
			global $ignored;
			$ignored = 0;
			
			$node_count 	= $crawler->filter('.site-navigation .navmenu.navmenu-depth-1')->count();
			
			$crawler->filter('.site-navigation .navmenu.navmenu-depth-1')->each(function ($node) {
				global $scrape_status;
				global $dom_changed;
				global $added;
				global $updated;
				global $ignored;
				$dom        = HtmlDomParser::str_get_html($node->html());
				if($dom){
					$li_htmls   = $dom->find('.navmenu-id-cannabis .navmenu.navmenu-depth-2.navmenu-meganav-items li.navmenu-item.navmenu-item-parent');

					$categories = [];
					
					foreach($li_htmls as $key=>$li_html){
						$parent_text = trim($li_html->find('.navmenu-item-text.navmenu-link-parent',0)->plaintext);
						if(strtolower($parent_text) != 'by type' && strtolower($parent_text) != 'by collection'&& strtolower($parent_text) != 'shop all'){
							$slug = Str::slug($parent_text, '-');
							$category = Category::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
							if($category->exists)
							$new_model = false;
							else
							$new_model = true;
							$category->website_slug = $this->website_slug;
							$category->slug = $slug;
							$category->business_type = '3';
							$category->name = html_entity_decode($parent_text);
							$category->save();
							
							$master_category = MasterCategory::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
							
							$master_category->website_slug = $this->website_slug;
							$master_category->slug = $slug;
							$master_category->business_type = '3';
							$master_category->name = html_entity_decode($parent_text);
							$master_category->save();
							
							$category->master_cat_id = $master_category->id;
							$category->save();
							$inner_htmls = $li_html->find('.navmenu.navmenu-depth-3.navmenu-submenu li');
							$temp_cat = [];
							foreach($inner_htmls as $key=>$inner_html){
								$text = trim($inner_html->find('a',0)->plaintext);
								$slug = Str::slug($text, '-');
								$href = trim($inner_html->find('a',0)->href);
								$temp_cat[] = ['title'=>$text,'href'=>$href];
								
								$category1 = Category::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
								$category1->website_slug = $this->website_slug;
								$category1->slug = $slug;
								$category1->business_type = '3';
								$category1->name = html_entity_decode($text);
								$category1->parent_id = $category->id;
								$category1->url = $href;
								$category1->save();
								
								if(strtolower($text) != 'shop all'){
									$master_category1 = MasterCategory::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
									$master_category1->website_slug = $this->website_slug;
									$master_category1->slug = $slug;
									$master_category1->name = html_entity_decode($text);
									$master_category1->parent_id = $master_category->id;
									//$master_category1->product_type_id = $master_category->id;
									$master_category1->url = $href;
									$master_category1->business_type = '3';
									$master_category1->save();
									$category1->master_cat_id = $master_category1->id;
									$category1->save();
								}
							}
							// $categories[] = [$parent_text => $temp_cat];
						}
						if($new_model){
						$added = $added+1;
						}else{
							if(count($category->getChanges())){
								$updated = $updated+1;
							}
						}
					
						$scrape_status = true;
					}
				}else{
					$dom_changed = 'Website Structure changed, please update scraping.';
				}
			});
			$updated = $updated-1;
			$updated = $updated>0?$updated:0;
			//return dd($added,$node_count,$updated);
			$ignored = $node_count-($updated+$added);
			if($scrape_status){
				Session::flash('scrape_msg', $added.' records added, '.$updated.' records updated,'.$ignored.' records ignored');
				return back()->withStatus(__('Categories added successfully.'));
			}else{
				Session::flash('scrape_msg', 'Categories was not scraped.');
				return back()->withStatus(__('Categories was not scraped.')); 
			}
        }

        /*
    *
    */
    public function scrapeCategoryDetails(Request $request,$cat_id){
        $category = Category::where(['website_slug'=>$this->website_slug,'id'=>$cat_id])->first();
        global $scrape_status;
        $scrape_status = false;

        global $dom_changed;
        $dom_changed = '';
		
		global $updated;
        $updated = 0;
		
		global $added;
        $added = 0;
		
		global $ignored;
        $ignored = 0;
        
        $crawler 			= Goutte::request('GET', $this->website_url.$category->url);
        $node_count 	= $crawler->filter('.productgrid--items .productgrid--item')->count();
        $crawler->filter('.productgrid--items .productgrid--item')->each(function ($node) use($category) {
            global $scrape_status;
            global $dom_changed;
            global $added;
            global $updated;
            global $ignored;
             
            $dom                                       = HtmlDomParser::str_get_html($node->html());       
            if($dom){
                $vendor                               = $dom->find('.productitem--vendor', 0)->plaintext;
				$vendor1 						   	= explode('by',$vendor);
				$product_brand 					= $vendor1[0];
				$master_brand 					= $vendor1[1];
				//echo $product_brand.' By '.$master_brand. '<br>';
                $title                                    = $dom->find('.productitem--title', 0)->plaintext;
                $slug                                   = Str::slug($title, '-');
                $product1                            = Product::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
				if($product1->exists)
				$new_model = false;
				else
				$new_model = true;
                $product1->cat_id               = $category->id;
                $product1->category           = $category->name;
                $product1->cat_slug           = $category->slug;
                $product1->product_title    	= trim($title);
                $product1->website_slug    = $this->website_slug;
                $product1->slug                  = $slug;
                $product1->vendor              = trim($vendor);
                $product1->url                     = $dom->find('.productitem--title a', 0)->href;
                $product1->image               = isset($dom->find('.productitem--image img', 0)->src)?'https:'.$dom->find('.productitem--image img', 0)->src:'';
                $product1->save();
				
				$parent_cat 						  		= Category::with('parent')->find($category->id);
				$master_category				  		= MasterCategory::find($parent_cat->master_cat_id);
				//return dd($master_category);
				$product_name 					  		= MasterProductName::firstOrNew(['name'=>trim($title)]);
				$product_name->name 		  		= html_entity_decode(trim($title));
				$product_name->source 	  		= 'bccannabisstores';
				$product_name->product_id 		= $product1->id;
				
				$product_name->save();
				
				$product_type						  = MasterProductType::firstOrNew(['name'=>trim($parent_cat->parent->name)]);
				$product_type->name 		  = html_entity_decode(trim($parent_cat->parent->name));
				$product_type->source 		  = 'bccannabisstores';
				$product_type->product_id   = $product1->id;
				$product_type->save();
				
				if($master_category){
					$master_category->product_type_id = $product_type->id;
					$master_category->save();
				}
				
                $crawler1                              = Goutte::request('GET', $this->website_url.$product1->url);
                $crawler1->filter('.product--container')->each(function ($node1) use($category,$product1,$product_name) {
                    $dom1                                       = HtmlDomParser::str_get_html($node1->html());
                    $product1->product_description = isset($dom1->find('.product-description', 0)->innertext)?$dom1->find('.product-description', 0)->innertext:'';
                    $product1->price                    = isset($dom1->find('.money', 0)->plaintext)?$dom1->find('.money', 0)->plaintext:'';
                    $product1->save();
                    
                    $chartics = $dom1->find('.product-characteristics li');
                    
                    
                    if($chartics){
                        $charitcs_array = [];
                        foreach($chartics as $c_key=>$chartic){
                            $key = $chartic->find('.product-characteristics--left',0)->plaintext;
                            $value = $chartic->find('.product-characteristics--right',0)->plaintext;
                            $charitcs_array[] = ['key'=>trim($key),'value'=>trim($value)];
							
							$meta_key 		   = Str::slug($key, '_');
							$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>$meta_key]);
                            $product_meta->meta_value = trim($value);
                            $product_meta->save();
											
							if($meta_key == 'producer'){
								$master_brand = MasterBrand::firstOrNew(['name'=>trim($value)]);
								$master_brand->name = trim($value);
								$master_brand->source = 'bccannabisstores';
								$master_brand->save();
								$product1->producer_id = $master_brand->id;
								$product1->save();
								
								$product_name->oem_brand = $master_brand->id;
								$product_name->save();
							}
							
							if($meta_key == 'brand'){
								$master_strain = MasterStrain::firstOrNew(['slug'=>$product1->slug,'source'=>'bccannabisstores']);
								$master_strain->name = $product1->product_title;
								$master_strain->category = $product1->category;
								$master_strain->description = $product1->product_description;
								$master_strain->slug = $product1->slug;
								$master_strain->save();
								//return dd('sss');
								
								$master_brand1 = MasterBrand::firstOrNew(['name'=>trim($value)]);
								$master_brand1->name = trim($value);
								$master_brand1->source = 'bccannabisstores';
								$master_brand1->save();
								
								$mms = MasterBrandsStrain::firstOrNew(['brand_id'=>$master_brand1->id,'strain_id'=>$master_strain->id]);
								$mms->brand_id = $master_brand1->id;
								$mms->save();
								
								$product1->brand_id = $master_brand1->id;
								$product1->master_brands_strains_id = $mms->id;
								$product1->save();
								$product_name->product_brand = $master_brand1->id;
								$product_name->save();
								//if($product1->id == 115){
								//return dd($product1);
								//}
							}
                        }
                        
                        if(!empty($charitcs_array)){
                            $product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'characteristics']);
                            $product_meta->meta_value = json_encode($charitcs_array);
                            $product_meta->save();
                        }
                        
                    }

					$terpenes = $dom1->find('.product-terpenes',0)->innertext;
                    
					if($terpenes){
						$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'terpene']);
						$product_meta->meta_value = json_encode($terpenes);
						$product_meta->save();
                    }
                    
                    $variants = $dom1->find('.form-options.no-js-required option');
                    if($variants){
                        $variants_array = [];
                        $variants_array1 = [];
                        $variants_prices = [];
                        foreach($variants as $v_key=>$variant){
                            $variant_txt         = trim($variant->plaintext);
							$price 					= explode('-',$variant_txt);
                            $variants_array[] = trim($variant_txt);
							if(isset($price[1]) && (trim($price[1]) != 'Sold out')){
								$variants_prices[] = trim($price[1]);
								$variants_array1[] = trim($price[0]);
							}
                        }
						
						if(!empty($variants_prices)){
							if($master_strain){
								$master_location_strain = MasterLocationStrain::firstOrNew(['slug'=>$master_strain->slug,'source'=>'bccannabisstores','strain_id'=>$master_strain->id]);
								$master_location_strain->strain_id = $master_strain->id;
								$master_location_strain->name = $master_strain->name;
								$master_location_strain->slug = $master_strain->slug;
								$master_location_strain->category_name =$master_strain->category;
								$master_location_strain->source = 'bccannabisstores';
								$master_location_strain->save();
								
								foreach($variants_prices as $vp_key=>$variants_price){
									$mass = $variants_array1[$vp_key];
									if($mass){
										$master_mass = MasterMass::firstOrNew(['name'=>$mass]);
										$master_mass->save();
										//return dd($master_mass);
										$master_price = MasterPrice::firstOrNew(['strain_id'=>$master_strain->id]);
										$master_price->strain_id = $master_strain->id;
										$variants_price =  preg_replace("/[^0-9.]/", "", $variants_price);
										$master_price->price 		 = $variants_price;
										$master_price->mass_id  = $master_mass->id;
										$master_price->state_id  = 2; // British Columbia
										$master_price->country_id  = 38; // Canada
										$master_price->menu_id  = $master_location_strain->id;
										$master_price->save();
										
										$master_price_history = MasterPriceHistory::firstOrNew(['strain_id'=>$master_strain->id,'master_prices_id'=>$master_price->id]);
										$master_price_history->strain_id = $master_strain->id;
										$variants_price =  preg_replace("/[^0-9.]/", "", $variants_price);
										$master_price_history->price 		 = $variants_price;
										$master_price_history->mass_id  = $master_mass->id;
										$master_price_history->state_id  = 2; // British Columbia
										$master_price_history->country_id  = 38; // Canada
										$master_price_history->menu_id  = $master_location_strain->id;
										$master_price_history->save();
										//return dd($master_price);
									}
								}
								
							}else{
								$master_strain = MasterStrain::firstOrNew(['slug'=>$product1->slug,'source'=>'bccannabisstores']);
								$master_strain->name = $product1->product_title;
								$master_strain->category = html_entity_decode($product1->category);
								$master_strain->description = $product1->product_description;
								$master_strain->slug = $product1->slug;
								$master_strain->source = 'bccannabisstores';
								$master_strain->save();
								
								$master_location_strain = MasterLocationStrain::firstOrNew(['slug'=>$master_strain->slug,'source'=>'bccannabisstores','strain_id'=>$master_strain->id]);
								$master_location_strain->strain_id = $master_strain->id;
								$master_location_strain->name = $master_strain->name;
								$master_location_strain->slug = $master_strain->slug;
								$master_location_strain->category_name =$master_strain->category;
								$master_location_strain->source = 'bccannabisstores';
								$master_location_strain->save();
								
								foreach($variants_prices as $vp_key=>$variants_price){
									$mass = $variants_array1[$vp_key];
									if($mass){
										$master_mass = MasterMass::firstOrNew(['name'=>$mass]);
										$master_mass->save();
										//return dd($master_mass);
										$master_price = MasterPrice::firstOrNew(['strain_id'=>$master_strain->id]);
										$master_price->strain_id = $master_strain->id;
										$variants_price =  preg_replace("/[^0-9.]/", "", $variants_price);
										$master_price->price 		 = $variants_price;
										$master_price->mass_id  = $master_mass->id;
										$master_price->state_id  = 2; // British Columbia
										$master_price->country_id  = 38; // Canada
										$master_price->menu_id  = $master_location_strain->id;
										$master_price->save();
										
										$master_price_history = MasterPriceHistory::firstOrNew(['strain_id'=>$master_strain->id,'master_prices_id'=>$master_price->id,'price'=>$variants_price]);
										$master_price_history->strain_id = $master_strain->id;
										//$variants_price =  preg_replace("/[^0-9.]/", "", $variants_price);
										$master_price_history->price 		 = $variants_price;
										$master_price_history->mass_id  = $master_mass->id;
										$master_price_history->state_id  = 2; // British Columbia
										$master_price_history->country_id  = 38; // Canada
										$master_price_history->menu_id  = $master_location_strain->id;
										$master_price_history->save();
										//return dd($master_price);
									}
								}
							}
						}
                        if(!empty($variants_array)){
                            $product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'variants']);
                            $product_meta->meta_value = json_encode($variants_array);
                            $product_meta->save();
                        }
                        //return dd($product_meta);
                    }
                    
                    $sliders = $dom1->find('.product-gallery--navigation button');
                    
                    if($sliders){
                        $sliders_array = [];
                        foreach($sliders as $v_key=>$slider){
                            $img_src = $slider->find('img',0)->src;
                            $sliders_array[] = ['image'=>trim($img_src)];
                        }
                        
                        if(!empty($sliders_array)){
                            $product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'gallery']);
                            $product_meta->meta_value = json_encode($sliders_array);
                            $product_meta->save();
                        }
                    }
                    
                    $short_descriptions = $dom1->find('.product-short-description');
                    
                    if($short_descriptions){
                        $s_description_array = [];
                        foreach($short_descriptions as $sd_key=>$short_description){
                            $text = $short_description->plaintext;
                            //return dd($text);
                            $s_description_array[] = ['text'=>trim($text)];
                        }
                        
                        if(!empty($s_description_array)){
                            $product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'short_descriptions']);
                            $product_meta->meta_value = json_encode($s_description_array);
                            $product_meta->save();
                            //return dd($product_meta);
                        }
                    }
                    
                    
                    $additional_infos = $dom1->find('.product-form--additional-info span');
                    
                    if($additional_infos){
                        $additional_info_array = [];
                        foreach($additional_infos as $ai_key=>$additional_info){
                            $text = $additional_info->plaintext;
                            //return dd($text);
                            $additional_info_array[] = ['value'=>trim($text)];
							
							if(isset($additional_info->attr['data-product-volume-equivalency'])){
								$meta_key 		   = 'quantity_note';
								$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>$meta_key]);							
							}else{
								$meta_key 		   = 'sku';
								$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>$meta_key]);
							}
                            $product_meta->meta_value = trim($text);
                            $product_meta->save();
							
                        }
                        
                        if(!empty($additional_info_array)){
                            $product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'additional_info']);
                            $product_meta->meta_value = json_encode($additional_info_array);
                            $product_meta->save();
                            //return dd($product_meta);
                        }
                    }
                    
                });
				
				if($new_model){
					$added = $added+1;
				}else{
					if(count($product1->getChanges())){
						$updated = $updated+1;
					}
				}
				
                $scrape_status = true;
            }else{
                $dom_changed = 'Website Structure changed, please update scraping.';
            }
        });
		$updated = $updated-1;
		$updated = $updated>0?$updated:0;
		//return dd($added,$node_count,$updated);
		$ignored = $node_count-($updated+$added);
		if($scrape_status){
			CRUDBooster::redirect(CRUDBooster::adminPath('bcc-strains?cat_id='.$category->id), $added.' records added, '.$updated.' records updated,'.$ignored.' records ignored',"success");
			//return redirect('admin/bcc-strains?cat_id='.$category->id);
		}else{
			 CRUDBooster::redirect(CRUDBooster::adminPath('bcc-strains?cat_id='.$category->id), $category->name.' products was not imported. It may be website structure has been changed.'.$dom_changed,"success");
		}
    }

    /*
    *
    */
    public function products(Request $request,$cat_id = 0){
        $category  = Category::find($cat_id);
        if($cat_id){
            $query = Product::where(['cat_id'=>$cat_id,'website_slug'=>$this->website_slug]);
        }else{
            $query = Product::where(['website_slug'=>$this->website_slug]);
        }
        if($request->has('s')){
            $query->where(function($query2) use($request){
                return $query2
                ->orWhere('product_title', 'like', "%{$request->s}%")
                ->orWhere('product_description', 'like', "%{$request->s}%");
            });
        }
        $products = $query->paginate(15);
       
        return view('scrape.product',compact('products','category'))->withInput($request); 
    }

}