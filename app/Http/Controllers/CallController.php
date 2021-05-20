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

    public function getCallDetail($id)
    {
        $data = Call::where('call_key', $id)->first();

        $customer = [
            'call_id'           => $data->call_id,
            'call_key'          => $data->call_key,
            'call_subject'      => $data->call_subject,
            'call_description'  => $data->call_description,
            'call_temperature'  => $data->call_temperature,
            'call_offerwa'      => $data->call_offerwa,
            'call_interesting'  => $data->call_interesting,
            'call_status_direction' => $data->call_status_direction,
            'call_status'       => $data->call_status,
            'call_start_date'   => $data->call_start_date,
            't_relatedproj_id'  => $data->t_relatedproj_id,
            't_relateddeal_id'  => $data->t_relateddeal_id,
            'call_hourduration' => $data->call_hourduration,
            'call_minutesduration' => $data->call_minutesduration,
            't_relatedissue_id' => $data->t_relatedissue_id,
            'm_userowner_id'    => $data->m_userowner_id,
            'm_userowner_nama'  => $data->owner->name,
            't_relatedticket_id' => $data->t_relatedticket_id,
            'm_customerrelated_id' => $data->m_customerrelated_id,
            'm_customerrelated_nama' => $data->customer->customer_firstname . ' ' . $data->customer->customer_lastname
        ];

        $res['customer'] = $customer;
        return response()->json($res, 200);
    }

    public function createCustomerCall($id, Request $request)
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
            'call_description.required'  => 'Description cannot empty'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        // try {
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
            $dataSave->m_customerrelated_id = $id;

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        // } catch (\Throwable $th) {
        //     $res["status"] = "failure";
        //     return response()->json($res, 500);
        // }

        $res["status"] = "successs";
        return response()->json($res);
    }

    public function getCalls(Request $request)
    {
        $filter = $request->get('filter');
        $calls = Call::where('call_aktif', 1);
        if ($filter <> '') {
            $calls = $calls->whereRaw("UPPER(call_subject) LIKE '%" . strtoupper($filter) . "%'");
        }

        $calls = $calls->paginate(20);

        $datacustomer = [];
        foreach ($calls->items() as $key => $value) {
            $datacustomer[] = [
                'call_aktif'        => $value->call_aktif,
                'call_description'  => $value->call_description,
                'call_hourduration' => $value->call_hourduration,
                'call_id'           => $value->call_id,
                'call_interesting'  => $value->call_interesting,
                'call_key'          => $value->call_key,
                'call_minutesduration' => $value->call_minutesduration,
                'call_offerwa'      => $value->call_offerwa,
                'call_start_date'   => $value->call_start_date,
                'call_status'       => $value->call_status,
                'call_status_direction' => $value->call_status_direction,
                'call_subject'      => $value->call_subject,
                'call_temperature'  => $value->call_temperature,
                'm_customerrelated_id' => $value->m_customerrelated_id,
                'm_userowner_id'    => $value->m_userowner_id,
                'm_userowner_nama'  => $value->customer->customer_firstname,
                't_relateddeal_id'  => $value->t_relateddeal_id,
                't_relatedissue_id' => $value->t_relatedissue_id,
                't_relatedproj_id'  => $value->t_relatedproj_id,
                't_relatedticket_id' => $value->t_relatedticket_id
            ];
        }

        $res['data'] = $datacustomer;
        $res['total'] = $calls->total();

        return response()->json($res, 200);
    }

    public function updateCall($id, Request $request)
    {
        $input = $request->only('call_subject', 'call_description', 'call_temperature', 'call_offerwa', 'call_interesting', 'call_status_direction',
                                'call_status', 'call_start_date', 'call_hourduration', 'call_minutesduration', 't_relatedproj_id', 'm_userowner_id',
                                'm_userowner_nama', 'm_customerrelated_id');

        $validator = Validator::make($input, [
            'call_subject'      => 'required|max:50',
            'call_description'  => 'required',
        ], [
            'call_subject.required' => 'Call Subject cannot empty',
            'call_subject.max'      => 'Call Subject should be at least 3 characters and a maximum of 50 characters',
            'call_description.required'  => 'Description cannot empty',
            'm_customerrelated_id' => 'Related Customer cannot empty'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = Call::find($id);
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
            $dataSave->m_customerrelated_id = $input['m_customerrelated_id'];

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function createCall(Request $request)
    {
        $input = $request->only('call_subject', 'call_description', 'call_temperature', 'call_offerwa', 'call_interesting', 'call_status_direction',
                                'call_status', 'call_start_date', 'call_hourduration', 'call_minutesduration', 't_relatedproj_id', 'm_userowner_id',
                                'm_userowner_nama', 'm_customerrelated_id');

        $validator = Validator::make($input, [
            'call_subject'      => 'required|max:50',
            'call_description'  => 'required',
        ], [
            'call_subject.required' => 'Call Subject cannot empty',
            'call_subject.max'      => 'Call Subject should be at least 3 characters and a maximum of 50 characters',
            'call_description.required'  => 'Description cannot empty',
            'm_customerrelated_id' => 'Related Customer cannot empty'
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
            $dataSave->m_customerrelated_id = $input['m_customerrelated_id'];

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($input);
    }

    public function deleteCall($id)
    {
        $res["status"] = "success";
        try {
            $call = Call::find($id);
            $call->call_aktif = 't';
            $call->disabled_by = auth()->user()->user_id;
            $call->disabled_date = date('Y-m-d H:i:s');
            $call->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function getCallCustomer($id)
    {
        $qcall = Call::where('m_customerrelated_id', $id)->orderBy('created_date', 'DESC')->get();
        $datacall = [];
        foreach ($qcall as $key => $value) {
            $datacall[] = [
                'call_id'           => $value->call_id,
                'call_description'  => $value->call_description,
                'call_hourduration' => $value->call_hourduration,
                'call_interesting'  => $value->call_interesting,
                'call_key'          => $value->call_key,
                'call_minutesduration' => $value->call_minutesduration,
                'call_offerwa'      => $value->call_offerwa,
                'call_start_date'   => $value->call_start_date,
                'call_start_date2'   => date('d M Y', strtotime($value->call_start_date)),
                'call_status'       => $value->call_status,
                'call_status_direction' => $value->call_status_direction,
                'call_subject'      => $value->call_subject,
                'call_temperature'  => $value->call_temperature,
                'm_customerrelated_id' => $value->m_customerrelated_id,
                'm_userowner_id'    => $value->m_userowner_id,
                't_relateddeal_id'  => $value->t_relateddeal_id,
                't_relatedissue_id' => $value->t_relatedissue_id,
                't_relatedproj_id'  => $value->t_relatedproj_id,
                't_relatedticket_id' => $value->t_relatedticket_id,
                'created_date'  => date('d M Y, H:i', strtotime($value->created_date))
            ];
        }

        return response()->json($datacall);
    }

    public function importCall(Request $request)
    {

    }

    public function exportCall(Request $request)
    {

    }

}
