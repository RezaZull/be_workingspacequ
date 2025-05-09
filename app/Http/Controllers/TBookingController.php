<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\TBooking;
use DB;
use Illuminate\Http\Request;
use Validator;

class TBookingController extends Controller
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
        $TBooking = new TBooking();
        if (isset($searchParam) && isset($searchValue)) {
            $TBooking = $TBooking->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $TBooking = $TBooking->orderBy($orderBy, $orderDir);
        }
        $TBooking = isset($pagination) ? $TBooking->paginate($pagination) : $TBooking->get();

        return ResponsHelper::successGetData($TBooking);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grand_total' => 'required',
            'date_book' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tBooking = TBooking::create([
                'id_m_user' => $request->user_id,
                'grand_total' => $request->grand_total,
                'date_book' => $request->date_book,
                'payment_status' => 'pending',
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["T_Booking"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tBooking) {
            return ResponsHelper::successChangeData($tBooking, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TBooking $tBooking)
    {
        return ResponsHelper::successGetData($tBooking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TBooking $tBooking)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required',
            'grand_total' => 'required',
            'date_book' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tBooking = $tBooking->updateOrFail([
                'id_m_user' => $request->user_id,
                'grand_total' => $request->grand_total,
                'date_book' => $request->date_book,
                'payment_status' => $request->payment_status,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tBooking) {
            return ResponsHelper::successChangeData($tBooking, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TBooking $tBooking, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $tBooking->update([
            'deleted_by' => $request->user_id
        ]);

        if ($tBooking->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
