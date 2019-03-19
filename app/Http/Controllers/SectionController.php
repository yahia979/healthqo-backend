<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Section;
use function GuzzleHttp\json_decode;
use Validator;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->limit) ? $request->limit : 15;
        
        //get all sections
        $sections = Section::paginate($limit)->toArray();

        // customize pagination
        $pagination = $sections;
        unset($pagination['data']);

        // return the output
        $data = array_merge(['sections' => $sections['data']], $pagination);
        return response()->json(['status' => 200, 'msg' => 'sections fetched', 'data' => $data], 200);
    }

   
}
