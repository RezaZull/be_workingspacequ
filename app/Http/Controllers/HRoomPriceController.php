<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\HRoomPrice;
use App\Models\MRoom;
use App\Models\MRoomType;
use DB;
use Illuminate\Http\Request;
use Validator;

class HRoomPriceController extends Controller
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
        $Msensor = new HRoomPrice();
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
            'id_m_room' => 'required|exists:m_rooms,id',
            'price' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        $mRoom = MRoom::findOrFail($request->id_m_room);
        $roomType = MRoomType::findOrFail($mRoom->id_m_room_type);
        if ($request->price > $roomType->max_price || $request->price < $roomType->low_price) {
            return ResponsHelper::validatorError(["price" => ["Price not match in room type"]]);
        }
        DB::beginTransaction();
        try {
            $hRoomPrice = HRoomPrice::create([
                'id_m_room' => $request->id_m_room,
                'price' => $request->price,
                'created_by' => $request->user_id,
                'obj_type' => $this->objTypes["H_Room_Price"],
                'flag_active' => true,
            ]);
            $mRoom->updateOrFail([
                'price' => $request->price,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mRoom) {
            return ResponsHelper::successChangeData($hRoomPrice, "Success update data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HRoomPrice $hRoomPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HRoomPrice $hRoomPrice)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HRoomPrice $hRoomPrice)
    {
        //
    }
}
