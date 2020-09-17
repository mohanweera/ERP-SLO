<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\Uploadc;
use Carbon\Carbon;
use DB;

class UploadCController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $list = Uploadc::where("deleted_at" , "=" , null)->get();
        return view('slo::uct.index')->with("list",$list);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('slo::uct.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($request->ucId > 0){
            $upct = Uploadc::find($request->ucId);
            $upct->category_name = $request->c_name;
            $upct->cat_code = $request->c_code;
            $upct->description = $request->description;
            $upct->save();
            return response()->json(array('msg'=> "Upload Category Updated Successfully","act" => 1), 200);
        }else{
        $check = Uploadc::where('cat_code' , '=', $request->c_code)->get()->count();
        if($check == 0){
            $upct = new Uploadc;
            $upct->category_name = $request->c_name;
            $upct->cat_code = $request->c_code;
            $upct->description = $request->description;
            $upct->save();
            return response()->json(array('msg'=> "Upload Category Add Successfully","act" => 1), 200);
        }else{
            return response()->json(array('msg'=> "Upload Category Adding error<br/>Category Code already exits","act" => 2), 200);
        }
        }
    }
    public function trash(Request $request)
    {
        $upct = Uploadc::find($request->upCtId);
        if($upct->deleted_at != null){
            $upct->deleted_at = null;
        }else{
            $upct->deleted_at = Carbon::now();
        }
        if($upct->save()){
            return response()->json(array('msg'=> 1), 200);
        }else{
            return response()->json(array('msg'=> 2), 200);
        }
        
    }
    public function trashList()
    {
        $list = Uploadc::where("deleted_at" , "!=" , null)->get();
        return view('slo::uct.trash')->with("list",$list);
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Uploadc::find($id);
        return view('slo::uct.create')->with('data', $data);
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
