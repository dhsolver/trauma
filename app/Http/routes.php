<?php

/****************   Model binding into route **************************/
Route::model('article', 'App\Article');
Route::model('articlecategory', 'App\ArticleCategory');
Route::model('language', 'App\Language');
Route::model('photoalbum', 'App\PhotoAlbum');
Route::model('photo', 'App\Photo');
Route::model('user', 'App\User');
Route::model('course', 'App\Course');
Route::model('coursekey', 'App\CourseKey');
Route::model('coursedocument', 'App\CourseDocument');
Route::model('coursemodule', 'App\CourseModule');
Route::model('coursemoduledocument', 'App\CourseModuleDocument');
Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('about', 'PagesController@about');
Route::get('contact', 'PagesController@contact');
Route::get('articles', 'ArticlesController@index');
Route::get('article/{slug}', 'ArticlesController@show');
Route::get('video/{id}', 'VideoController@show');
Route::get('photo/{id}', 'PhotoController@show');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/***************    User routes  **********************************/
Route::group(['middleware' => 'auth'], function() {
    Route::get('profile', 'ProfileController@viewProfile');
    Route::post('profile', 'ProfileController@saveProfile');
    Route::post('profile/avatar', 'ProfileController@saveAvatar');

    Route::get('courses', 'CourseController@index');
    Route::get('course/{slug}', 'CourseController@show');
    Route::post('course/{course}/register', 'CourseController@register');
    Route::get('course/{slug}/browse', 'CourseController@browse');
});

/***************    Faculty routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'faculty'], function() {
    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');

    // Courses routes
    Route::get('courses/', 'Admin\CourseController@index');
    Route::get('courses/create', 'Admin\CourseController@create');
    Route::post('courses', 'Admin\CourseController@store');
    Route::get('courses/{course}/edit', 'Admin\CourseController@edit');
    Route::put('courses/{course}', 'Admin\CourseController@update');
    Route::get('courses/{course}/copy', 'Admin\CourseController@copy');

    Route::post('courses/{course}/keys', 'Admin\CourseKeyController@create');
    Route::get('courses/{course}/keys/export', 'Admin\CourseKeyController@export');
    Route::get('courses/{course}/keys/{coursekey}/disable', 'Admin\CourseKeyController@disable');
    Route::get('courses/{course}/keys/{coursekey}/enable', 'Admin\CourseKeyController@enable');

    Route::post('courses/{course}/documents', 'Admin\CourseDocumentController@store');
    Route::get('courses/{course}/documents/{coursedocument}/delete', 'Admin\CourseDocumentController@delete');

    // Course modules routes
    Route::get('courses/{course}/modules/create', 'Admin\CourseModuleController@create');
    Route::post('courses/{course}/modules', 'Admin\CourseModuleController@store');
    Route::get('courses/{course}/modules/{coursemodule}/edit', 'Admin\CourseModuleController@edit');
    Route::put('courses/{course}/modules/{coursemodule}', 'Admin\CourseModuleController@update');
    Route::get('courses/{course}/modules/{coursemodule}/delete', 'Admin\CourseModuleController@delete');


    Route::post('courses/{course}/modules/{coursemodule}/documents', 'Admin\CourseModuleDocumentController@store');
    Route::get('courses/{course}/modules/{coursemodule}/documents/{coursemoduledocument}/delete', 'Admin\CourseModuleDocumentController@delete');
});

/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {

    Route::get('users/', 'Admin\UserController@index');
    Route::get('users/{user}/edit', 'Admin\UserController@edit');
    Route::put('users/{user}', 'Admin\UserController@update');
    Route::get('users/create', 'Admin\UserController@create');
    Route::post('users', 'Admin\UserController@store');
    Route::get('users/{user}/approve', 'Admin\UserController@approve');
    Route::get('users/{user}/deny', 'Admin\UserController@deny');

    // Courses routes
    // Route::get('courses/{course}/delete', 'Admin\CourseController@delete');
    Route::get('courses/{course}/disable', 'Admin\CourseController@disable');
    Route::get('courses/{course}/enable', 'Admin\CourseController@enable');

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');

    # Article category
    Route::get('articlecategory/data', 'Admin\ArticleCategoriesController@data');
    Route::get('articlecategory/{articlecategory}/show', 'Admin\ArticleCategoriesController@show');
    Route::get('articlecategory/{articlecategory}/edit', 'Admin\ArticleCategoriesController@edit');
    Route::get('articlecategory/{articlecategory}/delete', 'Admin\ArticleCategoriesController@delete');
    Route::get('articlecategory/reorder', 'ArticleCategoriesController@getReorder');
    Route::resource('articlecategory', 'Admin\ArticleCategoriesController');

    # Articles
    Route::get('article/data', 'Admin\ArticleController@data');
    Route::get('article/{article}/show', 'Admin\ArticleController@show');
    Route::get('article/{article}/edit', 'Admin\ArticleController@edit');
    Route::get('article/{article}/delete', 'Admin\ArticleController@delete');
    Route::get('article/reorder', 'Admin\ArticleController@getReorder');
    Route::resource('article', 'Admin\ArticleController');

    # Photo Album
    Route::get('photoalbum/data', 'Admin\PhotoAlbumController@data');
    Route::get('photoalbum/{photoalbum}/show', 'Admin\PhotoAlbumController@show');
    Route::get('photoalbum/{photoalbum}/edit', 'Admin\PhotoAlbumController@edit');
    Route::get('photoalbum/{photoalbum}/delete', 'Admin\PhotoAlbumController@delete');
    Route::resource('photoalbum', 'Admin\PhotoAlbumController');

    # Photo
    Route::get('photo/data', 'Admin\PhotoController@data');
    Route::get('photo/{photo}/show', 'Admin\PhotoController@show');
    Route::get('photo/{photo}/edit', 'Admin\PhotoController@edit');
    Route::get('photo/{photo}/delete', 'Admin\PhotoController@delete');
    Route::resource('photo', 'Admin\PhotoController');

    # Users
    // Route::get('user/data', 'Admin\UserController@data');
    // Route::get('user/{user}/show', 'Admin\UserController@show');
    // Route::get('user/{user}/edit', 'Admin\UserController@edit');
    // Route::get('user/{user}/delete', 'Admin\UserController@delete');
    // Route::resource('user', 'Admin\UserController');
});
