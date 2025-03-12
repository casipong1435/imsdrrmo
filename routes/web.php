<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [UserController::class, 'redirectToLogin']);

Route::middleware(['auth', 'user_role:team'])->prefix('team')->group(function(){
    Route::controller(TeamController::class)->group(function(){
        #GET REQUESTS
            Route::get('/dashboard', 'dashboard')->name('team.dashboard');
        #END GET REQUESTS

        #POST REQUESTS
            Route::post('/create-incident-report', 'createIncidentReport')->name('team.createIncidentReport');
        #END POST REQUESTS

        #PUT/PATCH REQUESTS
            Route::put('/update-incident-report/{id}', 'updateIncidentReport')->name('team.updateIncidentReport');
            Route::put('/update-incident-description', 'editIncidentDesctiption')->name('team.editIncidentDesctiption');
            
        #END PUT/PATCH REQUESTS

        Route::controller(ReportController::class)->group(function(){
            Route::get('/incident-report', 'DownloadIncidentReport')->name('team.DownloadIncidentReport');
        });

    });
});

Route::middleware(['auth', 'user_role:admin'])->prefix('admin')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
        Route::get('/account', 'account')->name('admin.account');
        Route::get('/certificate', 'certificate')->name('admin.certificate');
        Route::get('/log', 'log')->name('admin.log');

        //RECORDS
        Route::prefix('record')->group(function(){
            Route::get('/incident', 'incident')->name('admin.incident');
            Route::get('/supply', 'supply')->name('admin.supply');
            Route::get('/calamity-alert', 'alert')->name('admin.alert');
        });

        //POST REQUEST
        Route::post('/add-acount', 'addAccount')->name('admin.addAccount');
        Route::post('/add-item', 'addItem')->name('admin.addItem');
        Route::post('/send-sms', 'sendSMS')->name('admin.sendSMS');

        //PUT REQUEST
        Route::put('/edit-acount/{id}', 'editAccount')->name('admin.editAccount');
        Route::put('/edit-item/{id}', 'editItem')->name('admin.editItem');
        Route::put('/edit-item-quantity/{id}', 'editItemQuantity')->name('admin.editItemQuantity');
        Route::put('/change-account-password/{id}', 'changeAccountPassword')->name('admin.changeAccountPassword');
        Route::put('/update-incident-description', 'editIncidentDesctiption')->name('admin.editIncidentDesctiption');
        Route::put('/return-item', 'returnItem')->name('admin.returnItem');
        
        //DELETE REQUEST
        Route::delete('/delete-acount/{id}', 'deleteAccount')->name('admin.deleteAccount');
        Route::delete('/delete-item/{id}', 'deleteItem')->name('admin.deleteItem');
        Route::delete('/delete-all-log', 'deleteAllLog')->name('admin.deleteAllLog');

        //API

        Route::get('/filter-supply-activity', 'filterSupplyActivity')->name('admin.filterSupplyActivity');
        
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('/certificate-of-damage', 'DownloadCertificate')->name('admin.DownloadCertificate');
        Route::get('/incident-report', 'DownloadIncidentReport')->name('admin.DownloadIncidentReport');
        Route::get('/supply-activity', 'DownloadSupplyActivity')->name('admin.DownloadSupplyActivity');
    });
});

Route::middleware(['auth', 'user_role:resident'])->prefix('user')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('/dashboard', 'dashboard')->name('user.dashboard');

        //POST REQUEST

        Route::post('/submit-request', 'requestCertificate')->name('user.requestCertificate');
    });
});

require __DIR__.'/auth.php';
