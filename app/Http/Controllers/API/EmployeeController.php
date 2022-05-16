<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Employee;
use App\Models\Absen;
class EmployeeController extends Controller
{

    public function save_employee(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id_employee' => 'nullable|exists:employee,id_employee,deleted_at,NULL',
                'name' => 'required',
                'email' => 'required|email|unique:employee,email,'.$request->id_employee.',id_employee,deleted_at,NULL',
                'phone' => 'required',
                'tgl_lahir' => 'required',
                'tempat_lahir' => 'required',
                'alamat' => 'required',
                'jabatan' => 'required',
                'file' => 'nullable',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                // return response gagal
                $response = [
                    'status' => false,
                    'code' => 400,
                    'message' => $validator->errors()->first(),
                ];
                return response()->json($response, 200);
            }
            if($request->file == null){
                $employee = Employee::updateOrCreate(
                    ['id_employee' => $request->id_employee],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'tgl_lahir' => $request->tgl_lahir,
                        'tempat_lahir' => $request->tempat_lahir,
                        'alamat' => $request->alamat,
                        'jabatan' => $request->jabatan,
                        'password' => Hash::make($request->password),
                    ]
                );
            }else{
                $file = $request->file('file');
                $fileName = time().'.'.$file->extension();  
                $loc = public_path('foto-employee');
                $request['filename'] = 'foto-employee/'.$fileName;
                $loct = $file->move($loc, $fileName);
                $employee = Employee::updateOrCreate(
                    ['id_employee' => $request->id_employee],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'tgl_lahir' => $request->tgl_lahir,
                        'tempat_lahir' => $request->tempat_lahir,
                        'alamat' => $request->alamat,
                        'jabatan' => $request->jabatan,
                        'foto' => $request->filename,
                        'password' => Hash::make($request->password),
                    ]
                );
            }
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Success',
                'data' => $employee
            ];
            return response()->json($response, 200);

        }catch (Throwable $e) {
            $response = [
                'status' => false,
                'code' => 400,
                'message' => $e->getMessage(),
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }
    public function get_employee(Request $request)
    {
        dd('ada');
        $employee = Employee::find($request->id);

        return response()->json($employee);
    }

    public function absen(Request $request){
        $validator = Validator::make($request->all(), [
            'id_employee' => 'required|exists:employee,id_employee,deleted_at,NULL',
            'jam_absen' => 'required',
            'lat_absen' => 'required',
            'long_absen' => 'required',
        ]);
        if ($validator->fails()) {
            // return response gagal
            $response = [
                'status' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 200);
        }

        $absen = Absen::create(
            [
            'id_employee' => $request->id_employee,
            'jam_absen' => $request->jam_absen,
            'lat_absen' => $request->lat_absen,
            'long_absen' => $request->long_absen,
            ]
        );
        if($absen){
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Success',
                'data' => $absen
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'code' => 400,
                'message' => 'Failed',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function get_absen_all(){
        $absen = Absen::get_absen_all();
        if($absen){
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Success',
                'data' => $absen
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'code' => 400,
                'message' => 'Failed',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }
}
