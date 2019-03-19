<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Doctor;
use Validator;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recommended(Request $request){
       
         // defualt it to 5 per page max
         $limit = ($request->limit) ? $request->limit : 5;
         $data = Doctor::limit($limit)->get();
         return response()->json(['status'=> 200, 'msg'=> 'ok', 'data'=>$data] , 200);
        
    }
    public function index(Request $request, $q)
    {
        $query = $q;
        // defualt it to 5 per page max
        $limit = ($request->limit) ? $request->limit : 5;

        // search
        $doctors = Doctor::where('name', 'LIKE', "%$query%")->Paginate($limit)->toArray();

        // customize pagination
        $pagination = $doctors;
        unset($pagination['data']);

        // return the output
        $data = array_merge(['doctors' => $doctors['data']], $pagination);
        return response()->json(['status'=> 200, 'msg'=> 'ok', 'data'=>$data] , 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // init signup rules
        $rules = [
            'name' => 'required|min:3',
            'username' => 'required|min:3|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ];
        
        // validate these rules
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->errors(),'data'=>null], 401);
        }

        // create a new user object with the data sent
        $doctor = Doctor::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // creating login token
        $token = $doctor->createToken('2heal')->accessToken;

        // init the output data
        $data = [
            'user' => $doctor,
            'token' => $token
        ];

        return response()->json(['status' => 200, 'msg' => 'new user registered','data'=>$data], 200);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         // get authanticated user
         $doctor = auth()->user();
         return response()->json(['status' => 200, 'msg' => 'user authanticated!', 'data' => ["user" => $doctor]], 200);   
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // find user
        $doctor = User::find($id);
        
        if ($doctor) {
            // update user
            $doctor->name = $request->input('name');
            $doctor->username = $request->input('username');
            $doctor->password = $request->input('password');
            
            if ($doctor->save()) {
                return response()->json(['status' => 200, 'msg' => 'user updated!', 'data' => $doctor], 200);        
            } else {
                return response()->json(['status' => 500, 'msg' => "can't update user!", 'data' => $doctor], 500);        
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "user not found", 'data' => $doctor], 404);        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // find user
         $doctor = User::find($id);
        
         if ($doctor) {
             // destroy user
             if ($doctor->delete()) {
                 return response()->json(['status' => 200, 'msg' => 'user deleted!', 'data' => $doctor], 200);        
             } else {
                 return response()->json(['status' => 500, 'msg' => "can't delete user", 'data' => $doctor], 500);        
             }
         } else {
             return response()->json(['status' => 404, 'msg' => "user not found", 'data' => $doctor], 404);        
         }
         
    }
}
