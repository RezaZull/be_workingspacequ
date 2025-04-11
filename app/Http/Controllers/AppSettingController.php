<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\AppSetting;
use App\Models\MRole;
use DB;
use Illuminate\Http\Request;
use Validator;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        $data['AppSettings'] = AppSetting::all();
        $data['Roles'] = MRole::where('flag_active', '=', 'true')->get();
        return ResponsHelper::successGetData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AppSetting $appSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppSetting $appSetting)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $appSetting = $appSetting->updateOrFail([
                'name' => $request->name,
                'value' => $request->value,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($appSetting) {
            return ResponsHelper::successChangeData($appSetting, "Success update data");
        }
    }
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.value' => 'required',
            'data.*.id' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }

        DB::beginTransaction();
        try {
            foreach ($request->data as $dataUpdate) {
                $updateData = AppSetting::find($dataUpdate['id'])->update([
                    'value' => $dataUpdate['value'],
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
    public function destroy(AppSetting $appSetting)
    {
        //
    }
}
