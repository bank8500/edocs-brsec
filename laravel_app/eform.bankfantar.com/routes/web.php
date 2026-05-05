<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// หน้าแรกแสดงหน้า Login
Route::get('/', function () {
    return view('auth.login');
})->name('login_page');

// Login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// กลุ่ม Route ที่ต้องผ่านการ Login ก่อน
Route::middleware(['auth'])->group(function () {

    // หน้า admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // หน้าเอกสารสำหรับ user ทั่วไป
    Route::get('/dashboard', [DocumentController::class, 'index'])->name('dashboard');

    // จัดการ user ฝั่ง admin
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.delete');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');

    // เอกสาร
    Route::get('/folders/{id}', [DocumentController::class, 'showFolder'])->name('folders.show');
    Route::post('/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/export/{id}', [DocumentController::class, 'exportCsv'])->name('documents.export');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/preview/{id}', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/global-search', [DocumentController::class, 'globalSearch'])->name('global.search');

    // โปรไฟล์
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});