<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Employee;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:8',
            'file' => 'nullable'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }   
        if($request->file == null){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                // 'foto' => $request->foto
            ]);
        }else{
            $file = $request->file('file');
            $fileName = time().'.'.$file->extension();  
            $loc = public_path('foto-user');
            $request['filename'] = 'foto-user/'.$fileName;
            $loct = $file->move($loc, $fileName);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request['filename'],
                // 'foto' => $request->foto
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        // if (!Auth::attempt($request->only('email', 'password')))
        // {
        //     return response()
        //         ->json(['message' => 'Unauthorized'], 401);
        // }

        $user = User::where('email', $request['email'])->first();
        $employee = Employee::where('email', $request['email'])->first();
        // dd($employee);
        if($user != null){
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()
                ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
        }else if($employee != null){
            // dd('ada');
            $token = $employee->createToken('auth_token')->plainTextToken;
            return response()
            ->json(['message' => 'Hi '.$employee->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
        }else{
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }
        
    }
}
