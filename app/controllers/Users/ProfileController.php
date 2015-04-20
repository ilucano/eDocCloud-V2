<?php

class UsersProfileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	 
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		 //
		  
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		 
	  
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	 

	}
   

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		 //
		
	  
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	
    public function showChangePassword()
	{
		return View::make('users.profile.changepassword');
	}
	
	public function doChangePassword()
	{
		
		try {
			$policy = PasswordPolicy::first();
		} catch (Exception $e) {
			$policy = null;
		}

		if ($policy) {
			$minLength = $policy->min_length;
		} else {
			$minLength = '5';
		}

		$rules = array(
			'password' => 'required|min:'.$minLength.'|confirmed',
			'password_confirmation' => 'required|min:5'
		);
		
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('users/profile/password')
                ->withErrors($validator)
                ->withInput();
        }

        //check base on rules
        if ($policy) {
        	if($policy->uppercase == 1) {
        		if (!preg_match('/[A-Z]/', Input::get('password'))) {
				  	Session::flash('error', 'Password must contain at least one uppercase');
					return Redirect::to('users/profile/password');
				}
        	}

        	if($policy->lowercase == 1) {
        		if (!preg_match('/[a-z]/', Input::get('password'))) {
				  	Session::flash('error', 'Password must contain at least one lowercase');
					return Redirect::to('users/profile/password');
				}
        	}

        	if($policy->special_character == 1) {
        		if (!preg_match('/[!@#$%^&*\/\\:"\'<>,]/', Input::get('password'))) {
				  	Session::flash('error', 'Password must contain at least one special character');
					return Redirect::to('users/profile/password');
				}
        	}

        }
		
		$user =	Login::findOrFail(Auth::User()->id);
		
		$user->password = Hash::make(Input::get('password'));
        $user->save();
 
		$logDetails = json_encode(['row_id' => Auth::User()->getUserData()->row_id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'user_profile_changepassword',
				'action'      => 'Updated',
				'description' => 'Changed password',
				'details'     => $logDetails,
				'updated'     => true,
			]);
		
		Session::flash('message', 'Password Changed');
			
		return Redirect::to('users/profile/password');
	
		 
	}
	


}
