<?php namespace App\Http\Controllers;

use Session;
use Request;
use Route;
use DB;
use CRUDbooster;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {


	public function cbInit() {
		

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = FALSE;	
		$this->button_export 	   = FALSE;	
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);		
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array();	

		$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|alpha_spaces|min:3');

		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId());

		$this->form[] = array("label"=>"Gender","name"=>"gender",'type'=>'radio','dataenum'=>'Male;Female');

		$this->form[] = array("label"=>"Birthday","name"=>"birthday",'type'=>'date');		

		$this->form[] = array("label"=>"Country","name"=>"country",'type'=>'text' );
		$this->form[] = array("label"=>"State","name"=>"state",'type'=>'text');
		$this->form[] = array("label"=>"City","name"=>"city",'type'=>'text' );

		$this->form[] = array("label"=>"Latitude","name"=>"latitude",'type'=>'text' );
		$this->form[] = array("label"=>"Longitude","name"=>"longitude",'type'=>'text' );


				
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload");	
		if (CRUDBooster::getCurrentMethod() == 'getDetail') { $this->form[] = ['label'=>'Registration date','name'=>'created_at','type'=>'date'];  }
		

		if(CRUDBooster::myPrivilegeId() == 1){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
		}else{
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name","datatable_where"=>"cms_privileges.id != 1",'required'=>true);
		}

		$this->form[] = array("label"=>"Offers and News","name"=>"is_updates",'type'=>'checkbox','dataenum'=>'1|Keep me updated on special offers and news.');
		$this->form[] = array("label"=>"Terms and Privacy","name"=>"is_terms",'type'=>'checkbox','dataenum'=>"1|Yes, I agree to POW's Terms and Privacy.");
		$this->form[] = array("label"=>"Age validation","name"=>"is_age_validation",'type'=>'checkbox','dataenum'=>'1|Are you 21+ or a valid medical marijuana patient?.');

		$this->form[] = array("label"=>"Referral","name"=>"referral_id",'type'=>'text' ,'readonly'=>true);

		$this->form[] = array("label"=>"Referrer","name"=>"referrer_id",'type'=>'text' ,'readonly'=>true);

								
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
		# END FORM DO NOT REMOVE THIS LINE

		$this->addaction = array();

		$this->addaction[] = ['label'=>'View user log','url'=>CRUDBooster::adminPath('logs?q=[name]'),'icon'=>'fa fa-flag','color'=>'success'];
				
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());		
		$this->cbView('crudbooster::default.form',$data);				
	}

	public function hook_query_index(&$query) {
		if(CRUDBooster::myPrivilegeId() != 1){
	  		$query->where("id_cms_privileges","!=","1");
	  		$query->join('dispensaries_users',function($join){
	  			$join->on('cms_users.id', '=', 'dispensaries_users.user_id')
	  			->whereIn('dispensaries_users.dispansary_id',function($query){
	  				$query->select('master_locations.id')
	  						->from('master_locations')
	  						->join('dispensaries_users','master_locations.id','dispensaries_users.dispansary_id')
	  						->where('dispensaries_users.user_id',CRUDBooster::myId())
	  						->get();
	  			});
	  		});
	  	}
	  	// else{
	  		
	  	// }
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

        Session::put('current_row_id', $id);


        return view('userdetail', compact('row', 'page_menu', 'page_title', 'command', 'id'));
    }
     public function hook_before_delete($id) {
     	 DB::table("master_users_reviews")->where("user_id",$id)->delete();
     	 DB::table("master_dispensary_reviews")->where("user_id",$id)->delete();
     }
}
