<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;


class UsersOrderController extends \BaseController
{


	public function __construct(MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->meta_attribute = $metaAttribute;
    }

      

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$companyId = Auth::User()->getCompanyId();

		$searchFilters = Input::get();

       
        $filterExpand =  (count($searchFilters) >= 1) ? true: false;

	    $objects = Object::where('fk_obj_type', '=', 1)
		                 ->where('fk_company', '=', $companyId);

		if (count($searchFilters) >= 1) {
			$filteredObjects = $this->getObjectByAttributeFilters($searchFilters);
			if ($filteredObjects != null) {
				$objects = $objects->whereIn('row_id', $filteredObjects);
			}
		}
		$objects = $objects->orderBy('row_id')->get();
						 
		
		foreach($objects as $object)
		{
		    
		    $object->status = OrderStatus::find($object->fk_status)->status;
			$object->price = $object->qty * $object->ppc;
		}

		$attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);

       	
		// load the view and pass the data
        return View::make('users.order.index')
               ->with('objects', $objects)
               ->with('attributeFilters', $attributeFilters)
               ->with('filterExpand', $filterExpand);
	}


	public function getObjectByAttributeFilters($filters) 
	{

		$arrayFilteredObjects = array('');

		if ($filters) {
            $joinTables = array();
            $andString = array();

            foreach ($filters as $attribute_id => $value) {
                if (!$value) {
                    continue;
                }
                $joinTables[] = " JOIN meta_target_attribute_values AS table_{$attribute_id} ON `master`.target_id  =  table_{$attribute_id}.target_id ";
                $andString[]  = " AND table_{$attribute_id}.attribute_id = '".addslashes($attribute_id). "' AND table_{$attribute_id}.value = '".addslashes($value). "' ";
            }

            if (count($joinTables) >= 1) {
                $attributeSql = "SELECT DISTINCT(`master`.target_id) FROM  `meta_target_attribute_values`  as `master` ";
                $attributeSql .= implode(" ", $joinTables);
                $attributeSql .= " WHERE `master`.target_type = 'object' ";
                $attributeSql .= implode(" ", $andString);

                
                $filteredObjects = DB::select(DB::raw($attributeSql));
                
                foreach ($filteredObjects as $filteredObject) {
                    $arrayFilteredObjects[] = $filteredObject->target_id;
                }

            }

        }
        
        return $arrayFilteredObjects;

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
		//
		$objects = Object::where('fk_obj_type', '=', 2)
						   ->where('fk_parent', '=', $id)
						   ->where('fk_company', '=', Auth::User()->getCompanyId())
						   ->orderBy('f_code')
						   ->orderBy('f_name')
						   ->get();
		
	   foreach($objects as $object)
	   {
		
			try {
				$_object = Object::where('row_id', '=', $object->fk_parent)->first();
				$object->price = $_object->ppc * $object->qty;
				
			}
			catch(Exception $e)
			{
			   $object->price = '';
			}
			
			try {
				
				$object->status = OrderStatus::find($object->fk_status)->status;
				
			}
			catch (Exception $e)
			{
				$object->status = '';
			}
				
			
			try {
				
				$pickup = Pickup::where('fk_box', '=', $object->row_id)->firstOrFail();

				$workflow = Workflow::where('wf_id', '=', $pickup->fk_barcode)->first();
				
				$object->estatus = WorkflowStatus::where('row_id', '=', $workflow->fk_status)->first()->status;
				
			}
			catch (Exception $e)
			{
				$object->estatus = "FINISH"; //default
			}
		
	   }
	     
		$parent = Object::where('fk_obj_type', '=', 1)
		                 ->where('fk_company', '=', Auth::User()->getCompanyId())
						 ->find($id);
						 
		// load the view and pass the data
		return View::make('users.order.show')
	           ->with('parent', $parent)
               ->with('objects', $objects);
			   

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
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAttributes($id)
    {
        
        $companyId = Auth::User()->getCompanyId();

        $object = Object::where('row_id', '=', $id)
		                 ->where('fk_company', '=',  $companyId)->first();


        $attributeSets = $this->meta_attribute->getCompanyAttributes($companyId);
        
        foreach ($attributeSets as $attribute) {
            $attribute->user_value  = $this->meta_attribute->getTargetAttributeValues($object->row_id, 'object', $attribute->id);
        }

        return View::make('users.order.attributes.edit')
                    ->with('object', $object)
                    ->with('attributeSets', $attributeSets);

    }


    public function updateAttributes($id)
    {
        //make sure is owner of file
        try {
            $companyId = Auth::User()->getCompanyId();
	         
	        $object = Object::where('row_id', '=', $id)
		                 ->where('fk_company', '=',  $companyId)->first();


        } catch (Exception $e) {
            exit('cannot retrieve file');
        }
        
        $id = $object->row_id;

        $input = Input::except('_method', '_token');

        try {
            $this->meta_attribute->updateTargetAttributeValues($id, 'object', $input);

            Session::flash('message', 'Order attributes successfully updated');

            return Redirect::to('users/order');

        } catch (Exception $e) {
             Session::flash('error', 'Order updating file attributes');
            return Redirect::to('users/order');
        }
    }



}
