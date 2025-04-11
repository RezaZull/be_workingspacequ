<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MMenuGroup;
use DB;
use Illuminate\Http\Request;
use Validator;

class MMenuGroupController extends Controller
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
        $MMenuGroup = MMenuGroup::with('role');
        if (isset($searchParam) && isset($searchValue)) {
            $MMenuGroup = $MMenuGroup->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MMenuGroup = $MMenuGroup->orderBy($orderBy, $orderDir);
        }
        $MMenuGroup = isset($pagination) ? $MMenuGroup->paginate($pagination) : $MMenuGroup->get();

        return ResponsHelper::successGetData($MMenuGroup);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'flag_active' => 'required',
            'id_m_roles' => 'required|exists:m_roles,id',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $MMenuGroup = MMenuGroup::create([
                'name' => $request->name,
                'id_m_roles' => $request->id_m_roles,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Menu_Group"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($MMenuGroup) {
            return ResponsHelper::successChangeData($MMenuGroup, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MMenuGroup $mMenuGroup)
    {
        return ResponsHelper::successGetData($mMenuGroup->load('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MMenuGroup $mMenuGroup)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'flag_active' => 'required',
            'id_m_roles' => 'required|exists:m_roles,id',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mMenuGroup = $mMenuGroup->updateOrFail([
                'name' => $request->name,
                'flag_active' => $request->flag_active,
                'id_m_roles' => $request->id_m_roles,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mMenuGroup) {
            return ResponsHelper::successChangeData($mMenuGroup, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MMenuGroup $mMenuGroup, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mMenuGroup->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mMenuGroup->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
