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
		
		
		$rules = array(
			'password' => 'required|min:5|confirmed',
			'password_confirmation' => 'required|min:5'
		);
		
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('users/profile/password')
                ->withErrors($validator)
                ->withInput();
        }
		
		$user =	Login::findOrFail(Auth::User()->id);
		
		$user->password = Hash::make(Input::get('password'));
        $user->save();
		
		Session::flash('message', 'Password Changed');
			
		return Redirect::to('users/profile/password');
	
		 
	}
	


}
