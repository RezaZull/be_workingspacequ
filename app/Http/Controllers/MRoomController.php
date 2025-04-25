<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\HRoomPrice;
use App\Models\MRoom;
use DB;
use Illuminate\Http\Request;
use Validator;

class MRoomController extends Controller
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
        $MRoom = MRoom::with('roomType');
        if (isset($searchParam) && isset($searchValue)) {
            $MRoom = $MRoom->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MRoom = $MRoom->orderBy($orderBy, $orderDir);
        }
        $MRoom = isset($pagination) ? $MRoom->paginate($pagination) : $MRoom->get();

        return ResponsHelper::successGetData($MRoom);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_m_room_type' => 'required|exists:m_room_types,id',
            'price' => 'required',
            'current_capacity' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoom = MRoom::create([
                'name' => $request->name,
                'id_m_room_type' => $request->id_m_room_type,
                'price' => $request->price,
                'current_capacity' => $request->current_capacity,
                'obj_type' => $this->objTypes["M_Room"],
                'flag_active' => $request->flag_active,
                'created_by' => $request->user_id,
            ]);
            $mRoomHistory = HRoomPrice::create(
                [
                    'id_m_room' => $mRoom->id,
                    'price' => $mRoom->price,
                    'obj_type' => $this->objTypes["H_Room_Price"],
                    'flag_active' => $request->flag_active,
                    'created_by' => $request->user_id,
                ]
            );
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
    public function show(MRoom $mRoom)
    {
        return ResponsHelper::successGetData($mRoom->load('roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MRoom $mRoom)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_m_room_type' => 'required|exists:m_room_types,id',
            'price' => 'required',
            'current_capacity' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoom = $mRoom->updateOrFail([
                'name' => $request->name,
                'id_m_room_type' => $request->id_m_room_type,
                'price' => $request->price,
                'current_capacity' => $request->current_capacity,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoom) {
            return ResponsHelper::successChangeData($mRoom, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MRoom $mRoom, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mRoom->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mRoom->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
