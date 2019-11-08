<?php

/****************   Model binding into route **************************/
Route::model('user', 'App\User');
Route::model('course', 'App\Course');
Route::model('coursekey', 'App\CourseKey');
Route::model('comment', 'App\Comment');
Route::model('coursedocument', 'App\CourseDocument');
Route::model('coursemodule', 'App\CourseModule');
Route::model('coursemoduledocument', 'App\CourseModuleDocument');
Route::model('courseregistration', 'App\UsersCoursesRegistration');
Route::model('staticpage', 'App\StaticPage');
Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('data', 'PagesController@showStaticPage');
Route::get('education', 'PagesController@showStaticPage');
Route::get('consulting', 'PagesController@showStaticPage');
Route::get('terms', 'PagesController@showStaticPage');
Route::get('about', 'PagesController@showStaticPage');
Route::get('contact', 'PagesController@contact');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('calendar', 'CourseController@calendar');
Route::post('ipn/paypal', 'PurchaseController@handlePaypalIPN');

/***************    User routes  **********************************/
Route::group(['middleware' => 'auth'], function() {
    Route::get('profile', 'ProfileController@viewProfile');
    Route::post('profile', 'ProfileController@saveProfile');
    Route::post('profile/avatar', 'ProfileController@saveAvatar');

    Route::group(['middleware' => 'userApproved'], function() {
        Route::get('courses', 'CourseController@index');
        Route::get('course/{slug}', 'CourseController@show');
        Route::get('course/{slug}/preview', 'CourseController@preview');
        Route::post('course/{course}/register', 'PurchaseController@handleRegister');
        Route::get('course/{slug}/browse', 'CourseController@browse')->name('course.browse');
        Route::get('course/{course}/module/documents/{coursemoduledocument}/track', 'CourseController@trackProgress');
        Route::get('course/{course}/finish', 'CourseController@finish');
        Route::post('course/{course}/comments', 'CourseController@saveComment');
        Route::post('course/{course}/comments/{comment}', 'CourseController@updateComment');
        Route::get('course/{course}/comments/{comment}/delete', 'CourseController@deleteComment');
        Route::get('course/{course}/comments/{comment}/hide', 'CourseController@hideComment');
    });

    Route::get('my-courses', 'CourseController@myCourses');

    Route::post('cb/paypal/{slug}', 'PurchaseController@handlePaypalCheckout');
});

/***************    Faculty routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'faculty'], function() {
    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');

    Route::get('my-teaching', 'Admin\CourseController@myTeachings');

    // Courses routes
    Route::get('courses/', 'Admin\CourseController@index');
    Route::get('courses/create', 'Admin\CourseController@create');
    Route::post('courses', 'Admin\CourseController@store');
    Route::get('courses/{course}/edit', 'Admin\CourseController@edit');
    Route::put('courses/{course}', 'Admin\CourseController@update');
    Route::post('courses/{course}/photo', 'Admin\CourseController@updatePhoto');
    Route::get('courses/{course}/copy', 'Admin\CourseController@copy');
    Route::get('courses/{course}/instructors', 'Admin\CourseController@updateInstrcutors');

    Route::post('courses/{course}/keys', 'Admin\CourseKeyController@create');
    Route::get('courses/{course}/keys/export', 'Admin\CourseKeyController@export');
    Route::get('courses/{course}/keys/{coursekey}/disable', 'Admin\CourseKeyController@disable');
    Route::get('courses/{course}/keys/{coursekey}/enable', 'Admin\CourseKeyController@enable');


    Route::get('courses/{course}/students/export', 'Admin\CourseController@exportStudents');

    Route::get('courses/{course}/registrations/{courseregistration}/certify', 'Admin\CourseController@certifyStudent');
    Route::get('courses/{course}/registrations/{courseregistration}/uncertify', 'Admin\CourseController@uncertifyStudent');

    Route::post('courses/{course}/documents', 'Admin\CourseDocumentController@store');
    Route::get('courses/{course}/documents/{coursedocument}/delete', 'Admin\CourseDocumentController@delete');

    // Course modules routes
    Route::get('courses/{course}/modules/create', 'Admin\CourseModuleController@create');
    Route::post('courses/{course}/modules', 'Admin\CourseModuleController@store');
    Route::get('courses/{course}/modules/{coursemodule}/edit', 'Admin\CourseModuleController@edit');
    Route::get('courses/{course}/modules/{coursemodule}/show', 'Admin\CourseModuleController@show');
    Route::get('courses/{course}/modules/{coursemodule}/hide', 'Admin\CourseModuleController@hide');
    Route::put('courses/{course}/modules/{coursemodule}', 'Admin\CourseModuleController@update');
    Route::get('courses/{course}/modules/{coursemodule}/delete', 'Admin\CourseModuleController@delete');

    Route::post('courses/{course}/modules/{coursemodule}/documents', 'Admin\CourseModuleDocumentController@store');
    Route::get('courses/{course}/modules/{coursemodule}/documents/{coursemoduledocument}/delete', 'Admin\CourseModuleDocumentController@delete');
    Route::get('courses/{course}/modules/{coursemodule}/documents/{coursemoduledocument}/update', 'Admin\CourseModuleDocumentController@update');
});

/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {

    Route::get('users/', 'Admin\UserController@index');
    Route::get('users/{user}/edit', 'Admin\UserController@edit')->name('user.edit');
    Route::put('users/{user}', 'Admin\UserController@update');
    Route::get('users/create', 'Admin\UserController@create');
    Route::post('users', 'Admin\UserController@store');
    Route::get('users/{user}/approve', 'Admin\UserController@approve');
    Route::get('users/{user}/deny', 'Admin\UserController@deny');
    Route::get('users/{user}/courses/export', 'Admin\UserController@exportCourses');

    // Courses routes
    // Route::get('courses/{course}/delete', 'Admin\CourseController@delete');
    Route::get('courses/{course}/disable', 'Admin\CourseController@disable');
    Route::get('courses/{course}/enable', 'Admin\CourseController@enable');

    Route::get('courses/{course}/registrations/{courseregistration}/unregister', 'Admin\CourseController@unregisterStudent')->name('course.unregister');

    # Static Pages
    Route::get('staticpages', 'Admin\StaticPagesController@index');
    Route::get('staticpages/{staticpage}/edit', 'Admin\StaticPagesController@edit');
    Route::put('staticpages/{staticpage}', 'Admin\StaticPagesController@update');
});
