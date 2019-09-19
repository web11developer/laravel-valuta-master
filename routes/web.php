<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('map-exchange', 'HomeController@exchangeMap');
Route::post('subscribes/{id}', 'SubscribesController@addSubscribe');
Route::get('map-exchange/{id}{parent?}', 'HomeController@exchangeTable');
Route::get('exchanger-set', 'HomeController@exchangerSet');

/****************   Model binding into route **************************/
Route::model('article', 'App\Article');
Route::model('exchange', 'App\Exchangers');
//Route::model('cash', 'App\Cash');
Route::model('articlecategory', 'App\ArticleCategory');
Route::model('language', 'App\Language');
Route::model('user', 'App\User');
Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/
Route::get('/getNewCash', 'HomeController@getNewCash');
Route::get('/group/{parent?}', 'HomeController@index');
Route::get('/', 'HomeController@index');
Route::get('/ratesjson', 'HomeController@ratesjson');
Route::get('/home', 'HomeController@index');


/*** Static pages ***/
Route::get('agreement', 'PagesController@agreement');
Route::get('ad', 'PagesController@ad');

Route::get('forecasts-eur-usd', 'PagesController@forecastsEurUsd');
Route::get('forecasts-eur-usd/{id}', 'PagesController@forecastsEurUsdView');

Route::group(['prefix' => 'wave'], function () {
    Route::get('{slug}', 'PagesController@wave');
    Route::get('{slug}/{id}', 'PagesController@waveView');
});

Route::get('kase-sessions', 'PagesController@kaseSessions');
Route::get('kase-sessions/{id}', 'PagesController@kaseSessionsView');

Route::get('help', 'PagesController@help');
Route::get('contact', 'PagesController@contact');
Route::get('informer', 'PagesController@informer');

/*** Articles ***/
Route::get('articles', 'ArticlesController@index');
Route::get('articles/{articlecategory}', 'ArticlesController@byCategory');
Route::get('articles/{article}/edit', 'ArticlesController@edit');
Route::get('article/create', 'ArticlesController@create');
Route::get('article/{article}/delete', 'ArticlesController@delete');
Route::get('article/{slug}', 'ArticlesController@show');
Route::resource('article', 'ArticlesController');

/*** Exchangers ***/
Route::get('exchangers/{user}', 'ExchangersController@index');
Route::get('exchange/{exchange}/show', 'ExchangersController@show');
//Route::get('exchange/{exchange}/branches/{parent}/show', 'ExchangersController@show');
Route::get('exchange/{user}/create', 'ExchangersController@create');
Route::get('exchange/{exchange}/edit', 'ExchangersController@edit');
Route::resource('exchange', 'ExchangersController');

/*** Cash ***/
Route::get('cash/{exchange}/show', 'CashController@show');
Route::resource('cash', 'CashController');

/*** CashRates ***/
Route::post('getRates', 'CashRatesController@getRates');


/*** User ***/
Route::get('user/{user}/edit', 'UserController@edit');
Route::resource('user', 'UserController');


Route::group(['prefix' => 'auth'], function () {
// Authentication Routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('logout', [AuthController::class, 'getLogout'])->name('logout');
    Route::post('login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('register', [AuthController::class, 'getRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
    Route::get('verify/{confirm}', [AuthController::class, 'getVerify'])->name('verify');
});

Route::group(['prefix' => 'password'], function () {
    Route::get('email', [PasswordController::class, 'getEmail'])->name('email');
    Route::post('email', [PasswordController::class, 'postEmail'])->name('email.post');

});
Route::get('password/reset/{token?}', [PasswordController::class, 'getReset'])->name('password.request');
Route::post('password/reset', [PasswordController::class, 'postReset'])->name('password.reset');



/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    # Admin Dashboard
    Route::get('dashboard', [DashboardController::class,'index']);

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');

    # Exchange
    Route::get('exchange', 'Admin\ExchangeController@index');
    Route::get('exchange/data', 'Admin\ExchangeController@data');

    # Article category
    Route::get('articlecategory/data', 'Admin\ArticleCategoriesController@data');
    Route::get('articlecategory/{articlecategory}/show', 'Admin\ArticleCategoriesController@show');
    Route::get('articlecategory/{articlecategory}/edit', 'Admin\ArticleCategoriesController@edit');
    Route::get('articlecategory/{articlecategory}/delete', 'Admin\ArticleCategoriesController@delete');
    Route::get('articlecategory/reorder', 'Admin\ArticleCategoriesController@getReorder');
    Route::resource('articlecategory', 'Admin\ArticleCategoriesController');

    # Articles
    Route::get('article/data', 'Admin\ArticleController@data');
    Route::get('article/{article}/show', 'Admin\ArticleController@show');
    Route::get('article/{article}/edit', 'Admin\ArticleController@edit');
    Route::get('article/{article}/delete', 'Admin\ArticleController@delete');
    Route::get('article/reorder', 'Admin\ArticleController@getReorder');
    Route::resource('article', 'Admin\ArticleController');

    # Users
    Route::get('user/data', 'Admin\UserController@data');
    Route::get('user/{user}/show', 'Admin\UserController@show');
    Route::get('user/{user}/edit', 'Admin\UserController@edit');
    Route::get('user/{user}/delete', 'Admin\UserController@delete');
    Route::resource('user', 'Admin\UserController');
    Route::get('currency/data', 'Admin\CurrencyController@data');
    Route::resource('currency', 'Admin\CurrencyController');
    Route::post('currency/{id}/destroy', 'Admin\CurrencyController@destroy');

});
