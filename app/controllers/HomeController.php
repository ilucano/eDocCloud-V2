<?php

class HomeController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function showWelcome()
    {
        return View::make('hello');
    }

    public function showIndex()
    {
        if (Auth::User()->isAdmin()) {
            $statistics = new Object();

            $workflows = Workflow::where('fk_status', '=', 4)
                               ->orWhere(
                                         function ($query) {
                                            $query->where('fk_status', '=', 5)
                                                  ->where('created_by', '=', Auth::user()->getUserData()->row_id);

                                         }
                                )->count();

            $statistics->number_of_preparations = $this->getNumOfPreparations();
            $statistics->number_of_scans = $this->getNumOfScans();
            $statistics->number_of_qas = $this->getNumOfQAs();
            $statistics->number_of_ocrs = $this->getNumOfOCRs();

            //echo "<pre>";
            //print_r($statistics);
            //exit;
            return View::make('home.index')
                         ->with('statistics', $statistics);
        } else {
            return Redirect::to('users/storage');
        }
    }

    public function getNumOfPreparations()
    {
        return Workflow::where('fk_status', '=', 4)
                           ->orWhere(
                                    function ($query) {
                                        $query->where('fk_status', '=', 5)
                                              ->where('created_by', '=', Auth::user()->getUserData()->row_id);

                                     }
                            )->count();
    }

    public function getNumOfScans()
    {
        return  Workflow::where('fk_status', '=', 6)
                               ->orWhere(
                                        function ($query) {
                                            $query->where('fk_status', '=', 7)
                                                  ->where('created_by', '=', Auth::user()->getUserData()->row_id);

                                        }
                                )->count();
    }

    public function getNumOfQAs()
    {
        return Workflow::where('fk_status', '=', 8)
                               ->orWhere(
                                        function ($query) {
                                            $query->where('fk_status', '=', 9)
                                                  ->where('created_by', '=', Auth::user()->getUserData()->row_id);

                                        }
                                )->count();
    }

    public function getNumOfOCRs()
    {
        return Workflow::where('fk_status', '=', 10)
                               ->orWhere(
                                         function ($query) {
                                            $query->where('fk_status', '=', 11)
                                                  ->where('created_by', '=', Auth::user()->getUserData()->row_id);

                                         }
                                )->count();
    }

    /**
     * Login Form.
     */
    public function showLogin()
    {
        // show the form
        return View::make('home.login');
    }

    /*
     * process login
     */

    public function doLogin()
    {
        // validate the info
        $rules = array(
            'username'    => 'required',
            'password' => 'required',
        );

        // run the validation rules
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));

            exit;
        }

        // create our user data for the authentication
        $userdata = array(
            'username'     => Input::get('username'),
            'password'  => Input::get('password'),
            'active'    => 1,
        );

        $logDetails = json_encode(['username' => Input::get('username')]);

        if (Auth::attempt($userdata)) {
            Activity::log([
                'contentId'   => Auth::User()->id,
                'contentType' => 'user_login_success',
                'action'      => 'Create',
                'description' => 'Successfully Logged In',
                'details'     => $logDetails,
                'updated'     => false,
            ]);

            // validation successful!
            //return Redirect::to('home');
            $company = Company::where('row_id', '=', Auth::User()->getCompanyId())->first();
            $appDomain = $company->app_domain;
            $token = Auth::User()->getRememberToken();
            return Redirect::to($appDomain .'/ssologin?token='. $token);

        } else {
            Activity::log([
                'contentId'   => 0,
                'contentType' => 'user_login_success',
                'action'      => 'Create',
                'description' => 'Log In Fail',
                'details'     => $logDetails,
                'updated'     => false,
            ]);

            // redirect
            Session::flash('error', 'Invalid Username or Password');

            return Redirect::to('login');
        }
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }


    public function ssoLogin()
    {   
        $user = Login::where('remember_token', '=', Input::get('token'))->where('active', '=', 1)->first();
        
        if ($user) {
           // echo $hashPassword;
            Auth::login($user);
        }

            // validation successful!
        return Redirect::to('home');
    }
}
