<?php

use App\Http\Controllers\Backstage\CampaignsController;
use App\Http\Controllers\Backstage\DashboardController;
use App\Http\Controllers\Backstage\GameController;
use App\Http\Controllers\Backstage\PrizeController;
use App\Http\Controllers\Backstage\UserController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

Route::prefix('backstage')->name('backstage.')->middleware(['auth', 'setActiveCampaign'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Campaigns
    Route::get('campaigns/{campaign}/use', [CampaignsController::class, 'use'])->name('campaigns.use');
    Route::resource('campaigns', CampaignsController::class);

    Route::middleware('redirectIfNoActiveCampaign')->group(function () {
        Route::resource('games', GameController::class);
        Route::resource('prizes', PrizeController::class);
    });

    // Users
    Route::resource('users', UserController::class);
});

// Route::prefix('backstage')->middleware('setActiveCampaign')->group(function () {
//     // Account activation
//     Route::get('activate/{ott}', 'Auth\ActivateAccountController@index')->name('backstage.activate.show');
//     Route::put('activate/{ott}', 'Auth\ActivateAccountController@update')->name('backstage.activate.update');
// });

Route::get('{campaign:slug}', [FrontendController::class, 'loadCampaign']);
Route::get('/', [FrontendController::class, 'placeholder']);
