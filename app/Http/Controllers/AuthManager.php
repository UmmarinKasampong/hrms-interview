<?php

namespace App\Http\Controllers;

use Illuminate\Session\SessionManager;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AuthManager extends Controller
{
    protected $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }


    function checkPath()
    {
        if (auth()->user()) {
            if(auth()->user()->department === 'Manager'){
                return view('mHome');
            }else {
                return view('empHome');
            }
          
        } else {
            return view('login');
        }
    }

    function login()
    {
        return view('login');
    }

    function registration()
    {
        return view('registor');
    }


    function loginPost(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }
        // $request->validate([
        //     'email' => 'required' ,
        //     'password' => 'required'
        // ]);
        // if($validate->errors()){

        // }

        $credentail = $request->only('email', 'password');


        if (Auth::attempt($credentail)) {
            return response()->json(['success' => 'Login success',  'redirect' => route('home')]);
        }

        return response()->json(['error' => "Email or Password Wrong"]);
        // if(Auth::attempt($credentail)){
        //    return redirect()->intended(route('home'))->with("success" , "Login not valid");;
        // }

        // return redirect(route('login'))->with("error" , "Login not valid");
    }

    function registrationPost(Request $request)
    {

        $uuid = Str::uuid();
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'position' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'img_user' => 'required|file|mimes:jpeg,png,pdf,doc,docx',
        ]);

        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }


        // Store the file in a public directory
        $file = $request->file('img_user'); // Retrieve the uploaded file from the request
        $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename
        
        $destination_path = "public/uploads/userImgs/";
        // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
        $path = $file->storeAs($destination_path , $filename);
      

        $data['name'] = $request->fullname;
        $data['department'] = $request->position;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['userImg_url'] =  $filename;
        // // dd($data);
        $user = DB::table('users')->insert($data);

        if (!$user) {
            // return redirect(route('/registration'))->with("error" , "Can't create value");
            return response()->json(['error' => "Create user fail"]);
        }
        return response()->json(['success' => 'Create user success',  'redirect' => route('login')]);
        // //  return redirect(route('login'))->with("success" , "Login not valid");
        // //  return redirect()->intended(route('/login'))->with("success" , "create success");

    }



    function logout()
    {
        $this->session->flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
