<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', function (){
    return redirect('/app/');
});

Route::view('/app/{any?}', 'application')->where('any', '[\/\w\.-]*');

Route::group(['prefix' => '/admin'], function () {

    Route::get('/notify/{user}',      [\App\Http\Controllers\Admin\AdminUserNotificationsController::class, 'notifyUser'])->name('notify-user');
    Route::get('/force_delete/{id}', [\App\Http\Controllers\Admin\UserForceDeleteController::class, 'forceDelete'])->name('force-delete-user');
    $filemanagerPrefix = '\App\Http\Controllers\Api\FileManagerController';
    Route::group([
          'as'     => 'filemanager.',
          'prefix' => 'appresources',
      ], function () use ($filemanagerPrefix) {
          Route::get('/', ['uses' => $filemanagerPrefix.'@index',              'as' => 'index']);
          Route::post('files', ['uses' => $filemanagerPrefix.'@files',              'as' => 'files']);
          Route::post('new_folder', ['uses' => $filemanagerPrefix.'@new_folder',         'as' => 'new_folder']);
          Route::post('delete_file_folder', ['uses' => $filemanagerPrefix.'@delete', 'as' => 'delete']);
          Route::post('move_file', ['uses' => $filemanagerPrefix.'@move',          'as' => 'move']);
          Route::post('rename_file', ['uses' => $filemanagerPrefix.'@rename',        'as' => 'rename']);
          Route::post('upload', ['uses' => $filemanagerPrefix.'@upload',             'as' => 'upload']);
          Route::post('crop', ['uses' => $filemanagerPrefix.'@crop',             'as' => 'crop']);
      });
    $filemanagerAttachmentsPrefix = '\App\Http\Controllers\Api\FileManagerAttachmentsController';
    Route::group([
          'as'     => 'filemanager-attachments.',
          'prefix' => 'appattachments',
      ], function () use ($filemanagerAttachmentsPrefix) {
          Route::get('/', ['uses' => $filemanagerAttachmentsPrefix.'@index',              'as' => 'index']);
          Route::post('files', ['uses' => $filemanagerAttachmentsPrefix.'@files',              'as' => 'files']);
          Route::post('new_folder', ['uses' => $filemanagerAttachmentsPrefix.'@new_folder',         'as' => 'new_folder']);
          Route::post('delete_file_folder', ['uses' => $filemanagerAttachmentsPrefix.'@delete', 'as' => 'delete']);
          Route::post('move_file', ['uses' => $filemanagerAttachmentsPrefix.'@move',          'as' => 'move']);
          Route::post('rename_file', ['uses' => $filemanagerAttachmentsPrefix.'@rename',        'as' => 'rename']);
          Route::post('upload', ['uses' => $filemanagerAttachmentsPrefix.'@upload',             'as' => 'upload']);
          Route::post('crop', ['uses' => $filemanagerAttachmentsPrefix.'@crop',             'as' => 'crop']);
      });
    Voyager::routes();
});
