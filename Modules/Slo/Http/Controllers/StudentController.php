<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\BatchTypes;
use Modules\Slo\Entities\Batch;
use Modules\Slo\Entities\Courses;
use Modules\Slo\Entities\Faculty;
use Modules\Slo\Entities\Departments;
use Modules\Slo\Entities\Student;
use Modules\Slo\Entities\CourseStudent;
use Modules\Slo\Entities\Country;
use Modules\Slo\Entities\Idrange;
use Modules\Slo\Entities\Slqfstr;
use Modules\Slo\Entities\Inputf;
use DB;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        
        return view('slo::student.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $faculty = Faculty::all();
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
        $dep = Departments::find($request->dept_id);
        $slqf_s = Slqfstr::find(1);
        $slqf = $slqf_s->slqf_code;
        return response()->json(array('dept_code'=> $dep->dept_code,'slqf'=>$slqf), 200);
    }
    public function getMiddleId(Request  $request)
    {
        //echo $id; return;
        $batch = Batch::find($request->batch_id);
        $batchType = BatchTypes::find($batch->batch_type);
        $course = Courses::find($batch->course_id);
        $dep = Departments::find($course->dept_id);
        return response()->json(array('dept_code'=> $dep->dept_code,'batchType_code'=>$batchType->batch_type,'batch_code'=> $batch->batch_code), 200);
    }
    public function getStdSeriel(Request  $request)
    {
        //echo $id; return;
        $course = Courses::find($request->course_id);
        $stdSerial = Idrange::all()->where('hold' , '=' , 0)->where('course_id' , '=' , $course->course_id);
        $serial = 0;
        foreach($stdSerial as $stdSerial){
        $serial = $stdSerial->last_id ;
        }
        if($serial > 0){
            if($stdSerial->last_id == $stdSerial->end){
                return response()->json(array('msg'=>'1','last_id'=> 0));
            }else{
                return response()->json(array('msg'=>'0','last_id'=>$serial,'idrange'=>$stdSerial->id));
            }
        }else{
            return response()->json(array('msg'=>'2','last_id'=> 0));
        }
        
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
         return view('slo::select.batch')->with("batches",$batches);
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
        $std->gen_id = $request->stdReg;
        $std->cgsid = $request->idrange;
        $std->range_id = $request->std3;
        if($std->save()){
            $last = Student::all()->max('student_id');
            $stdc = new CourseStudent;
            $stdc->course_id = $request->course_id;
            $stdc->student_id = $last;
            
            if($stdc->save()){
                $idr = Idrange::find($request->idrange);
                $idr->last_id = $request->std3 + 1;
                $idr->save();
                $stdb = new Batch;
                $stdb->batch_id = $request->batch_id;
                $stdb->student_id = $last;
                $stdb->save();
                if(!is_dir('students')){
                    mkdir('students');
                }
                mkdir('students/'. $request->std3);
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
         $data = DB::table('students')
        ->join('course_student', 'course_student.student_id', '=', 'students.student_id')
        ->join('batch_student', 'batch_student.student_id', '=', 'students.student_id')
        ->join('courses', 'courses.course_id', '=', 'course_student.course_id')
        ->join('batches', 'batches.batch_id', '=', 'batch_student.batch_id')
        ->join('departments', 'departments.dept_id', '=', 'courses.dept_id')
        ->join('faculties', 'faculties.faculty_id', '=', 'departments.faculty_id')
        ->select('students.*', 'batches.batch_name', 'courses.course_name', 'departments.dept_name', 'faculties.faculty_name','courses.course_id')
        ->get();
        
        //echo $data; return;
        $cid = $data[0]->course_id;
        $genaral = Inputf::where('course_id' , '=' , 0)->get();
        $special = Inputf::where('course_id' , '=' , $cid)->get();
        return view('slo::student.update')->with(array("Student"=> $data[0],'genaral'=>$genaral,'special'=>$special));
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
