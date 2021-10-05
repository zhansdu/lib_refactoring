<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\Auth\LoginController')->name('login');
Route::post('cgi-bin/check.cgi', 'Api\Auth\ProxyAuthController')->name('cgi-login');

Route::middleware(['auth:api-student,api-employee'])->group(function () {
    Route::post('logout', 'Api\Auth\LogoutController')->name('logout');
    Route::get('user', 'Api\Auth\UserController')->name('user');
    Route::get('user/my-books', 'Api\Service\ShowController@userInfo')->name('my-books');

    // Admin routes
    Route::middleware(['api-admin'])->group(function () {
        //    Route::group([], function () {
        // Acquisition routes

        // Batch routes
        Route::group(['prefix' => 'batch'], function () {
            Route::get('index', 'Api\Acquisition\Batch\ShowController@index');
            Route::get('show/{id}', 'Api\Acquisition\Batch\ShowController@show');
            Route::get('last-created', 'Api\Acquisition\Batch\ShowController@lastCreated');
            Route::get('sort-fields', 'Api\Acquisition\Batch\ShowController@sortFields');
            Route::get('search-fields', 'Api\Acquisition\Batch\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Acquisition\Batch\ShowController@filterFields');

            Route::post('create', 'Api\Acquisition\Batch\ManageController@create');
            Route::put('update', 'Api\Acquisition\Batch\ManageController@update');
            Route::delete('delete/{id}', 'Api\Acquisition\Batch\ManageController@delete');

            Route::post('search', 'Api\Acquisition\Batch\SearchController@search');

            Route::get('numbers', 'Api\Acquisition\Batch\AdditionalController@numbers');
            Route::get('status/{id}', 'Api\Acquisition\Batch\AdditionalController@status');
            Route::get('statuses', 'Api\Acquisition\Batch\AdditionalController@statuses');
        });

        // Supplier routes
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('index', 'Api\Acquisition\Supplier\ShowController@index');
            Route::get('show/{id}', 'Api\Acquisition\Supplier\ShowController@show');
            Route::get('last-created', 'Api\Acquisition\Supplier\ShowController@lastCreated');
            Route::get('sort-fields', 'Api\Acquisition\Supplier\ShowController@sortFields');
            Route::get('search-fields', 'Api\Acquisition\Supplier\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Acquisition\Supplier\ShowController@filterFields');

            Route::post('create', 'Api\Acquisition\Supplier\ManageController@create');
            Route::put('update', 'Api\Acquisition\Supplier\ManageController@update');
            Route::delete('delete/{id}', 'Api\Acquisition\Supplier\ManageController@delete');

            Route::post('search', 'Api\Acquisition\Supplier\SearchController@search');
            Route::post('autocomplete', 'Api\Acquisition\Supplier\SearchController@autocomplete');

            Route::get('names', 'Api\Acquisition\Supplier\AdditionalController@names');
            Route::get('types', 'Api\Acquisition\Supplier\AdditionalController@types');
        });

        // Publisher routes
        Route::group(['prefix' => 'publisher'], function () {
            Route::get('index', 'Api\Acquisition\Publisher\ShowController@index');
            Route::get('show/{id}', 'Api\Acquisition\Publisher\ShowController@show');
            Route::get('last-created', 'Api\Acquisition\Publisher\ShowController@lastCreated');
            Route::get('sort-fields', 'Api\Acquisition\Publisher\ShowController@sortFields');
            Route::get('search-fields', 'Api\Acquisition\Publisher\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Acquisition\Publisher\ShowController@filterFields');

            Route::post('create', 'Api\Acquisition\Publisher\ManageController@create');
            Route::put('update', 'Api\Acquisition\Publisher\ManageController@update');
            Route::delete('delete/{id}', 'Api\Acquisition\Publisher\ManageController@delete');

            Route::post('search', 'Api\Acquisition\Publisher\SearchController@search');
            Route::post('autocomplete', 'Api\Acquisition\Publisher\SearchController@autocomplete');

            Route::get('names', 'Api\Acquisition\Publisher\AdditionalController@names');
        });

        // Item routes
        Route::group(['prefix' => 'item'], function () {
            Route::get('index', 'Api\Acquisition\Item\ShowController@index');
            Route::get('show/{id}', 'Api\Acquisition\Item\ShowController@show');
            Route::get('last-created', 'Api\Acquisition\Item\ShowController@lastCreated');
            Route::get('sort-fields', 'Api\Acquisition\Item\ShowController@sortFields');
            Route::get('search-fields', 'Api\Acquisition\Item\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Acquisition\Item\ShowController@filterFields');

            Route::post('create', 'Api\Acquisition\Item\ManageController@create');
            Route::put('update', 'Api\Acquisition\Item\ManageController@update');
            Route::delete('delete/{id}', 'Api\Acquisition\Item\ManageController@delete');
            Route::post('recreate', 'Api\Acquisition\Item\ManageController@recreate');

            Route::post('search', 'Api\Acquisition\Item\SearchController@search');
            Route::get('autocomplete', 'Api\Acquisition\Item\SearchController@autocomplete');

            Route::get('create-data', 'Api\Acquisition\Item\AdditionalController@createData');
            Route::get('by/batch/{batch_id}', 'Api\Acquisition\Item\AdditionalController@byBatchId');
            Route::get('specialities', 'Api\Acquisition\Item\AdditionalController@specialities');
        });

        // Reports

        // Inventory books report
        Route::group(['prefix' => 'inventory-books'], function () {
            Route::get('sort-fields', 'Api\Report\InventoryBooks\ShowController@sortFields');
            Route::get('search-fields', 'Api\Report\InventoryBooks\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Report\InventoryBooks\ShowController@filterFields');

            Route::post('search', 'Api\Report\InventoryBooks\SearchController');

            Route::post('export', 'Api\Report\InventoryBooks\ExportController');
            Route::post('print', 'Api\Report\InventoryBooks\PrintController');
        });

        Route::group(['prefix' => 'book-history'], function () {
            Route::get('sort-fields', 'Api\Report\BookHistory\ShowController@sortFields');
            Route::get('search-fields', 'Api\Report\BookHistory\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Report\BookHistory\ShowController@filterFields');

            Route::post('search', 'Api\Report\BookHistory\SearchController');

            Route::post('export', 'Api\Report\BookHistory\ExportController');
        });

        Route::group(['prefix' => 'most-read'], function () {
            Route::get('sort-fields', 'Api\Report\MostReadBooks\ShowController@sortFields');
            Route::get('search-fields', 'Api\Report\MostReadBooks\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Report\MostReadBooks\ShowController@filterFields');

            Route::post('search', 'Api\Report\MostReadBooks\SearchController');

            Route::post('export', 'Api\Report\MostReadBooks\ExportController');
        });

        Route::group(['prefix' => 'attendance'], function () {
            Route::get('virtual', 'Api\Report\Attendance\AttendanceController@getVirtualAttendance');
            Route::get('departments', 'Api\Report\Attendance\AttendanceController@getDepartments');
        });

        Route::group(['prefix' => 'barcode'], function () {
            Route::get('sort-fields', 'Api\Report\Barcode\ShowController@sortFields');
            Route::get('search-fields', 'Api\Report\Barcode\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Report\Barcode\ShowController@filterFields');

            Route::post('search', 'Api\Report\Barcode\SearchController');

            Route::post('print', 'Api\Report\Barcode\PrintController');
            Route::get('init/{id}', 'Api\Report\Barcode\InitializeController');
        });

        Route::group(['prefix' => 'service'], function () {
            Route::get('sort-fields', 'Api\Service\ShowController@sortFields');
            Route::get('search-fields', 'Api\Service\ShowController@searchFields');
            Route::get('filter-fields', 'Api\Service\ShowController@filterFields');
            Route::get('user/types', 'Api\Service\ShowController@types');

            Route::get('user/{type}/{id}', 'Api\Service\ShowController@show');

            Route::post('user/{type}/search', 'Api\Service\SearchController@search');
            Route::get('/media/search', 'Api\Service\SearchController@searchMedia');
            Route::get('/media/search/by-inventory', 'Api\Service\SearchController@getMediaWithStatusesByInventory');

            Route::post('/media/back', 'Api\Service\ActionsController@backMaterial');
            Route::post('/media/give', 'Api\Service\ActionsController@giveMaterial');
        });

        Route::group(['prefix' => 'cataloging'], function () {
            Route::post('material/search', 'Api\Cataloging\SearchController');
            Route::get('material/search-fields', 'Api\Cataloging\ShowController@searchFields');
            Route::get('material/types', 'Api\Cataloging\ShowController@getTypes');
            Route::get('material/{type}/{id}', 'Api\Cataloging\ShowController@getMaterialById');
            Route::get('material/export/{type}/{id}', 'Api\Cataloging\ShowController@exportXml');
            Route::get('material/print/{type}/{id}', 'Api\Cataloging\PrintController');
            Route::post('material/{type}/{id}/edit', 'Api\Cataloging\EditMaterialController');

            Route::get('authority', 'Api\Cataloging\AuthorityDataController');
            Route::post('material/{type}/{id}/complete', [\App\Http\Controllers\Api\Cataloging\ShowController::class, 'completeCataloging']);
            Route::get('material/{type}/{id}/history', [\App\Http\Controllers\Api\Cataloging\ShowController::class, 'madeByHistory']);
        });

        Route::prefix('ksu_report')->group(function () {
            Route::get('get', [\App\Http\Controllers\Api\Report\KsuReportController::class, 'getReport']);
            Route::get('export', [\App\Http\Controllers\Api\Report\KsuReportController::class, 'export']);
        });

        Route::prefix('stat_report')->group(function () {
            Route::get('get', [\App\Http\Controllers\Api\Report\StatReportController::class, 'getReport']);
            Route::get('export', [\App\Http\Controllers\Api\Report\StatReportController::class, 'export']);
        });

        Route::get('admin/configuration', [\App\Http\Controllers\Api\AdminController::class, 'getAdminConfigurationFile'])
            ->withoutMiddleware(['auth:api-student,api-employee', 'api-admin']);
        Route::post('admin/configuration', [\App\Http\Controllers\Api\AdminController::class, 'storeAdminConfigurationFile']);
    });
});

// Media routes
Route::group(['prefix' => 'media'], function () {
    Route::get('autocomplete', 'Api\Media\AutocompleteController@autocomplete');
    Route::post('search', 'Api\Media\SearchController@search');

    Route::get('show/{id}', 'Api\Media\ShowController@show');
    Route::post('save-excel', 'Api\Media\ExportController@export');
    Route::get('search-fields', 'Api\Media\ShowController@searchFields');
    Route::get('sort-fields', 'Api\Media\ShowController@sortFields');
    Route::get('filter-fields', 'Api\Media\ShowController@filterFields');
});
