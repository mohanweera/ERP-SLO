<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\batchTypes;
use Modules\Slo\Entities\batch;
use Modules\Slo\Entities\courses;
use Carbon\Carbon;
use DB;
class BatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = DB::table('batches')
        ->join('courses', 'courses.course_id', '=', 'batches.course_id')
        ->select('batches.*', 'courses.course_name')
        ->where("deleted_at" , "=" , null)->get();
        return view('slo::batches.index')->with("list",$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $courses = courses::all();
        $batchTypes = batchTypes::all();
        return view('slo::batches.create')->with(array("courses"=>$courses,"batchTypes"=>$batchTypes));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->bId !=""){
            $data = batch::find($request->bId);
        }else{
            $data = new batch;
        }

        $data->course_id = $request->course_id;
        $data->batch_type = $request->batch_type;
        $data->batch_name = $request->batch_name;
        $data->max_student = $request->max_student;
        $data->batch_start_date = $request->batch_start_date;
        $data->batch_end_date = $request->batch_end_date;
        $data->batch_code = $request->batch_code;
        if($data->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
     public function loadBatchCode(Request $request)
    {
       
        $batchTypes = batchTypes::find($request->batch_type);
        return response()->json(array('batch_code'=> $batchTypes->batch_type), 200);
        
    }
    public function show($id)
    {
        $data = batch::find($id);
        $courses = courses::all();
        $batchTypes = batchTypes::all();
        return view('slo::batches.create')->with(array("batchData"=>$data,"courses"=>$courses,"batchTypes"=>$batchTypes));
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('slo::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function trashList()
    {
        $list = DB::table('batches')
        ->join('courses', 'courses.course_id', '=', 'batches.course_id')
        ->select('batches.*', 'courses.course_name')
        ->where("deleted_at" , "!=" , null)->get();
        return view('slo::batches.trash')->with("list",$list);
    }
    public function trash(Request $request)
    {
        $batch = batch::find($request->batch_id);
        if($batch->deleted_at != null){
            $batch->deleted_at = null;
        }else{
            $batch->deleted_at = Carbon::now();
        }
        if($batch->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
        
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
