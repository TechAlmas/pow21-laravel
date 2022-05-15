<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Website;
    use App\WebsiteMeta;
    use App\CannabisCategory;
    use App\CannabisProduct;
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

	class AdminCannabisStrainsController extends \crocodicstudio\crudbooster\controllers\CBController {

		 public function __construct()
        {
            $this->website_slug = 'scrape-cannabis';
            $this->website_url = 'https://www.cannabis-nb.com/';
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
            $this->table = "cannabis_categories";
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
            $this->addaction[] = array('label'=>'Products','url'=>url("admin/cannabis-strains?cat_id=[id]"),'icon'=>'fa fa-tachometer');



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
        <div class="box-header"><div class="col-12"><form method="post" action="'.route("scrape.cannabis.update").'" autocomplete="off"> 
                    <input type="hidden" name="_token" value=" '.csrf_token().'">   
                   
                    <input type="hidden" name="website_id" value="'.$website->id .'">
                    <div class="row">
                    <div class="col-sm-10 form-group>
                        <label for="product_category">Product Category</label>
                        <input type="text" name="metas[category_url]" class="form-control" value="'.$meta_values["category_url"] .'" id="product_category" readonly>
                    </div>
                    <div class="col-sm-2">
                        <a href="'.route('scrape.cannabis.scrapeCategory',[$website->id,'category_url']) .'" style="margin-top: 30px;display: inherit;">Start Scrape</a>
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
			
			$crawler = Goutte::request('GET', $this->website_url.'/collections/cannabis');
						
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
			
			//$node_count 	= $crawler->filter('.main-nav-content')->count();
			$node = $crawler->filter('#filter-2');
			
			//$node_count = $crawler->filter('.col-md-5th.list-unstyled.accessible-megamenu-panel-group')->count();
			
			// return $node_count;
			// return dd($dom->html());
			
			/* $crawler->filter('.col-md-5th.list-unstyled.accessible-megamenu-panel-group')->each(function ($node) { */
				global $scrape_status;
				global $dom_changed;
				global $added;
				global $updated;
				global $ignored;
				
				$dom        = HtmlDomParser::str_get_html($node->html());
				if($dom){
					$li_htmls   = $dom->find('li');
					
					$node_count = count($li_htmls);
					$categories = [];
					foreach($li_htmls as $key=>$li_html){
						$parent_text     = trim($li_html->find('a',0)->plaintext);
						$slug 				  = Str::slug($parent_text, '-');
						$href 				  = trim($li_html->find('a',0)->href);
						return dd($href);
					
						$category         = CannabisCategory::firstOrNew(['url'=>$href,'website_slug'=>$this->website_slug]);
						
						if($category->exists)
						$new_model = false;
						else
						$new_model = true;
						$category->website_slug = $this->website_slug;
						$category->slug = $slug;
						$category->url = $href;
						$category->business_type = '3';
						$category->name = html_entity_decode($parent_text);
						
						
						$category->save();
						
						if($category){
							$master_category = MasterCategory::firstOrNew(['url'=>$href,'website_slug'=>$this->website_slug]);
							$master_category->website_slug = $this->website_slug;
							$master_category->slug = $slug;
							$master_category->url = $href;
							$master_category->business_type = '3';
							$master_category->name = html_entity_decode($parent_text);
							$master_category->save();
							$category->master_cat_id = $master_category->id;
							$category->save();
						}

						
						if($new_model){
							$added = $added+1;
						}else{
							if(count($category->getChanges())){
								$updated = $updated+1;
							}
						}
					   $scrape_status = true;
						// break;
					}
					//exit;
					//return dd('asdasas');			  
				}else{
					$dom_changed = 'Website Structure changed, please update scraping.';
				}
				//return false;
			//	return dd('asdasas');
			//	return false;
			// });
			$updated = $updated-1;
			$updated = $updated>0?$updated:0;
			
			$ignored = $node_count-($updated+$added);
			if($scrape_status){
				
				CRUDBooster::redirect(CRUDBooster::adminPath('cannabis-category'), $added.' records added, '.$updated.' records updated,'.$ignored.' records ignored',"success");
			}else{
				CRUDBooster::redirect(CRUDBooster::adminPath('cannabis-category'), 'Categories was not scraped.',"success");
			}
        }

        /*
    *
    */
    public function scrapeCategoryDetails(Request $request,$cat_id){
        $category = PccCategory::where(['website_slug'=>$this->website_slug,'id'=>$cat_id])->first();
		
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
        
		global $pagination_links;
		$pagination_links[] ='?page=1';
       		
        $crawler 			= Goutte::request('GET', $this->website_url.$category->url);
        $node_count 	= $crawler->filter('.row.js-equalize')->count();
		
		 $crawler->filter('.row.js-equalize')->each(function ($node) use($category) {
			 
				global $pagination_links;
				$dom                                       = HtmlDomParser::str_get_html($node->html());       
				if($dom){	
					$paginations                       = $dom->find('.pull-right.pagination li');
					//$pagination_links[] ='?page=1';
					foreach($paginations as $pkey=>$pagination){
						if(!empty($pagination->find('a',0)->href)){
							if(!in_array($pagination->find('a',0)->href,$pagination_links)){
								$pagination_links[] 	= $pagination->find('a',0)->href;
							}
						}
					}

				}
		 });
		
		foreach($pagination_links as $pkey=>$pagination_link){
			
		}
		foreach($pagination_links as $pkey=>$pagination_link){
			
			$url  = strtok($this->website_url.$category->url,'?');
			//return dd($url.$pagination_link);
			$crawler 			= Goutte::request('GET', $url.$pagination_link);
			$node_count 	= $crawler->filter('.row.js-equalize')->count();
			 
			$crawler->filter('.row.js-equalize')->each(function ($node) use($category) {
				global $scrape_status;
				global $dom_changed;
				global $added;
				global $updated;
				global $ignored;
				 
				$dom                                       = HtmlDomParser::str_get_html($node->html());       
				if($dom){		
					
					$htmls                                  = $dom->find('.col-xs-6.col-sm-4');
					
					foreach($htmls as $hkey=>$html){
						
						$title                                    = $html->find('.product-tile-text a', 0)->plaintext;
						$slug                                   = Str::slug($title, '-');
						$product1                           = CannabisProduct::firstOrNew(['slug'=>$slug,'website_slug'=>$this->website_slug]);
						if($product1->exists)
						$new_model 						 = false;
						else
						$new_model 						 = true;
						$brand 								 =  isset($html->find('.js-equalized-brand', 0)->plaintext)?$html->find('.js-equalized-brand', 0)->plaintext:'';
						$product1->cat_id                   = $category->id;
						$product1->category              = $category->name;
						$product1->cat_slug              = $category->slug;
						$product1->product_title    	 = trim($title);
						$product1->website_slug      = $this->website_slug;
						$product1->slug                     = $slug;
					  //  $product1->vendor                =  isset($dom->find('.js-equalized-brand', 0)->plaintext)?$dom->find('.js-equalized-brand', 0)->plaintext:'';
						$product1->url                        = $html->find('a.product-tile-media.image-background', 0)->href;
						$product1->image                  = isset($html->find('.product-tile-media.image-background img', 0)->src)?$html->find('.product-tile-media.image-background img', 0)->src:'';
						
						$product1->save();
						
						$parent_cat 						  			= CannabisCategory::with('parent')->find($category->id);
						$master_category				  			= MasterCategory::find($category->master_cat_id);
						
						//return dd($master_category);
						$product_name 					  		= MasterProductName::firstOrNew(['name'=>trim($title)]);
						$product_name->name 		  		= html_entity_decode(trim($title));
						$product_name->source 	  			= 'pcc';
						$product_name->product_id 		= $product1->id;
						
						$product_name->save();
						
						/* $product_type						  		= MasterProductType::firstOrNew(['name'=>trim($parent_cat->parent->name)]);
						$product_type->name 		  			= html_entity_decode(trim($parent_cat->parent->name));
						$product_type->source 		  		= 'pcc';
						$product_type->product_id   		= $product1->id;
						$product_type->save();
						
						if($master_category){
							$master_category->product_type_id = $product_type->id;
							$master_category->save();
						} */
						
						// return dd($node->html());
						$crawler1                              = Goutte::request('GET', $this->website_url.$product1->url);
						$crawler1->filter('#content')->each(function ($node1) use($category,$product1,$product_name) {
							
								//return dd($node1->html());
								$dom1                                       = HtmlDomParser::str_get_html($node1->html());
								$price = 0;
								foreach ($dom1->find('div[data-context]') as $e) { 
									$attr 				= $e->getAllAttributes();
									$datContext 	= json_decode($attr['data-context'],true);
									$price 			= $datContext['allVariants'][0]['ListPrice'];
									$price 			= str_replace(' ', '-', $price); // Replaces all spaces with hyphens.
									$price 			= preg_replace('/[^A-Za-z0-9\-]/', '', $price);
									break;
								}
									
								$product1->product_description = isset($dom1->find('.product-description', 0)->plaintext)?$dom1->find('.product-description', 0)->plaintext:'';
								$product1->price                    = $price;
								$product1->save();
								
								$info_labels = $dom1->find('.col-xs-6.col-xs-pull-6.col-md-4.col-md-pull-0  .product-infos-label');
								
								$product_attributes = $dom1->find('.col-xs-6.col-xs-pull-6.col-md-4.col-md-pull-0 .product-attribute');
								
								if($info_labels){
									foreach($info_labels as $lkey=>$info_label){
										$key     = $info_label->plaintext;
										$value  = $product_attributes[$lkey]->plaintext;
										$key                                   = Str::slug($key, '_');
										if($key && $value){
											$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>$key,'source'=>'pcc']);
											$product_meta->meta_key = trim($key);
											$product_meta->meta_value = trim($value);
											$product_meta->source = 'pcc';
											
											$product_meta->save();
										}
									}
								}
								
								$product_attributes 	= $dom1->find('.col-xs-6.col-xs-pull-6.col-md-4.col-md-pull-0 .product-attribute');
								
								$brand_data 				= $dom1->find('div[data-qa="product-brand"]',0)->plaintext;
								
								if(!empty($brand_data)){

									$master_brand1 = MasterBrand::firstOrNew(['name'=>trim($brand_data)]);
									$master_brand1->name = trim($brand_data);
									$master_brand1->source = 'pcc';
									$master_brand1->save();

									$product1->brand_id = $master_brand1->id;
									$product1->save();
									$product_name->product_brand = $master_brand1->id;
									$product_name->save();
									
									$product_meta   = ProductMeta::firstOrNew(['product_id'=>$product1->id,'meta_key'=>'brand','source'=>'pcc']);
									$product_meta->meta_key = 'brand';
									$product_meta->meta_value = trim($brand_data);
									$product_meta->source = 'cannabis';

									$product_meta->save();

								}
								
								if(!empty($category)){
									$master_strain = MasterStrain::firstOrNew(['slug'=>$product1->slug,'source'=>'ocs']);
									$master_strain->name = $product1->product_title;
									$master_strain->category = $category->name;
									$master_strain->description = $product1->product_description;
									$master_strain->slug = $product1->slug;
									$master_strain->save();
								}

								if($master_brand1 && $master_strain){
									$mms = MasterBrandsStrain::firstOrNew(['brand_id'=>$master_brand1->id,'strain_id'=>$master_strain->id]);
									$mms->brand_id = $master_brand1->id;
									$mms->save();

									$product1->master_brands_strains_id = $mms->id;
									$product1->save();
								}
							//	return dd($info_labels);
								$chartics = $dom1->find('.product-specifications p.item');
						
							
						});
					}
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
		}
		$updated = $updated-1;
		$updated = $updated>0?$updated:0;
		//return dd($added,$node_count,$updated);
		$ignored = $node_count-($updated+$added);
		if($scrape_status){
			CRUDBooster::redirect(CRUDBooster::adminPath('cannabis-strains?cat_id='.$category->id), $added.' records added, '.$updated.' records updated,'.$ignored.' records ignored',"success");
			//return redirect('admin/bcc-strains?cat_id='.$category->id);
		}else{
			 CRUDBooster::redirect(CRUDBooster::adminPath('cannabis-strains?cat_id='.$category->id), $category->name.' products was not imported. It may be website structure has been changed.'.$dom_changed,"success");
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
	
	/*
	*
	*/
	
	public function getTotalItems($data){
		// https://u2ghas8n0v-dsn.algolia.net/1/indexes/ocs_products_price_per_uom_asc/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.0&x-algolia-application-id=U2GHAS8N0V&x-algolia-api-key=0fce38bd280fd213b8a14f7a59602b7d"
			$curl = curl_init();
			$perpage = $data["per_page"];
			$collections = $data["collections"];
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $data['url'],
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "{\"facets\":\"*\",\"hitsPerPage\":\"$perpage\",\"page\": 0,\"distinct\": 20,\"analytics\": false,\"filters\": \"(collections:$collections) AND inventory_quantity > 0\"}",
			  CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Postman-Token: 65876592-60a9-48ce-b4d2-d95133ed6f44",
				"cache-control: no-cache"
			  ),
			));
		
		$response = curl_exec($curl);
		//return dd($response);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return false;
		  echo "cURL Error #:" . $err;
		}else{
			$res  = json_decode($response);
			return $res;
			return dd($res);
		}
	}	
}