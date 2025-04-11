<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MMenu;
use DB;
use Illuminate\Http\Request;
use Validator;

class MMenuController extends Controller
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
        $MMenu = new MMenu();
        if (isset($searchParam) && isset($searchValue)) {
            $MMenu = $MMenu->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $MMenu = $MMenu->orderBy($orderBy, $orderDir);
        }
        $MMenu = isset($pagination) ? $MMenu->paginate($pagination) : $MMenu->get();

        return ResponsHelper::successGetData($MMenu);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'route' => 'required',
            'description' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mMenu = MMenu::create([
                'name' => $request->name,
                'route' => $request->route,
                'flag_active' => $request->flag_active,
                'description' => $request->description,
                'obj_type' => $this->objTypes["M_Menu"],
                'created_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mMenu) {
            return ResponsHelper::successChangeData($mMenu, "Success create Data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MMenu $mMenu)
    {
        return ResponsHelper::successGetData($mMenu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MMenu $mMenu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'route' => 'required',
            'description' => 'required',
            'flag_active' => 'required',
            'user_id' => 'required|exists:m_users,id'
        ]);

        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $mMenu = $mMenu->updateOrFail([
                'name' => $request->name,
                'route' => $request->route,
                'flag_active' => $request->flag_active,
                'description' => $request->description,
                'updated_by' => $request->user_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponsHelper::conflictError(409, "Conflict error");
        }
        if ($mMenu) {
            return ResponsHelper::successChangeData($mMenu, "Success update data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MMenu $mMenu, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        DB::beginTransaction();
        $mMenu->update([
            'deleted_by' => $request->user_id
        ]);

        if ($mMenu->delete()) {
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }
}
