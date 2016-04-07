<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class UsersChartController extends \BaseController
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
    }

    /**
     * Display a listing of the object with type = 3.
     *
     * @param boxid
     * @param orderid
     *
     * @return Response
     */
    public function indexBoxOrder($boxId = '', $orderId = '')
    {
        $companyId = Auth::User()->getCompanyId();

        $objects = Object::where('fk_obj_type', '=', 3)
                    ->where('fk_parent', '=', $boxId)
                    ->where('fk_company', '=', Auth::User()->getCompanyId())
                    ->orderBy('f_code')
                    ->orderBy('f_name')
                    ->get();

        foreach ($objects as $object) {
            $object->status = OrderStatus::find($object->fk_status)->status;

            try {
                $metaAttributeValues = $this->meta_attribute->getTargetAttributeValues($object->row_id, 'object');
                $_tmp = array();
                if (count($metaAttributeValues) >= 1) {
                    foreach ($metaAttributeValues as $item) {
                        $options = $this->meta_attribute->getAttributeOptions($item->attribute_id);
                        if (count($options) >= 1) {
                            $_tmp[$item->attribute_id][] = $this->meta_attribute->getAttributeOptionLabel($item->value);
                        } else {
                            $_tmp[$item->attribute_id][] = $item->value;
                        }
                    }
                }
                $object->attributeValues = $_tmp;
            } catch (Exception $e) {
                $object->attributeValues  = array();
            }
        }

        $box = Object::where('row_id', '=', $boxId)->first();

        $order = Object::where('row_id', '=', $orderId)->first();

        $attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);
        $companyAttributeHeaders  = $this->meta_attribute->getCompanyAttributeHeaders($companyId);

        return View::make('users.chart.index_box_order')
               ->with('box', $box)
               ->with('order', $order)
               ->with('objects', $objects)
               ->with('attributeFilters', $attributeFilters)
               ->with('companyAttributeHeaders', $companyAttributeHeaders);
    }

    /**
     * Display a listing of the object with type = 3.
     *
     * @param boxid
     * @param orderid
     *
     * @return Response
     */
    public function indexBoxOrderChart($boxId = '', $orderId = '', $chartId = '')
    {
        $filePermission = Auth::User()->getUserData()->file_permission;
        $permissionArray = json_decode(Auth::User()->getUserData()->file_permission, true);
        $permissionArray[] = '';
        $files = FileTable::where('fk_empresa', '=', Auth::User()->getCompanyId())
                                                ->where('parent_id', '=', $chartId)
                                                ->where(function ($query) use ($permissionArray) {
                                                                $query->whereIn('file_mark_id', $permissionArray)
                                                                      ->orWhereRaw('file_mark_id is null');
                                                         }

                                                  )
                                            ->get(array('row_id', 'filename', 'creadate', 'moddate', 'pages', 'filesize', 'file_mark_id'));

        $box = Object::where('row_id', '=', $boxId)->first();

        $order = Object::where('row_id', '=', $orderId)->first();

        $chart = Object::where('row_id', '=', $chartId)->first();

        $filemarkDropdown = array('' => '(No Label)');

        try {
            $filemarks = Filemark::whereIn('id',  json_decode($filePermission, true))
                                ->orderBy('global', 'desc')
                                ->orderBy('label')->get();

            foreach ($filemarks as $filemark) {
                $filemarkDropdown[$filemark->id] = $filemark->label;
            }
        } catch (Exception $e) {
        }

        return View::make('users.chart.index_box_order_chart')
               ->with('box', $box)
               ->with('order', $order)
               ->with('chart', $chart)
               ->with('filemarkDropdown', $filemarkDropdown)
               ->with('files', $files);
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
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
