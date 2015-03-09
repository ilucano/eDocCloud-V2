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
				Log::error('Failed to get activity log username. id: '.  $activityLog->id. '. User Id: '. $activityLog->user_id);
				
				$activityLog->username = '';
			}
			
			$jsonDetails = $activityLog->details;
			
			try {
				
				$this->detailsText = $this->getDetailsInfo($activityLog->content_type, $activityLog->details);
				
			}
			catch (Exception $e)
			{
				$this->detailsText = $jsonDetails;
				
				Log::error('Failed to parse activity details. id: '.  $activityLog->id. '. Details: '. $jsonDetails);
			}
			
			
		}
		
	 
        return View::make('users.activity.index')
					 ->with('activityLogs', $activityLogs);

	}
	
	
	/**
	 * Function to generate more details base on log content type
	 * and details
	 * @param string $contentType
	 * @param string $jsonDetails
	 *
	 * @return string Output
	 *
	 */
	
	private function getDetailsInfo($contentType = '', $jsonDetails = '')
	{
		$arrayDetails = json_decode($jsonDetails, true);
		
		if(!is_array($arrayDetails) )
		{
			Log::error('getDetailsInfo() - Invalid $jsonDetails : '.  $jsonDetails);
			
			return '';
		}
		
		switch ($contentType)
		{
			case 'user_login_success':
				
                $text = $arrayDetails['username'];
				$userId = User::where('username', '=',  $arrayDetails['username'])->first()->row_id;
				
			    $link =   URL::action('CompanyAdminUserController@show', $userId);
				return HTML::link($link, $text);
			
			break;
		
			default:
				
			   Log::error('getDetailsInfo() - Not valid option for $contentType : '.  $contentType);
			   return '';
			
			break;

		}
		
		
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
				              ->where('created_at', '>=', date("Y-m-d H:i:s", time() - (24*60*60*90)))
							  ->orderBy('created_at', 'desc')
							  ->get();
		
		return $results;
	}
	
	
}
