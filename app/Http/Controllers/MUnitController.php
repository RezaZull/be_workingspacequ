<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MUnit;
use DB;
use Illuminate\Http\Request;
use Validator;

class MUnitController extends Controller
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
        $Munit = new MUnit();
        if (isset($searchParam) && isset($searchValue)) {
            $Munit = $Munit->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $Munit = $Munit->orderBy($orderBy, $orderDir);
        }
        $Munit = isset($pagination) ? $Munit->paginate($pagination) : $Munit->get();

        return ResponsHelper::successGetData($Munit);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mUnit = MUnit::create([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Unit"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mUnit) {
            return ResponsHelper::successChangeData($mUnit, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MUnit $mUnit)
    {
        return ResponsHelper::successGetData($mUnit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MUnit $mUnit)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mUnit = $mUnit->updateOrFail([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mUnit) {
            return ResponsHelper::successChangeData($mUnit, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MUnit $mUnit, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mUnit->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mUnit->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
