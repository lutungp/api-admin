<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getCustomer (Request $request) {
        $filter = $request->get('filter');
        $customers = Customer::where('customer_aktif', 1);
        if ($filter <> '') {
            $customers = $customers->whereRaw("UPPER(customer_nama) LIKE '%" . strtoupper($filter) . "%'");
        }

        $customers = $customers->paginate(20);

        $data = [];
        foreach ($customers->items() as $key => $value) {
            $data[] = [
                'customer_id'       => $value->customer_id,
                'customer_key'      => $value->customer_key,
                'customer_solutation' => $value->customer_solutation,
                'customer_firstname' => $value->customer_firstname,
                'customer_lastname'  => $value->customer_lastname,
                'customer_alamat'   => $value->customer_alamat,
                'customer_notelp'   => $value->customer_notelp,
                'customer_birthdate' => $value->customer_birthdate,
                'customer_email'    => $value->customer_email,
                'customer_desc'     => $value->customer_desc,
                'customer_job'      => $value->customer_job,
                'customer_departement' => $value->customer_departement,
                'customer_source'   => $value->customer_source,
                'customer_phone1'   => $value->customer_phone1,
                'customer_phone2'   => $value->customer_phone2,
                'customer_phone3'   => $value->customer_phone3,
                'customer_fax'      => $value->customer_fax,
                'customer_address'  => $value->customer_address,
                'customer_city'     => $value->customer_city,
                'customer_state'    => $value->customer_state,
                'customer_postalcode' => $value->customer_postalcode,
                'customer_country'  => $value->customer_country
            ];
        }

        $res['data'] = $data;
        $res['total'] = $customers->total();

        return response()->json($res, 200);
    }

    public function getCustomerDetail($id)
    {
        $select = ['customer_id', 'customer_key', 'customer_solutation', 'customer_firstname', 'customer_lastname', 'customer_alamat', 'customer_notelp',
                  'customer_birthdate', 'customer_email', 'customer_desc', 'customer_job', 'customer_departement', 'customer_source', 'customer_phone1',
                  'customer_phone2', 'customer_phone3', 'customer_fax', 'customer_address', 'customer_city', 'customer_state', 'customer_postalcode', 'customer_country'];

        $data = Customer::select($select)->where('customer_key', $id)->first();
        return response()->json($data, 200);
    }

    public function createCustomer (Request $request) {
        $input = $request->only('customer_id', 'customer_solutation', 'customer_lastname', 'customer_email', 'customer_firstname', 'customer_address',
                                'customer_phone1', 'customer_phone2', 'customer_job');

        $validator = Validator::make($input, [
            'customer_solutation' => 'required',
            'customer_lastname'   => 'required|max:100',
            'customer_email'      => 'required|email',
            'customer_firstname'  => 'required|max:100',
            'customer_address'    => 'required|max:100'
        ], [
            'customer_solutation.required' => 'Solutation Name cannot empty',
            'customer_lastname.required'   => 'Last Name cannot empty',
            'customer_lastname.max'        => 'Last Name should be at least 3 characters and a maximum of 100 characters',
            'customer_firstname.required'  => 'First Name cannot empty',
            'customer_firstname.max'       => 'Last Name should be at least 3 characters and a maximum of 100 characters'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new Customer();

            $dataSave->customer_key = Str::random(40);
            $dataSave->customer_solutation = $input['customer_solutation'];
            $dataSave->customer_lastname   = $input['customer_lastname'];
            $dataSave->customer_email      = $input['customer_email'];
            $dataSave->customer_firstname  = $input['customer_firstname'];
            $dataSave->customer_address    = $input['customer_address'];

            $dataSave->customer_phone1    = $input['customer_phone1'];
            $dataSave->customer_phone2    = $input['customer_phone2'];
            $dataSave->customer_job       = $input['customer_job'];

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }

    public function updateCustomer (Request $request) {
        $input = $request->only('customer_id', 'customer_solutation', 'customer_lastname', 'customer_email', 'customer_firstname', 'customer_address',
                'customer_phone1', 'customer_phone2', 'customer_job');

        $validator = Validator::make($input, [
            'customer_solutation' => 'required',
            'customer_lastname'   => 'required|max:100',
            'customer_email'      => 'required|email',
            'customer_firstname'  => 'required|max:100',
            'customer_address'    => 'required|max:100'
        ], [
            'customer_solutation.required' => 'Solutation Name cannot empty',
            'customer_lastname.required'   => 'Last Name cannot empty',
            'customer_lastname.max'        => 'Last Name should be at least 3 characters and a maximum of 100 characters',
            'customer_firstname.required'  => 'First Name cannot empty',
            'customer_firstname.max'       => 'Last Name should be at least 3 characters and a maximum of 100 characters'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        $customer_id = $input["customer_id"];
        $dataSave = Customer::find($customer_id);
        try {

            $dataSave->customer_solutation = $input['customer_solutation'];
            $dataSave->customer_lastname   = $input['customer_lastname'];
            $dataSave->customer_email      = $input['customer_email'];
            $dataSave->customer_firstname  = $input['customer_firstname'];
            $dataSave->customer_address    = $input['customer_address'];

            $dataSave->customer_phone1    = $input['customer_phone1'];
            $dataSave->customer_phone2    = $input['customer_phone2'];
            $dataSave->customer_job       = $input['customer_job'];

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }

    public function deleteCustomer ($id) {
        $res["status"] = "success";
        try {
            $customer = Customer::find($id);

            if ($customer->calls->count() > 0) {
                $res["status"] = "failure";
                return response()->json($res, 500);
            }

            $customer->customer_aktif = 't';
            $customer->disabled_by = auth()->user()->user_id;
            $customer->disabled_date = date('Y-m-d H:i:s');
            $customer->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function importCustomer(Request $request)
    {
        $input = $request->only('file');
        $validator = Validator::make($request->all(), [
            'file' => 'max:8000|mimes:xlsx,xls,csv',
        ], [
            'file.max' => "Upload Maksimal 8 MB",
            'file.mimes' => "File type must be xlsx,xls,csv"
        ]);

        $res["status"] = "success";
        if ($request->hasFile('file')) {
            $data = Excel::toArray(new CustomerImport(), $request->file('file'));


            foreach ($data[0] as $key => $value) {

                $dataSave = new Customer();

                $dataSave->customer_key = Str::random(40);
                $dataSave->customer_solutation = $value['solutations'];
                $dataSave->customer_lastname   = $value['last_name'];
                $dataSave->customer_email      = $value['customer_email'];
                $dataSave->customer_firstname  = $value['first_name'];
                $dataSave->customer_address    = $value['address'];

                $dataSave->customer_phone1    = $value['mobile_phone'];
                $dataSave->customer_phone2    = $value['work_phone'];
                $dataSave->customer_job       = $value['job_title'];

                $dataSave->created_by = auth()->user()->user_id;
                $dataSave->created_date = date("Y-m-d H:i:s");

                $dataSave->save();
            }

            return response()->json($res, 200);
        }
    }

    public function exportCustomer(Request $request)
    {
        (new CustomerExport)->store('customer-exported.xlsx', 'public_dokumen');

        $res['url'] = env('APP_URL') . '/dokumen/customer-exported.xlsx';
        return response()->json($res, 200);
    }

}
