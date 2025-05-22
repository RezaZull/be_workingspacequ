<?php

namespace App\Http\Controllers;

use App\Helper\ResponsHelper;
use App\Models\MRoomImage;
use Buglinjo\LaravelWebp\Facades\Webp;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;
use Validator;

class MRoomImageController extends Controller
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
        $Munit = new MRoomImage();
        if (isset($searchParam) && isset($searchValue)) {
            $Munit = $Munit->where($searchParam, 'LIKE', "%$searchValue%");
        }
        if (isset($orderBy) && isset($orderDir)) {
            $Munit = $Munit->orderBy($orderBy, $orderDir);
        }
        $Munit = isset($pagination) ? $Munit->paginate($pagination) : $Munit->get();

        return ResponsHelper::successGetData($Munit);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MRoomImage $mRoomImage, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::validatorError($validator->errors());
        }
        $img_path = $mRoomImage->img_path;
        DB::beginTransaction();
        $mRoomImage->update([
            'deleted_by' => $request->user_id
        ]);
        if ($mRoomImage->delete()) {
            Storage::delete(public_path($img_path));
            DB::commit();
            return ResponsHelper::successChangeData("true", "Successfully delete data");
        }
        DB::rollBack();
        return ResponsHelper::conflictError(409, "cant delete data");
    }

    public function uploadPicture(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'img_file' => 'required|image|mimes:jpeg,jpg,png|max:4096',
            'id_m_room' => 'required|exists:m_rooms,id',
            'user_id' => 'required|exists:m_users,id'
        ]);
        if ($validator->fails()) {
            return ResponsHelper::customResponse(442, false, "Validation error", [
                "error" => $validator->errors()
            ]);
        }
        $webp = Webp::make($request->file('img_file'));
        $newPath = "storage/images/profile/" . $request->file('img_file')->hashName() . ".webp";
        if ($webp->save(public_path($newPath))) {
            $MRoomImage = MRoomImage::create([
                'img_path' => $newPath,
                'id_m_room' => $request->id_m_room,
                'obj_type' => $this->objTypes['M_Room_Image'],
                'flag_active' => true,
                'created_by' => $request->user_id,
            ]);
            return ResponsHelper::successChangeData($MRoomImage, "Success Change Picture");
        }
        return ResponsHelper::conflictError(409, "Failed change picture");
    }
}
