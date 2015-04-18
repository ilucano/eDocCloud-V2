<?php namespace Repositories\Activity;

use Activity;
use Login;
use User;
use HTML;
use Filemark;
use Workflow;
use Pickup;
use WorkflowStatus;
use Attach;
use Object;
use Log;
use Company;
use FileTable;
use Role;
use OrderStatus;

class ActivityRepository implements ActivityRepositoryInterface
{
    private $activityLogs;

    public function getAll()
    {

        //get results in last 60 days
        $this->activityLogs = Activity::where('created_at', '>=', date("Y-m-d H:i:s", time() - (24*60*60*90)))
                              ->orderBy('created_at', 'desc')
                              ->get();

        return $this->parseActivityDetails();
    }

    public function getByCompany($companyId)
    {
    }

    public function getByUsers(array $users)
    {
    }

    public function find($id)
    {
    }

    /**
     * Return username of activitylog.
     *
     * @param integer $userId
     *
     * @return string $username
     */
    protected function getActivityUsername($userId)
    {

        $_login = Login::find($userId);
        
        if ($_login) {
            $username = $_login->getUserData()->username;
        } else {
            $username = '';
        }
        return $username;
    }
    
    /**
     * [Retrieve details of activity log
     * @return object $activitLogs
     */
    protected function parseActivityDetails()
    {

        foreach ($this->activityLogs as $activityLog) {
            $activityLog->username = $this->getActivityUsername($activityLog->user_id);

            $activityLog->detailsText = $this->getActivityDetailsText($activityLog->content_type, $activityLog->details);
        }

        return $this->activityLogs;
    }

    protected function getActivityDetailsText($contentType, $json)
    {
        try {
            $arrayDetails = json_decode($json, true);

            if (!is_array($arrayDetails)) {
                Log::error('getDetailsInfo() - Invalid $jsonDetails : '.$json);

                return '';
            }

            switch ($contentType) {
                case 'user_login_success':

                    $text = $arrayDetails['username'];
                    $userId = User::where('username', '=', $arrayDetails['username'])->first()->row_id;

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
                    return 'Query: <i>'.$arrayDetails['query'].'</i>';
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

                    return self::getDownloadFileRouteLink($fileName, $fileId)." labeled as <i>".$markLabel."</i>";

                break;

                case 'admin_ocr_update':
                case 'admin_qa_update':
                case 'admin_prepare_update':
                case 'admin_scan_update':
                    $boxName =  self::getBoxIdByWorkflowId($arrayDetails['row_id']);
                    $status =  self::getWorkflowStatus($arrayDetails['fk_status']);

                    return 'Box: '.$boxName.' updated to '.$status;
                break;

                case 'admin_pickup_store':
                    $barcode = self::getPickupBarcodeById($arrayDetails['pickup_id']);
                    $boxCode = self::getFCodeById($arrayDetails['box_id']);

                    return 'Barcode: '.$barcode.', Box Code: '.$boxCode;
                break;

                case 'admin_create_box_success':
                case 'admin_update_box_success':
                    $boxCode = self::getFCodeById($arrayDetails['row_id']);
                    $boxName = self::getBoxNameById($arrayDetails['row_id']);

                    return 'Box Code: '.$boxCode.', Box Name: '.$boxName;

                break;

                case 'admin_pickup_create':
                case 'admin_pickup_update':
                    $barcode = self::getPickupBarcodeById($arrayDetails['row_id']);

                    return self::getShowPickupRouteLink($barcode, $arrayDetails['row_id']);

                case 'admin_update_admin':
                    $admin = User::find($arrayDetails['row_id'])->username;
                    $status = (strtolower($arrayDetails['is_admin']) == 'x') ? ' added to admin'  : ' removed from admin';

                    return self::getShowAdminUserListRouteLink($admin).$status;

                break;

                case 'admin_user_store':
                case 'admin_user_update':
                    $user = User::find($arrayDetails['row_id'])->username;

                    return self::getAdminShowUserRouteLink($user, $arrayDetails['row_id']);

                break;

                case 'admin_company_store':
                case 'admin_company_update':
                    $companyName = Company::find($arrayDetails['row_id'])->company_name;

                    return self::getShowCompanyRouteLink($companyName, $arrayDetails['row_id']);
                break;

                case 'admin_update_box_status':
                    $code = Object::find($arrayDetails['row_id'])->f_code;
                    $status = OrderStatus::find($arrayDetails['status'])->status;

                    return self::getShowBoxRouteLink($code, $arrayDetails['row_id']).' updated to '.$status;
                break;
                default:

                Log::error('getDetailsInfo() - Not valid option for $contentType : '.$contentType);

                return $json;

                break;

            }
        } catch (Exception $e) {
            return $json;
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
        } catch (Exception $e) {
            return '';
        }
    }

    protected function getWorkflowStatus($status_id)
    {
        try {
            return WorkflowStatus::where('row_id', '=', $status_id)->firstOrFail()->status;
        } catch (Exception $e) {
            return '';
        }
    }

    protected function getPickupBarcodeById($id)
    {
        try {
            $barcode = Pickup::find($id)->fk_barcode;

            return $barcode;
        } catch (Exception $e) {
            Log::error('getPickupNameById() failed. id: '.$id);

            return '';
        }
    }

    protected function getFCodeById($id)
    {
        try {
            $fCode =  Object::find($id)->f_code;

            return $fCode;
        } catch (Exception $e) {
            Log::error('getFCodeById() failed. id: '.$id);

            return '';
        }
    }

    protected function getBoxNameById($id)
    {
        try {
            $fName =  Object::find($id)->f_name;

            return $fName;
        } catch (Exception $e) {
            Log::error('getBoxNameById() failed. id: '.$id);

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

    protected function getShowPickupRouteLink($text, $id)
    {
        return HTML::linkAction('AdminPickupController@edit', $text, array($id));
    }

    protected function getShowAdminUserListRouteLink($text)
    {
        return HTML::linkAction('AdministratorController@index', $text);
    }

    protected function getAdminShowUserRouteLink($text, $id)
    {
        return HTML::linkAction('UserController@show', $text, array($id));
    }

    protected function getShowCompanyRouteLink($text, $id)
    {
        return HTML::linkAction('CompanyController@show', $text, array($id));
    }

    protected function getShowBoxRouteLink($text, $id)
    {
        return HTML::linkAction('AdminBoxController@edit', $text, array($id));
    }
}
