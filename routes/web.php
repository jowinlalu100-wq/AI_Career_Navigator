<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/career-match/{resumeId}',
    [CareerController::class, 'recommend']
)->middleware('auth');

Route::get(
    '/recommendations',
    [CareerController::class, 'recommendations']
)->middleware('auth')->name('recommendations');

Route::post(
    '/resume/upload',
    [ResumeController::class, 'upload']
)->middleware('auth');

Route::get(
    '/dashboard',
    [DashboardController::class,'index']
    )->middleware(['auth','verified'])
     ->name('dashboard');

Route::get('/upload-check', function () {
    return [
        'upload_tmp_dir' => ini_get('upload_tmp_dir'),
    ];
});

Route::middleware('auth')->group(function () {

    Route::get('/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});

Route::get(
    '/jobs-test',
    [JobController::class,'test']
);

Route::get(
    '/live-jobs',
    [JobController::class,'liveJobs']
)->middleware('auth');
Route::post(
    '/jobs/apply',
    [JobController::class, 'apply']
)->middleware('auth')->name('jobs.apply');

Route::get(
    '/career-matches',
    [CareerController::class, 'latestMatch']
)->middleware('auth');

Route::post(
    '/send-otp',
    [ProfileController::class,'sendOtp']
)->middleware('auth');

Route::post(
    '/verify-otp',
    [ProfileController::class,'verifyOtp']
)->middleware('auth');  


Route::get(
    '/resume/{id}/matches',
    [ProfileController::class,'resumeMatches']
)->middleware('auth');

Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])
    ->name('profile.photo');

Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
    ->name('profile.photo.delete');

require __DIR__.'/auth.php';