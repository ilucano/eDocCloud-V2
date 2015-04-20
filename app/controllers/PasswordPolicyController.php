<?php 

class PasswordPolicyController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      $policy = PasswordPolicy::first();
      
      if (!$policy) {
         $policy = new PasswordPolicy();
         $policy->min_length = 6;
         $policy->expire_days = 90;
         $policy->save();
      }

      $booleanDropdown = ['1' => 'Yes', '0' => 'No'];
      
      return View::make('passwordpolicy.index')
                    ->with('policy', $policy)
                    ->with('booleanDropdown', $booleanDropdown);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    
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

     $rules = array(
            'uppercase'       => 'required',
            'lowercase'      => 'required',
            'min_length' => 'required|integer',
            'special_character' => 'required',
            'expire_days' => 'required|integer',
      
      );
      

      $validator = Validator::make(Input::all(), $rules);
    
      if ($validator->fails()) {
            return Redirect::to('passwordpolicy')
                ->withErrors($validator)
                ->withInput();
      }
     
      $policy = PasswordPolicy::find($id);

      $policy->uppercase = Input::get('uppercase');
      $policy->lowercase = Input::get('lowercase');
      $policy->min_length = Input::get('min_length');
      $policy->special_character = Input::get('special_character');
      $policy->expire_days = Input::get('expire_days');
      $policy->save();

      Session::flash('message', 'Password Policy updated');

      return Redirect::to('passwordpolicy');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    
  }
  
}

?>