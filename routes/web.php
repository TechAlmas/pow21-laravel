<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/admin','DashboardsController@getIndex');
/*Route::get('/', function()
{
    return View::make('pages.home');
});*/

/*Route::get('/runmigration', function () {
    Artisan::call('make:migration create_store_meta_table');
    return "Migration Run Successfully";
});*/

//Route::get('/','FrontHomeController@getIndex');

//Route::post("/admin/missing-strains/save-to-master/",)
//Route::post('/admin/missing-strains/save-to-master/','admin\MissingStrainsController@SaveToMaster');
//Route::post('/admin/missing-strains/save-to-master-brand/','admin\BrandsController@SaveToMasterBrand');

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

/*Route::group(['middleware' => ['role:admin,access_backend']], function () {
});*/

 Route::group(['middleware' => ['web'], 'prefix' => config('crudbooster.ADMIN_PATH')], function () {
	Route::post('forgot', ['uses' => 'AdminController@postForgot', 'as' => 'postForgot']);
	Route::get('forgot', ['uses' => 'AdminController@getForgot', 'as' => 'getForgot']);
 });
Route::group([
    'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
    'prefix' => config('crudbooster.ADMIN_PATH'),
], function () {
Route::get('scrape-bcc', ['as' => 'scrape.bcc', 'uses' => 'ScrapeBccController@index']);
	/*Route::get('bcc-category', ['as' => 'scrape.bcc.category', 'uses' => 'ScrapeBccController@category']);*/
	Route::get('bcc-category-edit/{cat_id}', ['as' => 'scrape.bcc.category.edit', 'uses' => 'ScrapeBccController@category_edit']);
	Route::post('bcc-category-update/{cat_id}', ['as' => 'scrape.bcc.category.update', 'uses' => 'ScrapeBccController@category_update']);
	Route::get('bcc-product/{cat_id?}', ['as' => 'scrape.bcc.product', 'uses' => 'ScrapeBccController@products']);
	Route::get('bcc-product/view/{product_id}', ['as' => 'scrape.bcc.product_view', 'uses' => 'ScrapeBccController@productDetail']);
	Route::get('bcc-product/product_edit/{product_id}', ['as' => 'scrape.bcc.product_edit', 'uses' => 'ScrapeBccController@product_edit']);
	Route::post('bcc-product/product_update/{product_id}', ['as' => 'scrape.bcc.product_update', 'uses' => 'AdminBccStrainsController@product_update']);
	Route::get('scrape-product/{product_id}', ['as' => 'scrape.bcc.scrapeProduct', 'uses' => 'ScrapeBccController@scrapeCategoryDetails']);
	Route::get('bcc-product/product_remove_gallery_link/{index}/{product_id}', ['as' => 'scrape.bcc.product_remove_gallery_link', 'uses' => 'AdminBccStrainsController@product_remove_gallery_link']);

	Route::get('bcc-model/{product_id?}', ['as' => 'scrape.bcc.model', 'uses' => 'ScrapeBccController@models']);
	Route::get('bcc-model/view/{model_id}', ['as' => 'scrape.bcc.modelDetail', 'uses' => 'ScrapeBccController@modelDetail']);
	Route::get('bcc-product/model_edit/{model_id}', ['as' => 'scrape.bcc.model_edit', 'uses' => 'ScrapeBccController@model_edit']);
	Route::post('bcc-product/model_update/{product_id}', ['as' => 'scrape.bcc.model_update', 'uses' => 'ScrapeBccController@model_update']);
	Route::get('bcc-product/model_remove_video_link/{index}/{model_id}', ['as' => 'scrape.bcc.model_remove_video_link', 'uses' => 'ScrapeBccController@model_remove_video_link']);
	Route::get('bcc-product/model_remove_gallery_link/{index}/{model_id}', ['as' => 'scrape.bcc.model_remove_gallery_link', 'uses' => 'ScrapeBccController@model_remove_gallery_link']);

	Route::post('scrape-bcc-update', ['as' => 'scrape.bcc.update', 'uses' => 'ScrapeBccController@update']);
	Route::get('scrape-bcc-category/{website_id}/{action}', ['as' => 'scrape.bcc.scrapeCategory', 'uses' => 'ScrapeBccController@scrapeCategory']);
	Route::get('scrape-bcc-category-details/{website_slug}/{cat_id}', ['as' => 'scrape.bcc.scrapeCategoryDetails', 'uses' => 'ScrapeBccController@scrapeCategoryDetails']);
	
	Route::get('load_brands', ['as' => 'scrape.bcc.load_brands', 'uses' => 'AdminBccStrainsController@load_brands']);
});