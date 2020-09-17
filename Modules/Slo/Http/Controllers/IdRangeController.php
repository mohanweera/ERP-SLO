<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\courses;
use Modules\Slo\Entities\idrange;
use Carbon\Carbon;
use DB;

class IdRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = DB::table('id_ranges')
        ->join('courses', 'courses.course_id', '=', 'id_ranges.course_id')
        ->leftJoin('students','students.cgsid','=','id_ranges.id')
        ->select('id_ranges.*', 'courses.course_name' , 'students.cgsid')
        ->where('id_ranges.deleted_at' , '=' , null)
        ->get();

        
        return view('slo::idrange.index')->with("list",$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        
        $courses = DB::table('courses')
        ->select(
            'courses.*'
        )
        ->leftJoin('id_ranges','id_ranges.course_id','=','courses.course_id')
        ->whereNull('id_ranges.course_id')->where('id_ranges.hold' , '=' , 1)
        ->get();
        $exId = idrange::where('hold' , '=' , 1)->get();
        $start = idrange::all()->max('end');
        return view('slo::idrange.create')->with(array("courses"=>$courses,"start"=>$start + 1 , 'ids'=>$exId));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->ids !=""){
        $old = idrange::find($request->ids);
        $old->end = $request->start2 - 1;
        $old->save();
        $start = $request->start2;
        $end = $request->end2;
        }else{
        $start = $request->start;
        $end = $request->end;
        }
        if($request->iId !=""){
            $data = idrange::find($request->iId);
        }else{
            $data = new idrange;
        }
        $data->description=$request->description;
        $data->start=$start;
        $data->end=$end;
        $data->course_id=$request->course_id;
        $data->last_id=$request->start;
        if($data->save()){
            $start = idrange::all()->max('end');
            return response()->json(array('msg'=> 1,'start'=>$start + 1), 200);
        }else{
            return response()->json(array('msg'=> 2,'start'=>''), 200);
        }
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $courses = courses::all();
        $data = idrange::find($id);
        return view('slo::idRange.create')->with(array("data"=>$data,"courses"=>$courses ,"start"=>$data->start));
        $start = idrange::all()->max('end');
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
        $list = DB::table('id_ranges')
        ->join('courses', 'courses.course_id', '=', 'id_ranges.course_id')
        ->leftJoin('students','students.cgsid','=','id_ranges.id')
        ->select('id_ranges.*', 'courses.course_name' , 'students.cgsid')
        ->where('id_ranges.deleted_at' , '!=' , null)
        ->get();
        return view('slo::idRange.trash')->with("list",$list);
    }
    public function hold(Request $request)
    {
        $idrange = idrange::find($request->idRange_id);
        
        $idrange->hold = 1;
        
        if($idrange->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
        
    }
    public function trash(Request $request)
    {
        $idrange = idrange::find($request->idRange_id);
        if($idrange->deleted_at != null){
            $idrange->deleted_at = null;
        }else{
            $idrange->deleted_at = Carbon::now();
        }
        if($idrange->save()){
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
