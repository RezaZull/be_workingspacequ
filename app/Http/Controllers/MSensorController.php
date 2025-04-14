<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MSensor;
use DB;
use Illuminate\Http\Request;
use Validator;

class MSensorController extends Controller
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
        $Msensor = MSensor::with('unit');
        if (isset($searchParam) && isset($searchValue)) {
            $Msensor = $Msensor->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $Msensor = $Msensor->orderBy($orderBy, $orderDir);
        }
        $Msensor = isset($pagination) ? $Msensor->paginate($pagination) : $Msensor->get();

        return ResponsHelper::successGetData($Msensor);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_m_unit' => 'required|exists:m_units,id',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mSensor = MSensor::create([
                'name' => $request->name,
                'id_m_unit' => $request->id_m_unit,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Sensor"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mSensor) {
            return ResponsHelper::successChangeData($mSensor, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MSensor $mSensor)
    {

        return ResponsHelper::successGetData($mSensor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MSensor $mSensor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_m_unit' => 'required|exists:m_units,id',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mSensor = $mSensor->updateOrFail([
                'name' => $request->name,
                'id_m_unit' => $request->id_m_unit,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mSensor) {
            return ResponsHelper::successChangeData($mSensor, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MSensor $mSensor, Request $request)
    {


        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mSensor->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mSensor->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
