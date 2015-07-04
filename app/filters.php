<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});

App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        return Redirect::guest('login');
    }
});

Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) {
        return Redirect::to('/');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException();
    }
});

/*
 * begin Company Admin routes
 */

Route::filter('admin_user', function () {
    if (! Auth::User()->can('admin_user') || ! Auth::User()->isCompanyAdmin()) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('admin_role', function () {
    if (! Auth::User()->can('admin_role') || ! Auth::User()->isCompanyAdmin()) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('admin_filemark', function () {
    if (! Auth::User()->can('admin_filemark') || ! Auth::User()->isCompanyAdmin()) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

/*
 * end Company Admin routes
 */

 /* Begin user routes */

Route::filter('user_order', function () {
    if (!Auth::User()->can('user_order')) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('user_search', function () {
    if (!Auth::User()->can('user_search')) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('user_file', function () {
    if (!Auth::User()->can('user_file')) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('user_changepassword', function () {
    if (!Auth::User()->can('user_changepassword')) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

Route::filter('user_myfolder', function () {
    if (!Auth::User()->can('user_myfolder')) {
        // Checks the current user

        return Redirect::to('system/denied');
    }
});

/* End user routes */


Route::filter('check_password_expiry', function () {
    if (Auth::User() && Route::current()->getPath() != 'users/profile/password') {

         
        $lastChanged = max([Auth::User()->created_at, Auth::User()->password_changed_at]);
        
        try {
            $policy = PasswordPolicy::first();
            $expireDays = $policy->expire_days;
 
            if ( (strtotime($lastChanged) + $expireDays * 24 * 3600) < time() ) {

               return Redirect::to('users/profile/password')->with('expired_reminder', true);
            }

        } catch (Exception $e) {
            Logging::error('check_password_expiry filter error: No password policy found');
        }
       
    }
});
