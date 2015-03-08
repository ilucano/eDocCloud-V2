<?php

class UsersActivityController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$users = array();
		
		//check if it's company admin?
		if(Auth::User()->isCompanyAdmin())
		{
			
			//get list of login id belongs to company
			$logins = DB::table('logins')
			                 ->join('users', 'logins.username', '=', 'users.username')
			                 ->where('users.fk_empresa', '=', Auth::User()->getCompanyId())
			                 ->select(array('id'))
							 ->get();
		    
			foreach($logins as $login)
			{
				$users[] = $login->id;
			}
			
			
			
		}
		else {
			$users[] = Auth::User()->id;
		}
		
		$activityLogs = $this->getAcitivityLog($users);
	   
		
		foreach($activityLogs as $activityLog)
		{
			//get user details;
			try {
				$_login = Login::find($activityLog->user_id);
				
				$activityLog->username = $_login->getUserData()->username;
			}
			catch (Exception $e)
			{
				$activityLog->username = '';
			}
		}
		
	 
        return View::make('users.activity.index')
					 ->with('activityLogs', $activityLogs);

	}
	
	/**
	 * @param $users Array contains id (login table)
	 *
	 * @return object results
	 */ 
    	
	private function getAcitivityLog($users)
	{
		//get results in last 60 days
		$results = Activity::whereIn('user_id', $users)
				              ->where('created_at', '>=', date("Y-m-d H:i:s", time() - (24*60*60*60)))
							  ->orderBy('created_at', 'desc')
							  ->get();
		
		return $results;
	}
	
	
}
