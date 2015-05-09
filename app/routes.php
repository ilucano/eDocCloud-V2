<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
//
//Route::get('/', function()
//{
//	return View::make('hello');
//});


// route to show the login form
Route::get('login', array('uses' => 'HomeController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'HomeController@doLogin'));

// route to show the login form
Route::get('logout', array('uses' => 'HomeController@doLogout'));

//begin routes required logged in
Route::group(
    array('before' => 'auth'), function () {

        Route::get(
            '/', array(
                          'uses' => 'HomeController@showIndex',
                          'as' => 'home.index',
            )
    );

        Route::get(
            '/home/', array(
                                'uses' => 'HomeController@showIndex',
                                'as' => 'home.index',
            )
    );

        /* begin super admin section */

        Route::resource('pickup', 'PickupController');

        Route::resource('prepare', 'PrepareController');

        Route::post('prepare/status', array('uses' => 'PrepareController@doUpdateStatus'));

        Route::resource('scan', 'ScanController');

        Route::post('scan/status', array('uses' => 'ScanController@doUpdateStatus'));

        Route::resource('qa', 'QAController');

        Route::post('qa/status', array('uses' => 'QAController@doUpdateStatus'));

        Route::resource('ocr', 'OCRController');

        Route::post('ocr/status', array('uses' => 'OCRController@doUpdateStatus'));

        Route::get(
            'reports/allboxes', array(
                                'uses' => 'ReportsController@showAllBoxes',
                                'as' => 'reports.allboxes',
            )
        );

        Route::resource('passwordpolicy', 'PasswordPolicyController');
        

        Route::get(
            'reports/groupbystatus', array(
                                'uses' => 'ReportsController@showGroupByStatus',
                                'as' => 'reports.groupbystatus',
            )
         );

        Route::get(
            'reports/datausage', array(
                                'uses' => 'ReportsController@showDataUsage',
                                'as' => 'reports.datausage',
            )
         );

        Route::get(
            'reports/usagechart/{fk_empresa}', array(
                                'uses' => 'ReportsController@showUsageChart',
                                'as' => 'reports.usagechart',
            )
         );

        Route::resource('company', 'CompanyController');

        Route::resource('user', 'UserController');

        Route::get(
            'user/create/company/{fk_empresa}', array(
                                'uses' => 'UserController@createStep2',
                                'as' => 'user.create.step2',
            )
    );

        Route::post(
            'user/create/company/{fk_empresa}', array(
                                'uses' => 'UserController@storeStep2',
                                'as' => 'user.create.storestep2',
            )
    );

        Route::resource('administrator', 'AdministratorController');

        Route::post('administrator/isadmin', array('uses' => 'AdministratorController@doUpdateIsAdmin'));

        Route::resource('order', 'OrderController');

        Route::post('order/status', array('uses' => 'OrderController@doUpdateStatus'));

        Route::group(
            array('prefix' => 'admin'),
            function () {

                Route::resource('pickup', 'AdminPickupController');
                Route::resource('box', 'AdminBoxController');
                Route::post('box/status', array('uses' => 'AdminBoxController@doUpdateStatus'));

                Route::resource('filemark', 'AdminFilemarkController');

                Route::resource('activity', 'AdminActivityController');

            }
        );

        Route::resource('role', 'RoleController');
        /* end super admin section */

        /* begin company admin section */

        // Route::when('companyadmin/user*', 'admin_user'); //admin_user permission check (app/filters.php)
        // Route::when('companyadmin/role*', 'admin_role'); //permission check (app/filters.php)
        // Route::when('companyadmin/filemark*', 'admin_filemark'); //permission check (app/filters.php)

        Route::group(
            array('prefix' => 'companyadmin'),
            function () {

                Route::resource('user', 'CompanyAdminUserController');
                Route::resource('filemark', 'CompanyAdminFilemarkController');
                Route::resource('role', 'CompanyAdminRoleController');

                Route::resource('metaattribute', 'CompanyAdminMetaAttributeController');
                Route::resource('metatargetattributevalue', 'CompanyAdminMetaTargetAttributeValueController');
                Route::resource('metaattributeoption', 'CompanyAdminMetaAttributeOptionController');

                Route::get(
                'reports/usagechart', array(
                                    'uses' => 'CompanyAdminReportsController@showUsageChart',
                                    'as' => 'companyadminreports.usagechart',
                )
         );

            }
        );

        /* end company admin section */

        /* begin users section */

        Route::when('users/order*', 'user_order');
        Route::when('users/chart*', 'user_order');
        Route::when('users/file/search*', 'user_search');
        Route::when('users/file*', 'user_file');
        Route::when('users/profile/password', 'user_changepassword');

        Route::group(
            array('prefix' => 'users'),
            function () {

                Route::resource('order', 'UsersOrderController');

                Route::resource('chart', 'UsersChartController');

                Route::get(
                    'chart/{boxid}/{orderid}', array(
                    'as' => 'chart.box.order',
                    'uses' => 'UsersChartController@indexBoxOrder',
                    )
            );

                Route::get(
                    'chart/{boxid}/{orderid}/{chartId}', array(
                    'as' => 'chart.box.order.chart',
                    'uses' => 'UsersChartController@indexBoxOrderChart',
                    )
            );

                Route::get('file/mark', array('uses' => 'UsersFileController@doUpdateMark'));

                Route::get('file/search', array('uses' => 'UsersFileController@showSearch'));

                Route::get('file/attributes/{id}/edit', array('uses' => 'UsersFileController@editAttributes'));

                Route::put('file/attributes/{id}', array(
                                                        'uses' => 'UsersFileController@updateAttributes', 
                                                         'as' => 'users.file.attribute.update'
                                                         )
                                                    );

                Route::post('file/search', array('uses' => 'UsersFileController@doSearch'));

                Route::resource('file', 'UsersFileController');

                Route::get('profile/password', array('uses' => 'UsersProfileController@showChangePassword'));

                Route::post('profile/password', array('uses' => 'UsersProfileController@doChangePassword'));

                Route::resource('profile', 'UsersProfileController');

                Route::resource('activity', 'UsersActivityController');


                Route::get('order/attributes/{id}/edit', array('uses' => 'UsersOrderController@editAttributes'));

                Route::put('order/attributes/{id}', array(
                                                        'uses' => 'UsersOrderController@updateAttributes', 
                                                         'as' => 'users.order.attribute.update'
                                                         )
                                                    );


                Route::resource('storage', 'UsersStorageController');
            }
        );

        /* end users section */

        /* Attachment */
        Route::get(
            'attachment/file/{id}', array(
                'as' => 'attachment.file.id',
                'uses' => 'AttachmentController@downloadFile',
            )
    );

        Route::get(
            'attachment/download/{id}', array(
                'as' => 'attachment.download.id',
                'uses' => 'AttachmentController@downloadAttachment',
            )
        );

        Route::get(
            'attachment/stream/{id}', array(
                'as' => 'attachment.stream.id',
                'uses' => 'AttachmentController@streamAttachment',
            )
    );

        Route::post(
            'attachment/fileszip', array(
            'uses' => 'AttachmentController@zipFiles',
            )
    );

        Route::resource('attachment', 'AttachmentController');

        /* end Attachment */

        /* pdf viewer **/
        Route::get(
            '/pdfviewer', function () {
                return View::make('pdfviewer.show');
            }
    );

        Route::get(
            'system/denied', function () {
                return View::make('system.denied');
            }
    );

    }
);

//end routes required logged in


/* test entrust **/
Route::get(
    '/start', function () {
        $subscriber = new Role();
        $subscriber->name = 'Subscriber';
        $subscriber->save();

        $author = new Role();
        $author->name = 'Author';
        $author->save();

        $read = new Permission();
        $read->name = 'can_read';
        $read->display_name = 'Can Read Posts';
        $read->save();

        $edit = new Permission();
        $edit->name = 'can_edit';
        $edit->display_name = 'Can Edit Posts';
        $edit->save();

        $subscriber->attachPermission($read);
        $author->attachPermission($read);
        $author->attachPermission($edit);

        $user1 = Login::find(3);
        $user2 = Login::find(4);

        $user1->attachRole($subscriber);
        $user2->attachRole($author);

        return 'Woohoo!';
    }
);

/* test queue **/
Route::get(
    '/testqueue', function () {
        echo '...';
        Queue::push(
            'MailService', array('to' => 'yatsum812@gmail.com',
                                  'from' => 'noreply@test2.com', )
    );

    }
);
// Adding auth checks for the upload functionality is highly recommended.

// Cabinet routes
Route::get('upload/data', 'UploadController@data');
Route::resource( 'upload', 'UploadController',
        array('except' => array('show', 'edit', 'update', 'destroy')));
