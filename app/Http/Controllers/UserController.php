<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
use Validator;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $q)
    {
        // init
        $query = $q;
        // defualt it to 5 per page max
        $limit = ($request->limit) ? $request->limit : 5;

        // search
        $users = User::where('name', 'LIKE', "%$query%")->Paginate($limit)->toArray();

        // customize pagination
        $pagination = $users;
        unset($pagination['data']);

        // return the output
        $data = array_merge(['users' => $users['data']], $pagination);
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
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // creating login token
        $token = $user->createToken('2heal')->accessToken;

        // init the output data
        $data = [
            'user' => $user,
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
    public function show(Request $request)
    {
        // get authanticated user
        $user = auth()->user();
        return response()->json(['status' => 200, 'msg' => 'user authanticated!', 'data' => ["user" => $user]], 200);        
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
        $user = User::find($id);
        
        if ($user) {
            // update user
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->password = $request->input('password');
            
            if ($user->save()) {
                return response()->json(['status' => 200, 'msg' => 'user updated!', 'data' => $user], 200);        
            } else {
                return response()->json(['status' => 500, 'msg' => "can't update user!", 'data' => $user], 500);        
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "user not found", 'data' => $user], 404);        
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
        $user = User::find($id);
        
        if ($user) {
            // destroy user
            if ($user->delete()) {
                return response()->json(['status' => 200, 'msg' => 'user deleted!', 'data' => $user], 200);        
            } else {
                return response()->json(['status' => 500, 'msg' => "can't delete user", 'data' => $user], 500);        
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "user not found", 'data' => $user], 404);        
        }
        
    }
}
