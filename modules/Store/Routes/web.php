<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('store')->group(function () {
//                Route::get('quotation_to_document/{quotation}', 'StoreController@quotationToDocument');
                Route::get('record/{table}/{table_id}', 'StoreController@getRecord');
                Route::post('get_item_series', 'StoreController@getItemSeries');
            });
            Route::prefix('documents')->group(function () {
                Route::get('create/{table?}/{table_id?}', 'StoreController@tableToDocument');
            });
        });
    });
}
