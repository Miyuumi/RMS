<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormDataController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/users_find/{id}', [UserController::class, 'find']);//->middleware(['auth', 'verified']);
Route::get('/users_get', [UserController::class, 'get']);//->middleware(['auth', 'verified']);

Route::get('/groups_user/{id}', [GroupsController::class, 'group_user']);//->middleware(['auth', 'verified']);
Route::get('/groups_template/{id}', [GroupsController::class, 'group_template']);//->middleware(['auth', 'verified']);
Route::get('/user_group/{id}', [GroupsController::class, 'user_group']);//->middleware(['auth', 'verified']);

Route::get('/groups', [GroupsController::class, 'index'])->middleware(['auth', 'verified'])->name('groups');
Route::get('/groups_find/{id}', [GroupsController::class, 'find']);//->middleware(['auth', 'verified']);
Route::get('/groups_delete/{id}', [GroupsController::class, 'delete']);//->middleware(['auth', 'verified']);
Route::get('/groups_get', [GroupsController::class, 'get']);//->middleware(['auth', 'verified']);
Route::post('/groups_add', [GroupsController::class, 'create']);//->middleware(['auth', 'verified']);
Route::post('/groups_edit', [GroupsController::class, 'store']);//->middleware(['auth', 'verified']);

Route::get('/template', [TemplateController::class, 'index'])->middleware(['auth', 'verified'])->name('templates');
Route::get('/template_find/{id}', [TemplateController::class, 'find']);//->middleware(['auth', 'verified']);
Route::get('/template_delete/{id}', [TemplateController::class, 'delete']);//->middleware(['auth', 'verified']);
Route::get('/template_get', [TemplateController::class, 'get']);//->middleware(['auth', 'verified']);
Route::post('/template_add', [TemplateController::class, 'create']);//->middleware(['auth', 'verified']);
Route::post('/template_edit', [TemplateController::class, 'store']);//->middleware(['auth', 'verified']);

Route::get('/forms', [FormController::class, 'index'])->middleware(['auth', 'verified'])->name('forms');
Route::get('/forms_find/{id}', [FormController::class, 'find']);//->middleware(['auth', 'verified']);
Route::get('/forms_delete/{id}', [FormController::class, 'delete']);//->middleware(['auth', 'verified']);
Route::get('/forms_get', [FormController::class, 'get']);//->middleware(['auth', 'verified']);
Route::get('/forms_group', [FormController::class, 'group']);//->middleware(['auth', 'verified']);
Route::post('/forms_add', [FormController::class, 'create']);//->middleware(['auth', 'verified']);
Route::get('/forms_edit/{id}', [FormController::class, 'view']);//->middleware(['auth', 'verified']);
Route::get('/forms_edit/', [FormController::class, 'new']);//->middleware(['auth', 'verified']);
Route::post('/forms_save', [FormController::class, 'save']);//->middleware(['auth', 'verified']);


Route::get('/record/{id}', [FormDataController::class, 'index'])->middleware(['auth', 'verified'])->name('record');
Route::post('/record_add', [FormDataController::class, 'create']);//->middleware(['auth', 'verified']);
Route::post('/record_del', [FormDataController::class, 'delete']);//->middleware(['auth', 'verified']);
Route::get('/record_get/{id}', [FormDataController::class, 'get']);//->middleware(['auth', 'verified']);
Route::get('/record_template/{id}', [FormDataController::class, 'template']);//->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


