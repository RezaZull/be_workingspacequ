<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MRole;
use DB;
use Illuminate\Http\Request;
use Validator;

class MRoleController extends Controller
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
        $MRole = new MRole();
        if (isset($searchParam) && isset($searchValue)) {
            $MRole = $MRole->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MRole = $MRole->orderBy($orderBy, $orderDir);
        }
        $MRole = isset($pagination) ? $MRole->paginate($pagination) : $MRole->get();

        return ResponsHelper::successGetData($MRole);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRole = MRole::create([
                'name' => $request->name,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Role"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRole) {
            return ResponsHelper::successChangeData($mRole, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MRole $mRole)
    {
        return ResponsHelper::successGetData($mRole);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MRole $mRole)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRole = $mRole->updateOrFail([
                'name' => $request->name,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRole) {
            return ResponsHelper::successChangeData($mRole, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MRole $mRole, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mRole->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mRole->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
