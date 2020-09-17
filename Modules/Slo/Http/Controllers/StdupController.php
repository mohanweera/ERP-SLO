<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\Student;
use Modules\Slo\Entities\Uploadc;
use Modules\Slo\Entities\StudentUp;
use Carbon\Carbon;
use DB;
class StdupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

            return view('slo::stuph.index');
        
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $ctg = Uploadc::all();
        $count = Student::where('range_id' , '=', $request->stId)->get()->count();
        if($count == 0){
            return view('slo::stuph.create')->with(array('count'=>$count));
        }else{
            $std = Student::where('range_id' , '=', $request->stId)->get();
            return view('slo::stuph.create')->with(array('count'=>$count,'data'=> $std[0],'ctg'=>$ctg));
        }
        
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(!is_dir('students/'.$request->range_id . '/' . $request->cid)){
            mkdir('students/'.$request->range_id . '/' . $request->cid);
        }

        $filename  = $_FILES['file']['name'];
        $location = 'students/'.$request->range_id . '/' . $request->cid . '/' . $filename;
        $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        $valid_extensions = array("jpg","jpeg");
        if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
            return response()->json(array('msg'=> "File extensions not valid<br/>Only JPG and JPEG",'act'=>2), 200);
        }else{
            move_uploaded_file($_FILES['file']['tmp_name'],$location);
            $ctg = Uploadc::where('cat_code','=', $request->cid)->get();
            $add = new StudentUp;
            $add->file     = $location;
            $add->student  = $request->std_id;
            $add->category = $ctg[0]->upload_cat_id;
            $add->updated_on  = Carbon::now();
            $add->save();
            
            return response()->json(array('msg'=> "Uploaded Successfully",'act'=>1), 200);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show()
    {
        return view('slo::stuph.show');
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
