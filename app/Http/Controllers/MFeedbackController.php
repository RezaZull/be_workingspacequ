<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MFeedback;
use DB;
use Illuminate\Http\Request;
use Validator;

class MFeedbackController extends Controller
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
        $MFeedback = new MFeedback();
        if (isset($searchParam) && isset($searchValue)) {
            $MFeedback = $MFeedback->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MFeedback = $MFeedback->orderBy($orderBy, $orderDir);
        }
        $MFeedback = isset($pagination) ? $MFeedback->paginate($pagination) : $MFeedback->get();

        return ResponsHelper::successGetData($MFeedback);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'feedback' => 'required',
            'id_t_booking' => 'required|exists:t_booking,id',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mFeedback = MFeedback::create([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'id_t_booking' => $request->id_t_booking,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["M_Feedback"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mFeedback) {
            return ResponsHelper::successChangeData($mFeedback, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MFeedback $mFeedback)
    {
        return ResponsHelper::successGetData($mFeedback);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MFeedback $mFeedback)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'feedback' => 'required',
            'id_t_booking' => 'required|exists:t_booking,id',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mFeedback = $mFeedback->updateOrFail([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'id_t_booking' => $request->id_t_booking,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mFeedback) {
            return ResponsHelper::successChangeData($mFeedback, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MFeedback $mFeedback, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mFeedback->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mFeedback->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
