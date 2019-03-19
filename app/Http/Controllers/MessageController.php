<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Message;
use App\Http\Resources\Message as MessageResource;
use function GuzzleHttp\json_decode;
use Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->limit) ? $request->limit : 15;

        $messages = Message::paginate($limit)->toArray();
        $pagination = $messages;
        unset($pagination['data']);

        // return the output
        $data = array_merge(['messages' => $messages['data']], $pagination);
        return response()->json(['status' => 200, 'msg' => 'messages fetched', 'data' => $data], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'id' => 'required',
            'content' => 'required',
        ];

        // validate these rules
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->errors(),'data'=>null], 401);
        }
        
        // create the post
        $messages = Message::create([
            'id' => $request->title,
            'content' => $request->body,
        ]);
        
        // try to save it
        if ($messages->save()) {
            return response()->json(['status' => 201, 'msg' => 'message sended!','data'=>$messages], 201);
        } else {
            return response()->json(['status' => 500, 'msg' => "can't send the message",'data'=>$messages], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::find($id);

        if ($message) {
            return response()->json(['status' => 200, 'msg' => 'message found!','data'=>$message], 200);
        } else {
            return response()->json(['status' => 404, 'msg' => "message not found!",'data'=>$message], 404);
        }
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
        $messages =Message::find($id);
        
        if($messages){
            $messages->id = $request->input('id');
            $messages->content = $request->input('content');
            
            if ($messages->save()) {
                return response()->json(['status' => 200, 'msg' => 'message updated!','data'=>$messages], 200);
            } else {
                return response()->json(['status' => 500, 'msg' => "message can't be updated!",'data'=>$messages], 500);
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "message not found!",'data'=>$messages], 404);
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
        $messages = Message::find($id);
        
        if ($messages) {
            if ($messages->delete()) {
                return response()->json(['status' => 200, 'msg' => 'message deleted!','data'=>$messages], 200);
            } else {
                return response()->json(['status' => 500, 'msg' => "message can't be deleted!",'data'=>$messages], 500);
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "message not found!",'data'=>$messages], 404);
        }
        
    }
    
}
