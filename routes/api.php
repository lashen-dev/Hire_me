<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//public routes
Route::get('/home', [HomeController::class, 'index']);
Route::get('/search', [SearchController::class, 'search']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

// مسارات المصادقة (Authentication Routes)
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// مسار استرجاع بيانات المستخدم
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// مسارات محمية بتسجيل الدخول
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);

    // مسارات الشركات (Company Routes)
    Route::prefix('companies')->group(function () {
        Route::apiResource('/', CompanyController::class)->parameters(['' => 'company'])->except('store');
        Route::post('{company}/jobs', [CompanyController::class, 'addJob'])->middleware([ 'CheckPermission:post-job' , 'IsProfileComplete']);
        Route::get('{company}/applicants', [CompanyController::class, 'getApplicants'])->middleware(['CheckPermission:view-applicants-company', 'IsProfileComplete']);
        Route::get('{company}/applications', [CompanyController::class, 'getApplications'])->middleware(['CheckPermission:view-applications', 'IsProfileComplete']);
        Route::get('applications/{id}/download-cv', [DownloadController::class, 'downloadCv']);

        });

    // مسارات المتقدمين (Applicant Routes)
    Route::prefix('applicants')->group(function () {
        Route::apiResource('/', ApplicantController::class)->parameters(['' => 'applicant'])->except('show' , 'store' , 'index')->middleware('IsApplicant');
        Route::get('{id}', [ApplicantController::class, 'show'])->middleware('CheckPermission:view-applicant-profile');
        Route::get('/', [ApplicantController::class, 'index'])->middleware('CheckPermission:view-applicants');
    });

    // مسارات الإدارة (Admin Routes)
    Route::prefix('admin')->middleware('IsAdmin')->group(function () {
        Route::get('companies', [AdminController::class, 'getCompanies'])->middleware('CheckPermission:view-companies');
        Route::get('applicants', [AdminController::class, 'getApplicants'])->middleware('CheckPermission:view-applicants');
        Route::delete('company/{id}', [AdminController::class, 'destroyCompany'])->middleware('CheckPermission:delete-companies');
        Route::delete('applicant/{id}', [AdminController::class, 'destroyApplicant'])->middleware('CheckPermission:delete-applicants');
        Route::delete('job/{id}', [AdminController::class, 'destroyJob'])->middleware('CheckPermission:delete-jobs');
        Route::post('notifications', [AdminController::class, 'sendNotification'])->middleware('CheckPermission:manage-users');
    });

    // مسارات الوظائف (Job Routes)
    Route::prefix('jobs')->group(function () {
        Route::apiResource('', JobController::class)->parameters(['' => 'job'])->except('show');
        Route::get('{id}', [JobController::class, 'show'])->middleware('CheckPermission:view-jobs');
        Route::post('{job}/apply', [JobController::class, 'apply'])->middleware(['IsApplicant', 'IsProfileComplete' ,  'CheckPermission:apply-job']);
        Route::get('{job}/applicants', [JobController::class, 'getApplicants']);
    });

    // مسارات الملفات الشخصية (Profile Routes)
    Route::prefix('profile')->group(function () {
        Route::get('{id}', [UserController::class, 'profile'])->middleware('CheckPermission:view-applicant-profile|view-company-profile');
        Route::put('company', [CompanyController::class, 'update'])->middleware('IsCompany');
        Route::put('applicant', [ApplicantController::class, 'update'])->middleware('IsApplicant');
    });



    // (application routes)
    Route::prefix('applications')->middleware('CompanyOrAdmin')->group(function () {
        Route::apiResource('/', ApplicationController::class)->parameters(['' => 'application'])->except('store');
    });
});
?>
