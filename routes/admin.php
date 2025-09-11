<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\NewspaperController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\MagazineController;
use App\Http\Controllers\Admin\NewsletterController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('admin')->group(function () {

    #account setup
    Route::get('/',[AdminController::class, 'login'])->name('admin.login');
    Route::get('/login',[AdminController::class, 'login'])->name('admin.login');

    #dashboard setup
    Route::get('/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin-login',[AdminController::class, 'admin_login'])->name('admin.admin_login');
    Route::get('/logout',[AdminController::class, 'logout'])->name('admin.logout');
	
	#settings
    Route::get('/settings',[ProfileController::class, 'settings'])->name('admin.settings');
	Route::post('/save-setting',[ProfileController::class, 'saveSetting'])->name('admin.save-setting');

    #update profile
    Route::get('/update-profile',[ProfileController::class, 'updateProfile'])->name('admin.update-profile');
    Route::post('/save-profile',[ProfileController::class, 'saveProfile'])->name('admin.save-profile');

    #change password
    Route::get('/change-password',[ProfileController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/update-password',[ProfileController::class, 'updatePassword'])->name('admin.dashboard');
	
	#ajax
Route::post('/change-status',[AjaxController::class, 'changeStatus'])->name('admin.change-status');
Route::post('/delete-record',[AjaxController::class, 'deleteRecord'])->name('admin.delete-record');
	
	#newspapers
	Route::get('/newspapers',[NewspaperController::class, 'getList'])->name('admin.newspapers');
	Route::any('/newspapers_paginate',[NewspaperController::class, 'listPaginate'])->name('admin.newspapers_paginate');
	Route::any('/edit-newspaper/{row_id}',[NewspaperController::class, 'editPage'])->name('admin.edit-newspaper');
	Route::any('/add-newspaper',[NewspaperController::class, 'addPage'])->name('admin.add-newspaper');
	Route::any('/newspaper-upload',[NewspaperController::class, 'newspaperUpload'])->name('admin.newspaperUpload');
	Route::any('/get-newspaper-images',[NewspaperController::class, 'getNewspaperImage'])->name('admin.getNewspaperImage');
	Route::any('/update-newspaper-images-order',[NewspaperController::class, 'updateNewspaperImageOrder'])->name('admin.updateNewspaperImageOrder');
	Route::any('/edit-newspaper-page/{row_id}',[NewspaperController::class, 'editNewspaperPage']);
	Route::any('/save-newspaper-cordinates',[NewspaperController::class, 'saveNewspaperPageCordinates']);
	
	#magazines
	Route::get('/magazines',[MagazineController::class, 'getList'])->name('admin.magazines');
	Route::any('/magazines_paginate',[MagazineController::class, 'listPaginate'])->name('admin.magazines_paginate');
	Route::any('/edit-magazine/{row_id}',[MagazineController::class, 'editPage'])->name('admin.edit-magazine');
	Route::any('/add-magazine',[MagazineController::class, 'addPage'])->name('admin.add-magazine');
	
	#magazines
	Route::get('/newsletters',[NewsletterController::class, 'getList'])->name('admin.newsletters');
	Route::any('/newsletters_paginate',[NewsletterController::class, 'listPaginate'])->name('admin.newsletters_paginate');
	Route::any('/edit-newsletter/{row_id}',[NewsletterController::class, 'editPage'])->name('admin.edit-newsletter');
	Route::any('/add-newsletter',[NewsletterController::class, 'addPage'])->name('admin.add-newsletter');
	
	
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
