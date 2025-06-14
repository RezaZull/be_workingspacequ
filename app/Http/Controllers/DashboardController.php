<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MFeedback;
use App\Models\MRoom;
use App\Models\MRoomType;
use App\Models\MUser;
use App\Models\TBooking;
use App\Models\TBookingLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        $user = MUser::with('role')->find($request->user_id);
        $feedback = new MFeedback();
        $listBook = new TBookingLine();
        $now = Carbon::now();
        $label_date = ['Jan', 'Feb', 'Mar', 'Apr', 'Mey', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        if ($user->role->name == 'Admin') {
            $category = new MRoomType();
            $categoryList = [];
            foreach ($category->all() as $cat) {
                $value_date = [];
                foreach ($label_date as $key => $date) {
                    $value_date[] = $listBook->whereHas(
                        'room',
                        function ($query) use ($cat) {
                            $query->where('id_m_room_type', $cat->id);
                        }
                    )->whereMonth('date_checkin', '=', $key + 1)->whereYear('date_checkin', '=', $now->year)->count();
                }
                $categoryList[] = ['category' => $cat->name, 'value' => $value_date];
            }

            //total room
            $data = [
                'card_data' => [
                    'total_room' => MRoom::count(),
                    'total_booking' => TBooking::count(),
                    'total_booked_room' => TBookingLine::count(),
                    'total_feedback' => $feedback->count(),
                ],
                'sentiment_data' => [
                    'label' => ['Negatif', 'Positif'],
                    'data' => [
                        $feedback->where('flag_positif_feedback', false)->count(),
                        $feedback->where('flag_positif_feedback', true)->count()
                    ]
                ],
                'rating_data' => [
                    'label' => ['1⭐', '2⭐', '3⭐', '4⭐', '5⭐'],
                    'data' => [
                        $feedback->where('rating', 1)->count(),
                        $feedback->where('rating', 2)->count(),
                        $feedback->where('rating', 3)->count(),
                        $feedback->where('rating', 4)->count(),
                        $feedback->where('rating', 5)->count()
                    ]
                ],
                'category_line' => [
                    'year' => $now->year,
                    'label' => $label_date,
                    'data' => $categoryList
                ],
                'last_feedback' => MFeedback::with('room')->orderBy('created_at', 'desc')->limit(5)->get(),

            ];
        } else {
            $data = [
                'card_data' => [
                    'pending_moth' => TBooking::whereMonth('date_book', $now->month)->where([['payment_status', '=', 'pending'], ['id_m_user', '=', $request->user_id]])->count(),
                    'pending_all' => TBooking::where([['payment_status', '=', 'pending'], ['id_m_user', '=', $request->user_id]])->count(),
                    'success_month' => TBooking::whereMonth('date_book', $now->month)->where([['payment_status', '=', 'success'], ['id_m_user', '=', $request->user_id]])->count(),
                    'success_all' => TBooking::where([['payment_status', '=', 'success'], ['id_m_user', '=', $request->user_id]])->count(),
                ],
                'last_history' => TBooking::where('id_m_user', '=', $request->user_id)->orderBy('created_at', 'desc')->limit(5)->get(),
                'last_feedback' => MFeedback::with('room')->where('created_by', '=', $request->user_id)->orderBy('created_at', 'desc')->limit(5)->get(),
            ];
        }
        return ResponsHelper::successGetData($data);
    }
}
