<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\SystemAccessIpRestriction;
use Modules\Admin\Repositories\SystemAccessIpRestrictionRepository;

class SystemAccessIpRestrictionController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new SystemAccessIpRestrictionRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("System Access IP Addresses");

        $this->repository->initDatatable(new SystemAccessIpRestriction());

        $this->repository->setColumns("id", "ip_location", "ip_address", "description", "access_status", "created_at")
            ->setColumnLabel("ip_location", "IP Location")
            ->setColumnLabel("ip_address", "IP Address")
            ->setColumnLabel("access_status", "Access Status")
            ->setColumnDisplay("access_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))
            ->setColumnFilterMethod("access_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnSearchability("created_at", false);

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->setTableTitle("System Access IP Addresses | Trashed")
                ->enableViewData("list", "restore", "export")
                ->disableViewData("view", "edit", "delete");
        }
        else
        {
            $query = $this->repository->model::query();

            $this->repository->setTableTitle("System Access IP Addresses")
                ->enableViewData("trashList", "trash", "export");
        }

        return $this->repository->render("admin::layouts.master")->index($query);
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
        $this->repository->setPageTitle("System Access IP Addresses | Add New");

        $model = new SystemAccessIpRestriction();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        $urls = [];
        $urls["listUrl"]=URL::to("/admin/system_access_ip_restriction");

        $this->repository->setPageUrls($urls);

        return view('admin::system_access_ip_restriction.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new SystemAccessIpRestriction();

        $model = $this->repository->getValidatedData($model, [
            "ip_location" => "required|min:3",
            "ip_address" => "required|min:3",
            "description" => "required|min:3",
            "access_status" => "required|digits:1",
        ]);

        if($this->repository->isValidData)
        {
            $model->ip_address_key = $this->repository->generateIPHash($model->ip_address);
            $response = $this->repository->saveModel($model);
        }
        else
        {
            $response = $model;
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $this->repository->setPageTitle("System Access IP Addresses | Edit");

        $model = SystemAccessIpRestriction::find($id);

        if($model)
        {
            $record = $model->toArray();
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/system_access_ip_restriction/create");
            $urls["listUrl"]=URL::to("/admin/system_access_ip_restriction");

            $this->repository->setPageUrls($urls);

            return view('admin::system_access_ip_restriction.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = SystemAccessIpRestriction::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "ip_location" => "required|min:3",
                "ip_address" => "required|min:3",
                "description" => "required|min:3",
                "access_status" => "required|digits:1",
            ]);

            if($this->repository->isValidData)
            {
                $model->ip_address_key = $this->repository->generateIPHash($model->ip_address);
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
        $model = SystemAccessIpRestriction::find($id);

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
        $model = SystemAccessIpRestriction::withTrashed()->find($id);

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
}
