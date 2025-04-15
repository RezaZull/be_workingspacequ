<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MRoomSensor;
use DB;
use Illuminate\Http\Request;
use Validator;

class MRoomSensorController extends Controller
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
        $MRoomSensor = MRoomSensor::with(['room', 'sensor']);
        if (isset($searchParam) && isset($searchValue)) {
            $MRoomSensor = $MRoomSensor->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MRoomSensor = $MRoomSensor->orderBy($orderBy, $orderDir);
        }
        $MRoomSensor = isset($pagination) ? $MRoomSensor->paginate($pagination) : $MRoomSensor->get();

        return ResponsHelper::successGetData($MRoomSensor);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_m_room' => 'required|exists:m_rooms,id',
            'id_m_sensor' => 'required|exists:m_sensors,id',
            'value' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoom = MRoomSensor::create([
                'id_m_room' => $request->id_m_room,
                'id_m_sensor' => $request->id_m_sensor,
                'value' => $request->value,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Room"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoom) {
            return ResponsHelper::successChangeData($mRoom, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MRoomSensor $mRoomSensor)
    {
        return ResponsHelper::successGetData($mRoomSensor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MRoomSensor $mRoomSensor)
    {
        $validator = Validator::make($request->all(), [
            'id_m_room' => 'required|exists:m_rooms,id',
            'id_m_sensor' => 'required|exists:m_sensors,id',
            'value' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoomSensor = $mRoomSensor->updateOrFail([
                'id_m_room' => $request->id_m_room,
                'id_m_sensor' => $request->id_m_sensor,
                'value' => $request->value,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoomSensor) {
            return ResponsHelper::successChangeData($mRoomSensor, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MRoomSensor $mRoomSensor, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mRoomSensor->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mRoomSensor->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
