<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\TCartLine;
use DB;
use Illuminate\Http\Request;
use Validator;

class TCartLineController extends Controller
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
        $TCartLine = TCartLine::with('room.roomType')->where('status', '=', 'active');
        if (isset($searchParam) && isset($searchValue)) {
            $TCartLine = $TCartLine->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $TCartLine = $TCartLine->orderBy($orderBy, $orderDir);
        }
        $TCartLine = isset($pagination) ? $TCartLine->paginate($pagination) : $TCartLine->get();

        return ResponsHelper::successGetData($TCartLine);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_m_room' => 'required|exists:m_rooms,id',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tCartLine = TCartLine::create([
                'id_m_user' => $request->user_id,
                'id_m_room' => $request->id_m_room,
                'status' => 'active',
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["T_Cart_Line"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tCartLine) {
            return ResponsHelper::successChangeData($tCartLine, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TCartLine $tCartLine)
    {
        return ResponsHelper::successGetData($tCartLine);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TCartLine $tCartLine)
    {
        $validator = Validator::make($request->all(), [
            'id_m_room' => 'required|exists:id_m_rooms,id',
            'flag_active' => 'required',
            'status' => 'required',
            'flag_chekced' => 'required',
            'date_checkin' => 'nullable',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tCartLine = $tCartLine->updateOrFail([
                'id_m_user' => $request->user_id,
                'id_m_room' => $request->id_m_room,
                'status' => $request->status,
                'flag_chekced' => $request->flag_chekced,
                'date_checkin' => $request->date_checkin,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tCartLine) {
            return ResponsHelper::successChangeData($tCartLine, "Success update data");
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.id' => 'required',
            'data.*status' => 'required',
            'data.*flag_chekced' => 'required',
            'data.*date_checkin' => 'nullable',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }

        DB::beginTransaction();
        try {
            foreach ($request->data as $dataUpdate) {
                $updateData = TCartLine::find($dataUpdate['id'])->update([
                    'status' => $dataUpdate['status'],
                    'flag_chekced' => $dataUpdate['flag_chekced'],
                    'date_checkin' => $dataUpdate['date_checkin'],
                    'updated_by' => $request->user_id,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        return ResponsHelper::successChangeData("", "Success update data");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TCartLine $tCartLine, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $tCartLine->update([
            'deleted_by' => $request->user_id
        ]);

        if ($tCartLine->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
