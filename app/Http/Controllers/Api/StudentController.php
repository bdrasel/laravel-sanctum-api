<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    //REGISTER API
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:students',
            'password'=>'required|confirmed'
        ]);

        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->phone = isset($request->phone) ? $request->phone: '';
        $student->save();

        return response()->json(['status' => 1, 'message' => 'Student Regsiter SuccessFully!']);



    }

    //LOGIN API
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $student =  Student::where('email', "=" ,$request->email)->first();

        if(isset($student->id)){

            if(Hash::check($request->password, $student->password)){

                $token = $student->createToken("auth_token")->plainTextToken;
                return response()->json(['status' => 1, 'message' => "Student Logged in Successfully", 'access_token' => $token]);


            }else{
                return response()->json(['status' => 0, 'message' => "Password did't match"]);

            }

        }else{
            return response()->json(['status' => 0, 'message' => 'Student Not Found']);

        }


    }

    //PROFILE API
    public function profile()
    {
        return response()->json(['status' => 1, 'message' =>'Student Profile information', 'data' => auth()->user()]);
    }

    //LOGOUT API
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['status' => 1, 'message' =>'Student logged out  successfully']);
    }
}
