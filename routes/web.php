<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DonationsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\FoundationController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\InstitutionRoleController;
use App\Http\Controllers\InstitutionTypeController;
use App\Http\Controllers\PhilanthropistController;
use App\Http\Controllers\RelationTypeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubmissionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;


//$appDomain = 'app.' . parse_url(config('app.url'), PHP_URL_HOST);
//Route::domain($appDomain)->group(function () {
//#region app.mujp.*
//Route::get('/', function () {
//    return redirect('/login');
//});

Auth::routes(['verify' => true]);

Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('auth.logout');
Route::get('home', [StaterkitController::class, 'home'])->name('home');

Route::get('/about', [\App\Http\Controllers\Frontend\AboutController::class, 'about'])->name('frontend.about');
Route::get('/donate', [\App\Http\Controllers\Frontend\DonateController::class, 'donate'])->name('frontend.donate');
Route::get('/contact', [\App\Http\Controllers\Frontend\ContactController::class, 'contact'])->name('frontend.contact');

Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/boxed', [StaterkitController::class, 'layout_boxed'])->name('layout-boxed');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');
Route::get('/test', 'TestController@index');

//#region Front-end

Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('frontend.home');

Route::group(['prefix' => 'philanthropists'], function () {
    Route::get('/', [\App\Http\Controllers\Frontend\PhilanthropistController::class, 'index'])->name('frontend.philanthropists.index');
    Route::post('/search', [App\Http\Controllers\Frontend\PhilanthropistController::class, 'search'])->name('frontend.philanthropists.search');
    Route::get('/{id}', [App\Http\Controllers\Frontend\PhilanthropistController::class, 'show'])->name('frontend.philanthropist.show');
    Route::get('/{slug}/{id}', [App\Http\Controllers\Frontend\PhilanthropistController::class, 'showWithSlug'])->name('frontend.philanthropist.show-with-slug');
});

//#endregion


//Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {

    Route::get('/', function () {
        return redirect(route('admin.dashboard'));
    });

    Route::get('/dashboard', 'UsersController@dashboard')->name('admin.dashboard');
    Route::get('/my-account', 'UsersController@myAccountIndex')->name('admin.myAccount');
    Route::get('/users/list', 'UsersController@list')->name('admin.users.list');
    Route::get('/users/new', 'UsersController@new')->name('admin.users.new');
    Route::post('/users/update', 'UsersController@update')->name('admin.users.update');
    Route::post('/users/store', 'UsersController@store')->name('admin.users.store');
    Route::post('/users/storeWithAjax', 'UsersController@storeWithAjax')->name('admin.users.store-with-ajax');
    Route::post('/users/delete', 'UsersController@delete')->name('admin.users.delete');
    Route::get('/users/{user}', 'UsersController@edit')->name('admin.users.edit');

    //Library Routes
    Route::group(['prefix' => 'library'], function () {

        //#regionIndustries Routes
        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::post('/industries', [IndustryController::class, 'store'])->name('industries.store');
        Route::post('/industries/search', [IndustryController::class, 'search'])->name('industries.search');
        Route::get('/industries/{industry}/edit', [IndustryController::class, 'edit'])->name('industries.edit');
        Route::put('/industries/{id}', [IndustryController::class, 'update'])->name('industries.update');
        Route::delete('/industries/{industry}', [IndustryController::class, 'destroy'])->name('industries.destroy');
        //#endregion

        //#region Foundations Routes
        Route::get('/foundations', [FoundationController::class, 'index'])->name('foundations.index');
        Route::post('/foundations', [FoundationController::class, 'store'])->name('foundations.store');
        Route::post('/foundations/search', [FoundationController::class, 'search'])->name('foundations.search');
        Route::get('/foundations/{foundation}/edit', [FoundationController::class, 'edit'])->name('foundations.edit');
        Route::put('/foundations/{id}', [FoundationController::class, 'update'])->name('foundations.update');
        Route::delete('/foundations/{foundation}', [FoundationController::class, 'destroy'])->name('foundations.destroy');
        #endregion

        //#region Institutions Routes
        Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
        Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');
        Route::post('/institutions/search', [InstitutionController::class, 'search'])->name('institutions.search');
        Route::get('/institutions/{institution}/edit', [InstitutionController::class, 'edit'])->name('institutions.edit');
        Route::put('/institutions/{id}', [InstitutionController::class, 'update'])->name('institutions.update');
        Route::delete('/institutions/{institution}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');
        #endregion

        //#region InstitutionsTypes Routes
        Route::get('/institution-types', [InstitutionTypeController::class, 'index'])->name('institution-types.index');
        Route::post('/institution-types', [InstitutionTypeController::class, 'store'])->name('institution-types.store');
        Route::post('/institution-types/search', [InstitutionTypeController::class, 'search'])->name('institution-types.search');
        Route::get('/institution-types/{institutionType}/edit', [InstitutionTypeController::class, 'edit'])->name('institution-types.edit');
        Route::put('/institution-types/{id}', [InstitutionTypeController::class, 'update'])->name('institution-types.update');
        Route::delete('/institution-types/{institutionType}', [InstitutionTypeController::class, 'destroy'])->name('institution-types.destroy');
        //#endregion

        //#region InstitutionsRoles Routes
        Route::get('/institution-roles', [InstitutionRoleController::class, 'index'])->name('institution-roles.index');
        Route::post('/institution-roles', [InstitutionRoleController::class, 'store'])->name('institution-roles.store');
        Route::post('/institution-roles/search', [InstitutionRoleController::class, 'search'])->name('institution-roles.search');
        Route::put('/institution-roles/{id}', [InstitutionRoleController::class, 'update'])->name('institution-roles.update');
        Route::delete('/institution-roles/{institutionRole}', [InstitutionRoleController::class, 'destroy'])->name('institution-roles.destroy');
        //#endregion

        //#region RelationTypes Routes
        Route::get('/relation-types', [RelationTypeController::class, 'index'])->name('relation-types.index');
        Route::post('/relation-types', [RelationTypeController::class, 'store'])->name('relation-types.store');
        Route::post('/relation-types/search', [RelationTypeController::class, 'search'])->name('relation-types.search');
        Route::get('/relation-types/{relationType}/edit', [RelationTypeController::class, 'edit'])->name('relation-types.edit');
        Route::put('/relation-types/{id}', [RelationTypeController::class, 'update'])->name('relation-types.update');
        Route::delete('/relation-types/{relationType}', [RelationTypeController::class, 'destroy'])->name('relation-types.destroy');
        //#endregion

        //#region Philanthropist Routes
        Route::get('/philanthropists', [PhilanthropistController::class, 'index'])->name('philanthropists.index');
        Route::get('/philanthropists/search', [PhilanthropistController::class, 'search'])->name('philanthropists.search');
        Route::get('/philanthropists/add', [PhilanthropistController::class, 'create'])->name('philanthropists.add');
//        Route::post('/philanthropists/store', [PhilanthropistController::class, 'store'])->name('philanthropists.store');
//        Route::post('/philanthropists/searchForOption', [PhilanthropistController::class, 'searchForOption'])->name('philanthropists.searchForOption');
        Route::get('/philanthropists/{philanthropist}/edit', [PhilanthropistController::class, 'edit'])->name('philanthropists.edit');
        Route::put('/philanthropists/{philanthropist}', [PhilanthropistController::class, 'update'])->name('philanthropists.update');
        Route::post('/philanthropists/{philanthropist}/removeFile', [PhilanthropistController::class, 'removeFile'])->name('philanthropist.removeFile');
        Route::delete('/philanthropists/{philanthropist}', [PhilanthropistController::class, 'destroy'])->name('philanthropists.destroy');
        //#endregion

        //#region PhilanthropistFiles Routes
        Route::post('/philanthropistFiles/{philanthropist}/upload', [\App\Http\Controllers\PhilanthropistFileController::class, 'uploadFile'])
            ->name('philanthropist-files.upload');
        Route::post('/philanthropistFiles/{philanthropistFile}/remove', [\App\Http\Controllers\PhilanthropistFileController::class, 'removeFile'])
            ->name('philanthropist-files.remove');
        Route::post('/philanthropistFiles/{philanthropistFile}/changeFileTag', [\App\Http\Controllers\PhilanthropistFileController::class, 'changeFileTag'])
            ->name('philanthropist-files.changeFileTag');

        //#endregion

        //#region FileTag Routes
        Route::group(['prefix' => 'file-tags'], function () {
            Route::get('/', [\App\Http\Controllers\FileTagController::class, 'index'])->name('admin.file-tags.index');
            Route::post('/search', [\App\Http\Controllers\FileTagController::class, 'search'])->name('admin.file-tags.search');
            Route::post('/', [\App\Http\Controllers\FileTagController::class, 'store'])->name('admin.file-tags.store');
            Route::put('/{fileTag}', [\App\Http\Controllers\FileTagController::class, 'update'])->name('admin.file-tags.update');
            Route::delete('/{fileTag}', [\App\Http\Controllers\FileTagController::class, 'destroy'])->name('admin.file-tags.destroy');
        });
        //#endregion
    });

    //Location Routes
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', [CountryController::class, 'index']);

        //Countries Routes
        Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
        Route::get('/countries/add', [CountryController::class, 'create'])->name('countries.create');
        Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
        Route::post('/countries/search', [CountryController::class, 'search'])->name('countries.search');
        Route::get('/countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
        Route::put('/countries/{country}', [CountryController::class, 'update'])->name('countries.update');
        Route::delete('/countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');

        //States Routes
        Route::get('/states', [StateController::class, 'index'])->name('states.index');
        Route::get('/states/add', [StateController::class, 'create'])->name('states.create');
        Route::post('/states', [StateController::class, 'store'])->name('states.store');
        Route::post('/states/statesByCountry', [StateController::class, 'statesByCountry'])->name('states.statesByCountry');
        Route::post('/states/getStateOptions', [StateController::class, 'getStateOptions'])->name('states.getStateOptions');
        Route::post('/states/search', [StateController::class, 'search'])->name('states.search');
        Route::get('/states/{state}/edit', [StateController::class, 'edit'])->name('states.edit');
        Route::put('/states/{state}', [StateController::class, 'update'])->name('states.update');
        Route::delete('/states/{state}', [StateController::class, 'destroy'])->name('states.destroy');

        //Cities Routes
        Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
        Route::get('/cities/add', [CityController::class, 'create'])->name('cities.create');
        Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
        Route::post('/cities/getCityTableRows', [CityController::class, 'getCityTableRows'])->name('cities.getCityTableRows');
        Route::post('/cities/search', [CityController::class, 'search'])->name('cities.search');
        Route::get('/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
        Route::put('/cities/{city}', [CityController::class, 'update'])->name('cities.update');
        Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
    });

    //Route::resource('users', \App\Http\Controllers\UsersController::class);
});

//Member Routes
Route::group(['prefix' => 'member', 'middleware' => ['role:admin,member', 'verified']], function () {
    Route::get('/', function () {
        return redirect(route('member.dashboard'));
    });
    Route::get('/my-account', [UsersController::class, 'memberIndex'])->name('member.myAccount');

    Route::get('/dashboard', 'UsersController@memberDashboard')->name('member.dashboard');
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('member.favorites');
    Route::get('/submissions', [SubmissionsController::class, 'index'])->name('member.submissions');
    Route::get('/submissions/{philanthropist}', [SubmissionsController::class, 'view'])->name('member.submissions.view');
    Route::get('/submit', [PhilanthropistController::class, 'create'])->name('member.submit');
    Route::get('/donations', [DonationsController::class, 'index'])->name('member.donations');
    Route::post('/favorites/{philanthropist}', [FavoritesController::class, 'store'])->name('member.favorites.store');
    Route::delete('/favorites/{favorite}', [FavoritesController::class, 'destroy'])->name('member.favorites.destroy');
    Route::delete('/favorites/philanthropist/{philanthropist}',[ FavoritesController::class, 'destroyWithPhilanthropist'])->name('member.favorites.destroy_with_philanthropist');
    Route::put('/my-account', [UsersController::class, 'membersUpdate'])->name('member.my-account.update');
});

//Common Routes
Route::group(['prefix' => 'common', 'middleware' => ['role:admin,member']], function (){
    Route::post('/philanthropists/searchForOption', [PhilanthropistController::class, 'searchForOption'])->name('common.philanthropists.searchForOption');
    Route::post('/philanthropists/store', [PhilanthropistController::class, 'store'])->name('common.philanthropists.store');
});

Route::group(['prefix' => 'address'], function () {
    Route::get('/states', [AddressController::class, 'getStates'])->name('address.states');
    Route::get('/cities', [AddressController::class, 'getCities'])->name('address.cities');
});

Route::get('lang/{locale}', [LanguageController::class, 'swap']);

//#endregion app.mujp.*

//});

//Route::get('/', [\App\Http\Controllers\Guest\HomeController::class, 'index'])->name('guest.home');
//Route::get('/test', function () {
//    $users = \App\Models\User::get();
//    return view('test.index', ['users' => $users]);
//});
//

