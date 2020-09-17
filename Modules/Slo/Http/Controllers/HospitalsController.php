<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\Hospitals;
use Carbon\Carbon;
use DB;

class HospitalsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = Hospitals::where('deleted_at' , '=' , null)->get();
        return view('slo::hospitals.index')->with('list',$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('slo::hospitals.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        
        if($request->hId !=""){
            $Hospitals = Hospitals::find($request->hId);
        }else{
            $Hospitals = new Hospitals;
        }
        $Hospitals->hospital_name = $request->h_name;
        if($Hospitals->save()){
            return response()->json(array('msg'=> 'Hospital Added Successfully','act'=>1), 200);
        }else{
            return response()->json(array('msg'=> 'Hospital Adding Error','act'=>2), 200);
        }
    }
    public function trashList()
    {
        $list = Hospitals::where('deleted_at' , '!=' , null)->get();
        return view('slo::hospitals.trash')->with('list',$list);
    }
    public function trash(Request $request)
    {
        $hospital = Hospitals::find($request->hospital_id);
        if($hospital->deleted_at != null){
            $hospital->deleted_at = null;
        }else{
            $hospital->deleted_at = Carbon::now();
        }
        if($hospital->save()){
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
        $data = Hospitals::find($id);
        return view('slo::hospitals.create')->with('data',$data);
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
