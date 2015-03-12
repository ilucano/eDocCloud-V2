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
				
				$activityLog->detailsText = $this->getDetailsInfo($activityLog->content_type, $activityLog->details);
				
			}
			catch (Exception $e)
			{
				$activityLog->detailsText = $jsonDetails;
				
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
				 
				return self::getShowUserRouteLink($text, $userId);
			
			break;
		
			case 'user_profile_changepassword':
			case 'companyadmin_user_store':
			case 'companyadmin_user_updated':
				
				$userId = $arrayDetails['row_id'];
                $text = User::find($userId)->username;
	
				return self::getShowUserRouteLink($text, $userId);
			 
			break;
			
			case 'companyadmin_role_store':	
			case 'companyadmin_role_update':
			
				$roleId = $arrayDetails['row_id'];
				$text = Role::find($roleId)->name;
				
		        return self::getShowRoleRouteLink($text, $roleId);
			
			case 'user_file_search':
				return 'Query: <i>' . $arrayDetails['query'] . '</i>';
			break;
		
			case 'companyadmin_filemark_store':
			case 'companyadmin_filemark_update':
				
				$fileMarkId = $arrayDetails['row_id'];
				$text = Filemark::find($fileMarkId)->label;
			 
				return self::getEditFilemarkRouteLink($text, $fileMarkId);
		    
			break;
		    
			
			case 'attachment_download_file':
				$fileId = $arrayDetails['row_id'];
				$fileName = FileTable::find($fileId)->filename;
				 
				return self::getDownloadFileRouteLink($fileName, $fileId);
				
			break;
		    
			case 'attachment_download_attachment':
				$attachmentId = $arrayDetails['row_id'];
				$attachmentName = Attach::find($attachmentId)->attach_name;
				
				return self::getDownloadAttachmentRouteLink($attachmentName, $attachmentId);
				
			break;
		
			case 'user_file_updatemark':
			    $fileId = $arrayDetails['row_id'];
				$fileMarkId = $arrayDetails['file_mark_id'];
				
				$fileName = FileTable::find($fileId)->filename;
				$markLabel = Filemark::find($fileMarkId)->label;
				
				return self::getDownloadFileRouteLink($fileName, $fileId) . " labeled as <i>" . $markLabel . "</i>";
				
			break;
		
			case 'admin_ocr_update':
				$boxName =  self::getBoxIdByWorkflowId($arrayDetails['row_id']);
				$status =  self::getWorkflowStatus($arrayDetails['fk_status']);
				return 'Box: '. $boxName . ', Status: '. $status;
			break;
		
			default:
				
			   Log::error('getDetailsInfo() - Not valid option for $contentType : '.  $contentType);
			   return $jsonDetails;
			
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
	
	
	protected function getBoxIdByWorkflowId($id)
	{
		try {
			
			$workflow = Workflow::find($id);
		 
			return substr($workflow->wf_id, 6);
		}
		catch (Exception $e)
		{
			return '';
		}
		
	}
	
	protected function getWorkflowStatus($status_id)
	{
		try {
			return WorkflowStatus::where('row_id', '=', $status_id)->firstOrFail()->status;
		}
		catch (Exception $e)
		{
			return '';
		}
		
	}
	
	protected function getShowUserRouteLink($text, $id)
	{
		return HTML::linkAction('CompanyAdminUserController@show', $text, array($id));
	}
	
	
	protected function getShowRoleRouteLink($text, $id)
	{
		return HTML::linkAction('CompanyAdminRoleController@show', $text, array($id));
	}
	
	
	protected function getEditFilemarkRouteLink($text, $id)
	{
		return HTML::linkAction('CompanyAdminFilemarkController@edit', $text, array($id));
	}
	
	protected function getDownloadFileRouteLink($text, $id)
	{
		
		return HTML::linkAction('AttachmentController@downloadFile', $text, array($id));
	}
	
	protected function getDownloadAttachmentRouteLink($text, $id)
	{
		
		return HTML::linkAction('AttachmentController@downloadAttachment', $text, array($id));
	}
	
}
