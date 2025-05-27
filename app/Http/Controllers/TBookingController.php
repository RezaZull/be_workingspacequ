<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MRoom;
use App\Models\TBooking;
use App\Models\TBookingLine;
use App\Models\TCartLine;
use Carbon\Carbon;
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

    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*id' => 'required|exists:t_cart_lines,id',
            'data.*id_m_room' => 'required|exists:m_rooms,id',
            'data.*date_checkin' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tBooking = TBooking::create([
                'id_m_user' => $request->user_id,
                'grandtotal' => '0',
                'date_book' => Carbon::now(),
                'payment_status' => 'pending',
                'order_id' => '',
                'flag_active' => true,
                'obj_type' => $this->objTypes["T_Booking"],
                'created_by' => $request->user_id,
            ]);
            $BookingLineData = [];
            $grandTotal = 0;
            foreach ($request->data as $data) {
                $dataRoom = MRoom::find($data['id_m_room']);
                $grandTotal += $dataRoom->price;
                $BookingLineData[] = [
                    'id_t_booking' => $tBooking['id'],
                    'id_m_room' => $data['id_m_room'],
                    'date_checkin' => $data['date_checkin'],
                    'book_code' => "",
                    'flag_active' => true,
                    'obj_type' => $this->objTypes["T_Booking_Line"],
                    'created_by' => $request->user_id,
                    'created_at' => Carbon::now(),
                ];
                $updateData = TCartLine::find($data['id'])->update([
                    'status' => 'complete',
                    'updated_by' => $request->user_id,
                ]);
            }
            $tBookingLine = TBookingLine::insert($BookingLineData);
            $tBooking->update([
                'grandtotal' => $grandTotal
            ]);

            DB::commit();
            return ResponsHelper::successChangeData($tBooking, "Success create Data");
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(TBooking $tBooking)
    {
        return ResponsHelper::successGetData($tBooking->load('user'));
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
