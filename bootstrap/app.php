<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CompanyOrAdmin;
use App\Http\Middleware\EnsureCompanyHasProfile;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsApplicant;
use App\Http\Middleware\IsCompany;
use App\Http\Middleware\IsProfileCompelet;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(
            [
                'EnsureCompanyHasProfile' => EnsureCompanyHasProfile::class,
                'IsApplicant' => IsApplicant::class,
                'IsCompany' => IsCompany::class,
                'IsProfileComplete' => IsProfileCompelet::class,
                'IsAdmin' => IsAdmin::class,
                'CheckPermission' => CheckPermission::class,
                'CompanyOrAdmin' => CompanyOrAdmin::class,
            ]
            );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
