<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MUser;
use App\Models\TBookingLine;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class TBookingLineController extends Controller
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
        $TBookingLine = TBookingLine::with(['room.roomType', 'room.roomImage']);
        if (isset($searchParam) && isset($searchValue)) {
            $TBookingLine = $TBookingLine->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $TBookingLine = $TBookingLine->orderBy($orderBy, $orderDir);
        }
        $TBookingLine = isset($pagination) ? $TBookingLine->paginate($pagination) : $TBookingLine->get();

        return ResponsHelper::successGetData($TBookingLine);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_t_booking' => 'required|exists:t_bookings,id',
            'id_m_room' => 'required|exists:m_rooms,id',
            'date_checking' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tBookingLine = TBookingLine::create([
                'id_t_booking' => $request->id_t_booking,
                'id_m_room' => $request->id_m_room,
                'date_checking' => $request->date_checking,
                'flag_active' => $request->flag_active,
                'obj_type' => $this->objTypes["T_Booking_Line"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tBookingLine) {
            return ResponsHelper::successChangeData($tBookingLine, "Success create Data");
        }
    }

    public function checkBookId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_code' => 'required|exists:t_booking_lines,book_code|min_digits:6|max_digits:6',
            'id_room' => 'required|exists:m_rooms,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'access' => false
            ]);
        }
        $TBookLine = TBookingLine::where('book_code', $request->book_code)
            ->where('id_m_room', $request->id_room)
            ->whereDate('date_checkin', Carbon::today())
            ->count();
        // dd($TBookLine);
        return response()->json([
            'access' => $TBookLine > 0
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(TBookingLine $tBookingLine)
    {
        return ResponsHelper::successGetData($tBookingLine);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TBookingLine $tBookingLine)
    {
        $validator = Validator::make($request->all(), [
            'id_t_booking' => 'required|exists:t_bookings,id',
            'id_m_room' => 'required|exists:m_rooms,id',
            'date_checking' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $tBookingLine = $tBookingLine->updateOrFail([
                'id_t_booking' => $request->id_t_booking,
                'id_m_room' => $request->id_m_room,
                'date_checking' => $request->date_checking,
                'flag_active' => $request->flag_active,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($tBookingLine) {
            return ResponsHelper::successChangeData($tBookingLine, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TBookingLine $tBookingLine, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $tBookingLine->update([
            'deleted_by' => $request->user_id
        ]);

        if ($tBookingLine->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }


    public function getAllBookingLineByUser(MUser $mUser)
    {
        $roomBook = TBookingLine::with([
            'room.roomImage',
            'room.roomType',
            'bookingHeader'
        ])
            ->whereHas('bookingHeader', function ($query) use ($mUser) {
                $query->where('id_m_user', $mUser->id);
            })
            ->where('book_code', '!=', "")
            ->orderBy('date_checkin', 'desc')
            ->get();

        return ResponsHelper::successGetData($roomBook);
    }
}
