<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MMenuGroupDetail;
use DB;
use Illuminate\Http\Request;
use Validator;

class MMenuGroupDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $searchParam = $request->query('searchParam');
        $searchValue = $request->query('searchValue');
        $orderBy = $request->query('orderBy');
        $orderDir = $request->query('orderDir');

        $pagination = $request->query('pagination');
        $MMenuGroupDetail = MMenuGroupDetail::with('menu');
        if (isset($searchParam) && isset($searchValue)) {
            $MMenuGroupDetail = $MMenuGroupDetail->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MMenuGroupDetail = $MMenuGroupDetail->orderBy($orderBy, $orderDir);
        }
        $MMenuGroupDetail = isset($pagination) ? $MMenuGroupDetail->paginate($pagination) : $MMenuGroupDetail->get();

        return ResponsHelper::successGetData($MMenuGroupDetail);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_m_menu_groups' => 'required|exists:m_menu_groups,id',
            'id_m_menus' => 'required|exists:m_menus,id',
            'flag_create' => 'required|boolean',
            'flag_read' => 'required|boolean',
            'flag_update' => 'required|boolean',
            'flag_delete' => 'required|boolean',
            'flag_export' => 'required|boolean',
            'flag_import' => 'required|boolean',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $MMenuGroupDetail = MMenuGroupDetail::create([
                'id_m_menu_groups' => $request->id_m_menu_groups,
                'id_m_menus' => $request->id_m_menus,
                'flag_create' => $request->flag_create,
                'flag_read' => $request->flag_read,
                'flag_update' => $request->flag_update,
                'flag_delete' => $request->flag_delete,
                'flag_export' => $request->flag_export,
                'flag_import' => $request->flag_import,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Menu_Group"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($MMenuGroupDetail) {
            return ResponsHelper::successChangeData($MMenuGroupDetail, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MMenuGroupDetail $mMenuGroupDetail)
    {
        return ResponsHelper::successGetData($mMenuGroupDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MMenuGroupDetail $mMenuGroupDetail)
    {
        $validator = Validator::make($request->all(), [
            'id_m_menu_groups' => 'required|exists:m_menu_groups,id',
            'id_m_menus' => 'required|exists:m_menus,id',
            'flag_create' => 'required|boolean',
            'flag_read' => 'required|boolean',
            'flag_update' => 'required|boolean',
            'flag_delete' => 'required|boolean',
            'flag_export' => 'required|boolean',
            'flag_import' => 'required|boolean',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mMenuGroupDetail = $mMenuGroupDetail->updateOrFail([
                'id_m_menu_groups' => $request->id_m_menu_groups,
                'id_m_menus' => $request->id_m_menus,
                'flag_create' => $request->flag_create,
                'flag_read' => $request->flag_read,
                'flag_update' => $request->flag_update,
                'flag_delete' => $request->flag_delete,
                'flag_export' => $request->flag_export,
                'flag_import' => $request->flag_import,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mMenuGroupDetail) {
            return ResponsHelper::successChangeData($mMenuGroupDetail, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MMenuGroupDetail $mMenuGroupDetail, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mMenuGroupDetail->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mMenuGroupDetail->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
