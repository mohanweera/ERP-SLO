<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\batchTypes;
use Modules\Slo\Entities\batch;
use Modules\Slo\Entities\courses;
use Modules\Slo\Entities\faculty;
use Modules\Slo\Entities\Departments;
use Modules\Slo\Entities\Student;
use Modules\Slo\Entities\CourseStudent;
use Modules\Slo\Entities\Country;
use DB;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('slo::student.create');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $faculty = faculty::all();
        $country = Country::orderBy('country_name')->get();
        return view('slo::student.create')->with(array("faculty"=> $faculty, "country"=>$country));
    }
    public function loadDepartments($id)
    {
        
        $dep = DB::table('departments')
        ->select('departments.*')
        ->where('faculty_id' , '=' , $id)->get();
         return view('slo::select/departments')->with("dep",$dep);
    }
    public function getDepartmentCode(Request  $request)
    {
        //echo $id; return;
        $dep = departments::find($request->dept_id);
        return response()->json(array('dept_code'=> $dep->dept_code), 200);
    }
    public function loadCourses($id)
    {
        
        $courses = DB::table('courses')
        ->select('courses.*')
        ->where('dept_id' , '=' , $id)->get();
         return view('slo::select/courses')->with("courses",$courses);
    }
    public function loadBatches($id)
    {
        
        $batches = DB::table('batches')
        ->select('batches.*')
        ->where('course_id' , '=' , $id)->get();
         return view('slo::select/batch')->with("batches",$batches);
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function addNewStudent(Request $request)
    {
        $std = new Student;
        $std->std_title = $request->std_title;
        $std->full_name = $request->full_name;
        $std->nic_passport = $request->nicpass;
        $std->name_initials = $request->name_initials;
        $std->tel_mobile1	 = $request->tel_mobile1;
        $std->reg_date = $request->reg_date;
        $std->std_title = $request->std_title;
        $std->gender = $request->gender;
        $std->gen_id = 0011100110;
        $std->cgsid = 1;
        if($std->save()){
            $last = Student::all()->max('student_id');
            $stdc = new CourseStudent;
            $stdc->course_id = $request->course_id;
            $stdc->student_id = $last;
            if($stdc->save()){
                return response()->json(array('msg'=> 1), 200);
            }else{
                return response()->json(array('msg'=> 2), 200);
            }
            
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
        return view('slo::show');
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
    public function destroy($id)
    {
        //
    }
}
