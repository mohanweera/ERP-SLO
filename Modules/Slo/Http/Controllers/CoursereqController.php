<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\Courses;
use Modules\Slo\Entities\Groupes;
use Modules\Slo\Entities\Batch;
use Modules\Slo\Entities\inputf;
use Carbon\Carbon;
use DB;

class CoursereqController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('slo::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $courses = Courses::all();
        return view('slo::coursereq.create')->with(array("courses"=>$courses , 'count'=>0));
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        for($x=1; $x <= $request->total_des; $x++){
            $fname = "new_" . $x;
            if($request->$fname !=""){
                $input = new inputf;
                $input->fname = $request->$fname;
                $ftype = "fieldType_" . $x;
                $input->fid = $request->$ftype;
                $input->course_id = $request->course_id;
                $inname = str_replace(' ', '', $request->$fname);
                $input->inputname = $inname;
                $input->save();
            }
        }
        return response()->json(array('msg'=> 'Successfully','act'=>1), 200);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $courses = Courses::all();
        $count = inputf::where('course_id' , '=' , $id)->get()->count();
        $data = inputf::where("course_id" , "=" , $id)->get();
        if($count == 0){
            return view('slo::coursereq.create')->with(array("courses"=>$courses , 'count'=>$count,'cid'=> $id));
        }else{
            return view('slo::coursereq.create')->with(array("courses"=>$courses , "data"=>$data,'count'=>$count,'cid'=>$id));
        }
        
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $inputf = inputf::find($request->fid);
        if($inputf->delete()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
    }
}
