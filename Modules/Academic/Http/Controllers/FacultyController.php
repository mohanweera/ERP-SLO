<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Modules\Academic\Entities\Faculty;
use Modules\Academic\Repositories\FacultyRepository;

class FacultyController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new FacultyRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Faculties");

        $this->repository->initDatatable(new Faculty());

        $statusParams = [];
        $statusParams[]=[["id" =>"1", "name" =>"Enabled", "label" => "success"], ["id" =>"0", "name" =>"Disabled", "label" => "danger"]];

        $this->repository->setColumns("id", "faculty_name", "faculty_code", "faculty_status", "created_at")
            ->setColumnLabel("faculty_code", "Code")
            ->setColumnLabel("faculty_status", "Status")
            ->setColumnDisplay("faculty_status", array($this->repository, 'display_status_as'), $statusParams)
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("faculty_name", "text")
            ->setColumnFilterMethod("faculty_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false);

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->setTableTitle("Faculties | Trashed")
                ->enableViewData("list", "restore", "export")
                ->disableViewData("view", "edit", "delete");
        }
        else
        {
            $query = $this->repository->model::query();

            $this->repository->setTableTitle("Faculties")
                ->enableViewData("trashList", "trash", "export");
        }

        return $this->repository->render("academic::layouts.master")->index($query);
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function trash()
    {
        $this->trash = true;
        return $this->index();
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create()
    {
        $model = new Faculty();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        $urls = [];
        $urls["listUrl"]=URL::to("/academic/faculty");

        $this->repository->setPageUrls($urls);

        return view('academic::faculty.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new Faculty();

        $model = $this->repository->getValidatedData($model, [
            "faculty_name" => "required|min:3",
            "color_code" => "required"
        ]);

        if($this->repository->isValidData)
        {
            //set faculty_status as 0 when inserting the record
            $model->faculty_status = 1;
            $model->faculty_code = $this->repository->generateFacultyCode();

            $response = $this->repository->saveModel($model);
        }
        else
        {
            $response = $model;
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $model = Faculty::find($id);

        if($model)
        {
            $record = $model->toArray();

            $urls = [];
            $urls["addUrl"]=URL::to("/academic/faculty/create");
            $urls["listUrl"]=URL::to("/academic/faculty");

            $this->repository->setPageUrls($urls);

            return view('academic::faculty.view', compact('record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $model = Faculty::find($id);

        //dd(get_class($model));

        if($model)
        {
            $record = $model->toArray();
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["addUrl"]=URL::to("/academic/faculty/create");
            $urls["listUrl"]=URL::to("/academic/faculty");

            $this->repository->setPageUrls($urls);

            return view('academic::faculty.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $model = Faculty::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "faculty_name" => "required|min:3",
                "color_code" => "required"
            ]);

            if($this->repository->isValidData)
            {
                $response = $this->repository->saveModel($model);
            }
            else
            {
                $response = $model;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details saving was failed. Requested record does not exist.";

            $response["notify"]=$notify;
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Move the record to trash
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function delete($id)
    {
        $model = Faculty::find($id);

        if($model)
        {
            if($model->delete())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully moved the record to trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details moving to trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details moving to trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Restore record
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function restore($id)
    {
        $model = Faculty::withTrashed()->find($id);

        if($model)
        {
            if($model->restore())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully restored the record from trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details restoring from trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details restoring from trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Search records
     * @param Request $request
     * @return JsonResponse
     */
    public function searchData(Request $request)
    {
        if($request->expectsJson())
        {
            $searchText = $request->post("query");
            $idNot = $request->post("idNot");

            $query = Faculty::query()
                ->select("faculty_id", "faculty_name")
                ->where("faculty_status", "=", "1")
                ->orderBy("faculty_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("faculty_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("faculty_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
