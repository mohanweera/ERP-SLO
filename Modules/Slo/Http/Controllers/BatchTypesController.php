<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\batchTypes;
use Carbon\Carbon;
class BatchTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = batchTypes::where("deleted_at" , "=" , null)->get();
        return view('slo::batchTypes.index')->with("list",$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('slo::batchTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->tId !=""){
            $save = batchTypes::find($request->tId);
        }else{
            $save = new batchTypes;
        }
        
        $save->description = $request->description;
        $save->batch_type = $request->batch_type;
        if($save->save()){
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
        $data = batchTypes::find($id);
        return view('slo::batchTypes.create')->with("batchData",$data);
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
    public function trashList()
    {
        $list = batchTypes::where("deleted_at" , "!=" , null)->get();
        return view('slo::batchTypes.trash')->with("list",$list);
    }
    public function trash(Request $request)
    {
        $batchType = batchTypes::find($request->batchType_id);
        if($batchType->deleted_at != null){
            $batchType->deleted_at= null;
        }else{
            $batchType->deleted_at= Carbon::now();
        }
        if($batchType->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
    }
}
