<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getActivity(Request $request)
    {
        $activity = Activity::where("activity_aktif", "y")->paginate(20);
        $dataActivity  = [];
        foreach ($activity->items() as $key => $value) {
            $dataActivity[] = [
                "activity_id" => $value->activity_id,
                "activity_no" => $value->activity_no,
                "activity_tgl" => $value->activity_tgl,
                "activity_deskripsi" => $value->activity_deskripsi,
                "m_user_id" => $value->m_user_id,
                "name" => $value->customer->name,
                "activity_status" => $value->activity_status
            ];
        }

        $data["activity"] = $dataActivity;
        $data["total"] = $activity->total();

        return response()->json($data);
    }

    public function createActivity(Request $request)
    {
        $input = $request->only('activity_deskripsi', 'activity_tgl', 'm_user_id');
        $validator = Validator::make($input, [
            'activity_tgl' => 'required',
            'activity_deskripsi' => 'required|min:10',
            'm_user_id' => 'required'
        ], [
            'activity_tgl.required' => 'Date cannot be empty',
            'activity_deskripsi.required' => 'Description cannot be empty',
            'activity_deskripsi.min' => 'Description of at least 10 characters',
            'm_user_id.required' => 'Customer cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new Activity();

            $activity_no = $this->getLastNumber();
            $dataSave->activity_no = $activity_no;
            $dataSave->activity_tgl = date('Y-m-d H:i:s', strtotime($input["activity_tgl"]));
            $dataSave->activity_deskripsi = $input["activity_deskripsi"];
            $dataSave->m_user_id = $input["m_user_id"];
            $dataSave->activity_status = "NEW";
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $activity_id = $dataSave->activity_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }


    public function updateActivity(Request $request)
    {
        $input = $request->only('activity_id','activity_deskripsi', 'activity_tgl', 'm_user_id');

        $activity_id = $input["activity_id"];
        $validator = Validator::make($input, [
            'activity_tgl' => 'required',
            'activity_deskripsi' => 'required|min:10',
            'm_user_id' => 'required'
        ], [
            'activity_tgl.required' => 'Date cannot be empty',
            'activity_deskripsi.required' => 'Description cannot be empty',
            'activity_deskripsi.min' => 'Description of at least 10 characters',
            'm_user_id.required' => 'Customer cannot be empty',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = Activity::find($activity_id);

        $res["status"] = "success";
        try {
            $dataSave->activity_tgl = date('Y-m-d H:i:s', strtotime($input["activity_tgl"]));
            $dataSave->activity_deskripsi = $input["activity_deskripsi"];
            $dataSave->m_user_id = $input["m_user_id"];
            $dataSave->updated_by = auth()->user()->user_id;
            $dataSave->updated_date = date("Y-m-d H:i:s");
            $dataSave->revised = $dataSave->revised+1;

            $dataSave->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function deleteActivity($id)
    {
        $res["status"] = "success";
        try {
            $activity = Activity::find($id);
            $activity->activity_aktif = 't';
            $activity->disabled_by = auth()->user()->user_id;
            $activity->disabled_date = date("Y-m-d H:i:s");
            $activity->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function confirmActivity($id)
    {
        $res["status"] = "success";
        try {
            $activity = Activity::find($id);
            $activity->activity_status = 'CONFIRM';
            $activity->confirm_by = auth()->user()->user_id;
            $activity->confirm_date = date("Y-m-d H:i:s");
            $activity->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function getLastNumber()
    {
        $activity_no = Activity::select('activity_no')
                        ->orderBy('activity_id', 'DESC')
                        ->first();

        $activity_no = isset($activity_no->activity_no) ? $activity_no->activity_no : null;
        preg_match_all('!\d+!', $activity_no, $matches);
        $activity_no = empty($matches[0]) ? 1 : (int)$matches[0][0]+1;
        $activity_no = str_pad($activity_no, 8, '0', STR_PAD_LEFT);

        return 'ACT' . $activity_no;
    }
}
