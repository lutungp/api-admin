<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createCustomerCall(Request $request)
    {
        $input = $request->only('call_subject', 'call_description', 'call_temperature', 'call_offerwa', 'call_interesting', 'call_status_direction',
                                'call_status', 'call_start_date', 'call_hourduration', 'call_minutesduration', 't_relatedproj_id', 'm_userowner_id',
                                'm_userowner_nama');

        $validator = Validator::make($input, [
            'call_subject'      => 'required|max:50',
            'call_description'  => 'required',
        ], [
            'call_subject.required' => 'Call Subject cannot empty',
            'call_subject.max'      => 'Call Subject should be at least 3 characters and a maximum of 50 characters',
            'call_description.required'  => 'First Name cannot empty'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new Call();

            $dataSave->call_key = Str::random(40);

            $dataSave->call_subject         = $input['call_subject'];
            $dataSave->call_description     = $input['call_description'];
            $dataSave->call_temperature     = $input['call_temperature'];
            $dataSave->call_offerwa         = $input['call_offerwa'];
            $dataSave->call_interesting     = $input['call_interesting'];
            $dataSave->call_status_direction = $input['call_status_direction'];
            $dataSave->call_status          = $input['call_status'];
            $dataSave->call_start_date      = $input['call_start_date'];
            $dataSave->call_hourduration    = $input['call_hourduration'];
            $dataSave->call_minutesduration = $input['call_minutesduration'];
            $dataSave->t_relatedproj_id     = $input['t_relatedproj_id'];
            $dataSave->m_userowner_id       = $input['m_userowner_id'];

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }
}
