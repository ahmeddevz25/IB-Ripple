<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;

use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ContactMessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/view-flipbook/{type}', [IndexController::class, 'flipbook'])->name('flipbook');
Route::get('page/{sub_title}', [IndexController::class, 'page'])->name('page');
Route::get('events/{slug}', [IndexController::class, 'eventmedia'])->name('eventmedia');

Route::get('contact-us', [IndexController::class, 'contactus'])->name('contactus');
Route::post('contact', [IndexController::class, 'submit'])->name('contact.submit');
Route::post('newsletter', [IndexController::class, 'newsletterSubmit'])->name('newsletter.submit');

Route::middleware(['admin.redirect'])->group(function () {
    Route::get('admin/login', [AdminController::class, 'LoginForm'])->name('login');
    Route::post('admin/login', [AdminController::class, 'login'])->name('login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::resource('pages', PageController::class);
        Route::delete('/page/media-document/{id}', [PageController::class, 'delete_document'])->name('media-document.destroy');

        Route::resource('events', EventController::class);
        Route::resource('media-items', MediaItemController::class);
        // ✅ New Unified Route
        Route::delete('/media/{type}/{id}', [MediaItemController::class, 'deleteFile'])->name('media.file.delete');
        Route::resource('sliders', SliderController::class);

        Route::delete('/slider-image/{id}', [SliderController::class, 'deleteImage'])->name('slider.image.delete');
        //Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contactmessages');
        Route::delete('/contact-messages/delete/{id}', [ContactMessageController::class, 'destroy'])->name('contactmessages.delete');

        // Index - All Users
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

        //Roles Management
        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.delete');

        //Permissions Management
        // routes/web.php
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
        Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::post('/permissions/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete');

        Route::get('clear-cache', [AdminController::class, 'cacheclear'])->name('cacheclear');
    });
});
