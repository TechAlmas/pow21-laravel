<?php 

namespace App\Http\Controllers;

error_reporting(E_ALL ^ E_NOTICE);

use crocodicstudio\crudbooster\controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use CRUDBooster;

class FrontHomeController extends \crocodicstudio\crudbooster\controllers\CBController {

    public $blog_name = 'MioProject';

    public function getIndex() {

        $data['page_title'] = 'Mio - Strains, Dispensaries';
    	$data['page_description'] = 'This is my simple blog';
    	$data['blog_name'] = $this->blog_name;

        $sec_key = "57289d1b6580bc64a58830e09031e745";
        $time = time();

        $token = md5($sec_key.$time.$_SERVER['HTTP_USER_AGENT']);

        $data["token"] = $token;
        $data["time"] = $time;

    	return view('pages.home',$data);
    }   

}
