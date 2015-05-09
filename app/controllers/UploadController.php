<?php
/*
|--------------------------------------------------------------------------
| Cabinet Controller Template
|--------------------------------------------------------------------------
|
| This is the default Cabinet controller template for controlling uploads.
| Feel free to change to your needs.
|
*/

class UploadController extends BaseController
{

    /**
     * Displays the form for account creation
     *
     */
    public function create()
    {
        //return View::make(Config::get('cabinet::upload_form'));
        $typesAllowed = Config::get('cabinet::upload_file_extensions');
        $maxFileSize = Config::get('cabinet::max_upload_file_size');
        
        return View::make('users.storage.create')
                     ->with('typesAllowed', $typesAllowed)
                     ->with('maxFileSize', $maxFileSize);
    }

    /**
     * Stores new upload
     *
     */
    public function store()
    {
        $file = Input::file('file');

        $upload = new Upload;

        try {
            $upload->process($file);
        } catch(Exception $exception){
            // Something went wrong. Log it.
            Log::error($exception);
            // Return error
            return Response::json($exception->getMessage(), 400);
        }

        // If it now has an id, it should have been successful.
        if ( $upload->id ) {
            return Response::json(array('status' => 'success', 'file' => $upload->toArray()), 200);
        } else {
            return Response::json('Error', 400);
        }
    }

    public function index()
    {
        return View::make(Config::get('cabinet::upload_list'));
    }

    public function data()
    {
        $uploads =  Upload::leftjoin('users', 'uploads.id', '=', 'users.id')
            ->select(
                array('uploads.id', 'uploads.filename', 'uploads.path', 'uploads.extension',
                    'uploads.size', 'uploads.mimetype', 'users.id as user_id', 'users.username as username')
            );

        return Datatables::of($uploads)
            ->remove_column('id')
            ->remove_column('user_id')
            ->edit_column('username', '<a href="{{ URL::to(\'admin/users/\'.$id.\'/edit\')}}">{{$username}}</a>')
            ->make();
    }

}
