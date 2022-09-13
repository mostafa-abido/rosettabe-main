<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Auth\LoginController;
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

if (App::environment('production')) {
  URL::forceScheme('https');
}

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('forgot/password', [LoginController::class, 'forgot'])->name('forgot');
Route::post('reset/password', [LoginController::class, 'reset'])->name('reset');

Route::post('/upload', [\App\Http\Controllers\Api\AttachmentController::class, 'upload']);

$filemanagerPrefix = '\App\Http\Controllers\Api\FileManagerController';
$filemanagerAttachmentsPrefix = '\App\Http\Controllers\Api\FileManagerAttachmentsController';

Route::group([
  'as'     => 'filemanager.',
  'prefix' => 'filemanager',
], function () use ($filemanagerPrefix) {
  Route::get('/', ['uses' => $filemanagerPrefix . '@index',              'as' => 'index']);
  Route::get('/links', ['uses' => $filemanagerPrefix . '@links',              'as' => 'index']);
  Route::post('files', ['uses' => $filemanagerPrefix . '@files',              'as' => 'files']);
  Route::post('new_folder', ['uses' => $filemanagerPrefix . '@new_folder',         'as' => 'new_folder']);
  Route::post('delete_file_folder', ['uses' => $filemanagerPrefix . '@delete', 'as' => 'delete']);
  Route::post('move_file', ['uses' => $filemanagerPrefix . '@move',          'as' => 'move']);
  Route::post('rename_file', ['uses' => $filemanagerPrefix . '@rename',        'as' => 'rename']);
  Route::post('upload', ['uses' => $filemanagerPrefix . '@upload',             'as' => 'upload']);
  Route::post('crop', ['uses' => $filemanagerPrefix . '@crop',             'as' => 'crop']);
});

Route::group([
  'as'     => 'filemanager-attachments.',
  'prefix' => 'filemanager-attachments',
], function () use ($filemanagerAttachmentsPrefix) {
  Route::get('/', ['uses' => $filemanagerAttachmentsPrefix . '@index',              'as' => 'index']);
  Route::post('files', ['uses' => $filemanagerAttachmentsPrefix . '@files',              'as' => 'files']);
  Route::post('new_folder', ['uses' => $filemanagerAttachmentsPrefix . '@new_folder',         'as' => 'new_folder']);
  Route::post('delete_file_folder', ['uses' => $filemanagerAttachmentsPrefix . '@delete', 'as' => 'delete']);
  Route::post('move_file', ['uses' => $filemanagerAttachmentsPrefix . '@move',          'as' => 'move']);
  Route::post('rename_file', ['uses' => $filemanagerAttachmentsPrefix . '@rename',        'as' => 'rename']);
  Route::post('upload', ['uses' => $filemanagerAttachmentsPrefix . '@upload',             'as' => 'upload']);
  Route::post('crop', ['uses' => $filemanagerAttachmentsPrefix . '@crop',             'as' => 'crop']);
});

Route::middleware('auth:sanctum')->group(function () {
  Route::get('user', [LoginController::class, 'user'])->name('user');
  Route::apiResources([
    'notes' => \App\Http\Controllers\Api\NoteController::class,
    'polls' => \App\Http\Controllers\Api\PollController::class,
    'users' => \App\Http\Controllers\Api\UserController::class,
    'guests' => \App\Http\Controllers\Api\GuestController::class,
    'homes' => \App\Http\Controllers\Api\HomeController::class,
    'colleagues' => \App\Http\Controllers\Api\ColleaguesController::class,
    'posts' => \App\Http\Controllers\Api\PostController::class,
    'comments' => \App\Http\Controllers\Api\CommentController::class,
    'requests' => \App\Http\Controllers\Api\RequestController::class,
    'chats' => \App\Http\Controllers\Api\ChatController::class,
    'messages' => \App\Http\Controllers\Api\MessageController::class,
    'attachments' => \App\Http\Controllers\Api\AttachmentController::class,
    'resources' => \App\Http\Controllers\Api\ResourceController::class,
  ]);
  Route::get('notifications', [\App\Http\Controllers\Api\NotificationController::class, 'list']);
  Route::get('notification', [\App\Http\Controllers\Api\NotificationController::class, 'read']);
  Route::post('notification', [\App\Http\Controllers\Api\NotificationController::class, 'store']);
  Route::post('read', [\App\Http\Controllers\Api\MessageController::class, 'messageSetSeen']);
  Route::post('set-message-seen', [\App\Http\Controllers\Api\MessageController::class, 'messageSetSeen']);
  Route::post('attach/option/{option}', [\App\Http\Controllers\Api\PollController::class, 'attachUserOption']);
  Route::post('detach/option/{option}', [\App\Http\Controllers\Api\PollController::class, 'detachUserOption']);
  Route::post('posts/{post}/like', [\App\Http\Controllers\Api\PostController::class, 'attachLike']);
  Route::post('posts/{post}/complete', [\App\Http\Controllers\Api\PostController::class, 'reactToPost']);
  Route::get('posts/{post}/comments', [\App\Http\Controllers\Api\CommentController::class, 'index']);
});
