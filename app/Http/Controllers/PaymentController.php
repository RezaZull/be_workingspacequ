<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\TBooking;
use App\Models\TBookingLine;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createCharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grandtotal' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        $order_id = rand();
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->grandtotal,
            ],
            'customer_details' => [
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'email' => $request->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return ResponsHelper::successGetData([
            'snap_token' => $snapToken,
            'order_id' => $order_id
        ]);
    }

    public function setBookCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_t_booking' => 'required|exists:t_bookings,id',
            'order_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        if ($request->status == 'success') {
            $tBookLines = TBookingLine::where('id_t_booking', '=', $request->id_t_booking)->get();
            foreach ($tBookLines as $tBookLine) {
                $tBookLine->update([
                    'book_code' => sprintf("%06d", random_int(1, 999999))
                ]);
            }
        }
        $tBook = TBooking::find($request->id_t_booking)->update([
            'payment_status' => $request->status,
            'order_id' => $request->order_id
        ]);
        return ResponsHelper::successChangeData($tBook);
    }
}
