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
use Modules\Academic\Entities\Department;
use Modules\Academic\Repositories\DepartmentRepository;

class DepartmentController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new DepartmentRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Departments");

        $this->repository->initDatatable(new Department());

        $this->repository->setColumns("id", "dept_name", "dept_code", "faculty", "dept_status", "created_at")
            ->setColumnLabel("dept_code", "Code")
            ->setColumnLabel("dept_name", "Department")
            ->setColumnLabel("dept_status", "Status")
            ->setColumnDisplay("dept_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("faculty", array($this->repository, 'display_faculty_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("dept_name", "text")
            ->setColumnFilterMethod("dept_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnFilterMethod("faculty", "select", URL::to("/academic/faculty/search_data"))

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false)

            ->setColumnDBField("faculty", "faculty_id")
            ->setColumnFKeyField("faculty", "faculty_id")
            ->setColumnRelation("faculty", "faculty", "faculty_name");

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->setTableTitle("Departments | Trashed")
                ->enableViewData("list", "restore", "export")
                ->disableViewData("view", "edit", "delete");
        }
        else
        {
            $query = $this->repository->model::query();

            $this->repository->setTableTitle("Departments")
                ->enableViewData("trashList", "trash", "export");
        }

        $query = $query->with(["faculty"]);

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
        $model = new Department();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        $urls = [];
        $urls["listUrl"]=URL::to("/academic/department");

        $this->repository->setPageUrls($urls);

        return view('academic::department.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new Department();

        $model = $this->repository->getValidatedData($model, [
            "faculty_id" => "required|exists:faculties,faculty_id",
            "dept_name" => "required|min:3",
            "color_code" => "required"
        ], [], ["faculty_id" => "Faculty", "dept_name" => "Department name"]);

        if($this->repository->isValidData)
        {
            //set dept_status as 0 when inserting the record
            $model->dept_status = 1;
            $model->dept_code = $this->repository->generateDeptCode();

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
        $model = Department::find($id);

        if($model)
        {
            $record = $model->toArray();

            $urls = [];
            $urls["addUrl"]=URL::to("/academic/department/create");
            $urls["listUrl"]=URL::to("/academic/department");

            $this->repository->setPageUrls($urls);

            return view('academic::department.view', compact('record'));
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
        $model = Department::with(["faculty"])->find($id);

        if($model)
        {
            $record = $model->toArray();
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["addUrl"]=URL::to("/academic/department/create");
            $urls["listUrl"]=URL::to("/academic/department");

            $this->repository->setPageUrls($urls);

            return view('academic::department.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = Department::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "faculty_id" => "required|exists:faculties,faculty_id",
                "dept_name" => "required|min:3",
                "color_code" => "required"
            ], [], ["faculty_id" => "Faculty", "dept_name" => "Department name", "color_code" => "Colour Code"]);

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
        $model = Department::find($id);

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
        $model = Department::withTrashed()->find($id);

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

            $query = Department::query()
                ->select("dept_id", "dept_name")
                ->where("dept_status", "=", "1")
                ->orderBy("dept_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("dept_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("dept_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
