<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\Courses;
use Modules\Slo\Entities\Groupes;
use Modules\Slo\Entities\Batch;
use Carbon\Carbon;
use DB;
class GroupesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = DB::table('groupes')
        ->join('courses', 'courses.course_id', '=', 'groupes.CourseID')
        ->join('batches', 'batches.batch_id', '=', 'groupes.BatchID')
        ->select('groupes.*', 'courses.course_name', 'batches.batch_name')
        ->where("groupes.deleted_at" , "=" , null)->get();
        return view('slo::groups.index')->with("list",$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $courses = Courses::all();
        return view('slo::groups.create')->with("courses",$courses);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->gId !=""){
            $group = Groupes::find($request->gId);
        }else{
            $group = new Groupes;
        }
        $group->GroupName = $request->gName;
        $group->BatchID = $request->batch_id;
        $group->CourseID = $request->course_id;
        $group->semester = $request->semester;
        $group->year = $request->year;
        $group->type = $request->type;
        if($group->save()){
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
    public function show($id)
    {
        $courses = Courses::all();
        $group = Groupes::find($id);
        $batch = Batch::where('course_id', '=', $group->CourseID)->get();
        return view('slo::groups.create')->with(array("courses"=>$courses, "group"=>$group,'batches'=>$batch));
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
    public function loadBatches($id)
    {
        
        $batches = DB::table('batches')
        ->select('batches.*')
        ->where('course_id' , '=' , $id)->get();
         return view('slo::select.batch2')->with("batches",$batches);
    }
    public function trashList()
    {
        $list = DB::table('groupes')
        ->join('courses', 'courses.course_id', '=', 'groupes.CourseID')
        ->join('batches', 'batches.batch_id', '=', 'groupes.BatchID')
        ->select('groupes.*', 'courses.course_name', 'batches.batch_name')
        ->where("groupes.deleted_at" , "!=" , null)->get();
        return view('slo::groups.trash')->with("list",$list);
    }
    public function trash(Request $request)
    {
        $group = Groupes::find($request->gid);
        if($group->deleted_at != null){
            $group->deleted_at = null;
        }else{
            $group->deleted_at = Carbon::now();
        }
        if($group->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
        
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
