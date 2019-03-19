<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;

use App\Post;
use App\Http\Resources\Post as PostResource;
use function GuzzleHttp\json_decode;
use Validator;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // init
        $limit = ($request->limit) ? $request->limit : 15;
        
        //get all posts
        $posts = Post::paginate($limit)->toArray();

        // customize pagination
        $pagination = $posts;
        unset($pagination['data']);

        // return the output
        $data = array_merge(['posts' => $posts['data']], $pagination);
        return response()->json(['status' => 200, 'msg' => 'posts fetched', 'data' => $data], 200);
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
            
            'body' => 'required',
        ];

        // validate these rules
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->errors(),'data'=>null], 401);
        }
        
        // create the post
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        
        // try to save it
        if ($post->save()) {
            return response()->json(['status' => 201, 'msg' => 'post created!','data'=>$post], 201);
        } else {
            return response()->json(['status' => 500, 'msg' => "can't create the post",'data'=>$post], 500);
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
        //get single post
        $post = Post::find($id);

        if ($post) {
            return response()->json(['status' => 200, 'msg' => 'post found!','data'=>$post], 200);
        } else {
            return response()->json(['status' => 404, 'msg' => "post not found!",'data'=>$post], 404);
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
        $post = Post::find($id);
        
        if($post){
            $post->title = $request->input('title');
            $post->body = $request->input('body');
            
            if ($post->save()) {
                return response()->json(['status' => 200, 'msg' => 'post updated!','data'=>$post], 200);
            } else {
                return response()->json(['status' => 500, 'msg' => "post can't be updated!",'data'=>$post], 500);
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "post not found!",'data'=>$post], 404);
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
        $post = Post::find($id);
        
        if ($post) {
            if ($post->delete()) {
                return response()->json(['status' => 200, 'msg' => 'post deleted!','data'=>$post], 200);
            } else {
                return response()->json(['status' => 500, 'msg' => "post can't be deleted!",'data'=>$post], 500);
            }
        } else {
            return response()->json(['status' => 404, 'msg' => "post not found!",'data'=>$post], 404);
        }
        
    }
}
