<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/',[PageController::class, 'newspaper'])->name('index');
Route::get('/newspaper',[PageController::class, 'newspaper'])->name('newspaper');
Route::get('/newspaper-details/{id}',[PageController::class, 'newspaperDetails'])->name('newspaperDetails');
Route::get('/magazine',[PageController::class, 'magazine'])->name('magazine');
Route::get('/download-pdf/{id}',[PageController::class, 'downloadPdf'])->name('downloadPdf');
Route::post('/save-newsletter',[PageController::class, 'saveNewsletter'])->name('saveNewsletter');
Route::any('/get-newspapaer/{date}',[PageController::class, 'getNewspaper'])->name('getNewspaper');

Route::any('/get-cordinates',[PageController::class, 'getCordinates'])->name('getCordinates');


require "admin.php";
