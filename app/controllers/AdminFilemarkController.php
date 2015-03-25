<?php

use Repositories\Filemark\FilemarkRepositoryInterface;

class AdminFilemarkController extends \BaseController
{
    public function __construct(FilemarkRepositoryInterface $filemark)
    {
        $this->filemark = $filemark;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filemarks = $this->filemark->getAll();

        return View::make('adminfilemark.index')
                     ->with('filemarks', $filemarks);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
        $filemark = $this->filemark->find($id);

        if ($filemark->global != 1) {
            Session::flash('error', 'Cannot edit client\'s label');

            return Redirect::to('admin/filemark');
            exit;
        }

        return View::make('adminfilemark.edit')
               ->with('filemark', $filemark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        Input::merge(array_map('trim', Input::except('password')));

        $rules = array(
            'label' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/filemark/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        //check if exist of same name
        $markCount = Filemark::where('global', '=', '1')
                             ->where('label', '=', Input::get('label'))
                             ->where('id', '<>', $id)
                             ->count();

        if ($markCount >= 1) {
            Session::flash('error', '<strong>'.Input::get('label').'</strong> already exists');

            return Redirect::to('admin/filemark/'.$id.'/edit');
            exit;
        }

        //create login
        $filemark = Filemark::find($id);
        $filemark->label = Input::get('label');
        $filemark->save();

        Session::flash('message', 'Filemark successfully updated');

        return Redirect::to('admin/filemark');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('adminfilemark.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        Input::merge(array_map('trim', Input::except('password')));

        $rules = array(
            'label' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/filemark/create')
                ->withErrors($validator)
                ->withInput();
        }

        //check if exist
        $markCount = Filemark::where('global', '=', 1)
                             ->where('label', '=', Input::get('label'))
                             ->count();

        if ($markCount >= 1) {
            Session::flash('error', '<strong>'.Input::get('label').'</strong> already exists');

            return Redirect::to('admin/filemark/create');
            exit;
        }

        //create login
        $filemark = new Filemark();
        $filemark->label = Input::get('label');
        $filemark->global = 1;
        $filemark->fk_empresa = Auth::User()->getUserData()->fk_empresa;
        $filemark->create_date = date("Y-m-d H:i:s");
        $filemark->save();
 

        Session::flash('message', '<strong>'.Input::get('label').'</strong> successfully created');

        return Redirect::to('admin/filemark');
    }
}
