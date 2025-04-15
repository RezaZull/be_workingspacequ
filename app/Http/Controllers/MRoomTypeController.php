<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MRoomType;
use DB;
use Illuminate\Http\Request;
use Validator;

class MRoomTypeController extends Controller
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
        $MRoomType = new MRoomType();
        if (isset($searchParam) && isset($searchValue)) {
            $MRoomType = $MRoomType->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MRoomType = $MRoomType->orderBy($orderBy, $orderDir);
        }
        $MRoomType = isset($pagination) ? $MRoomType->paginate($pagination) : $MRoomType->get();

        return ResponsHelper::successGetData($MRoomType);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'max_capacity' => 'required',
            'max_price' => 'required',
            'low_price' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoomType = MRoomType::create([
                'name' => $request->name,
                'max_capacity' => $request->max_capacity,
                'max_price' => $request->max_price,
                'low_price' => $request->low_price,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Room_Type"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoomType) {
            return ResponsHelper::successChangeData($mRoomType, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MRoomType $mRoomType)
    {
        return ResponsHelper::successGetData($mRoomType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MRoomType $mRoomType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'max_capacity' => 'required',
            'max_price' => 'required',
            'low_price' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mRoomType = $mRoomType->updateOrFail([
                'name' => $request->name,
                'max_capacity' => $request->max_capacity,
                'max_price' => $request->max_price,
                'low_price' => $request->low_price,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoomType) {
            return ResponsHelper::successChangeData($mRoomType, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MRoomType $mRoomType, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mRoomType->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mRoomType->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
