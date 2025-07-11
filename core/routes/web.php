<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'SiteController@index')->name('home');
Route::get('pages/{slug}', 'SiteController@pages')->name('pages');
Route::get('policy/{slug}', 'SiteController@policyPages')->name('policy.pages');
Route::get('branches', 'SiteController@branches')->name('branches');
Route::get('contact', 'SiteController@contact')->name('contact');
Route::post('contact', 'SiteController@contactSubmit')->name('contact.submit');
Route::get('change/{lang?}', 'SiteController@changeLanguage')->name('lang');
Route::get('cookie-accept', 'SiteController@cookieAccept')->name('cookie.accept');
Route::get('cookie-policy', 'SiteController@cookiePolicy')->name('cookie.policy');
Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');
Route::get('maintenance', 'SiteController@maintenance')->name('maintenance');
Route::post('subscribe', 'SiteController@addSubscriber')->name('subscribe');
Route::get('session-status', 'SiteController@sessionStatus')->name('session.status');

// Support Ticket Routes
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

// Cron Job Route
Route::get('cron', [\App\Http\Controllers\CronController::class, 'cron'])->name('cron');