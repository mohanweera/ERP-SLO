<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminActivity;
use Modules\Admin\Entities\AdminLoginHistory;
use Modules\Admin\Repositories\AdminActivityRepository;

class AdminActivityController extends Controller
{
    private $repository = null;

    public function __construct()
    {
        $this->repository = new AdminActivityRepository();
    }

    /**
     * Display a listing of the resource.
     * @param $admin_login_history_id
     * @return Response
     */
    public function index($admin_login_history_id)
    {
        $adminLH = AdminLoginHistory::find($admin_login_history_id);

        if($adminLH)
        {
            $admin = Admin::withTrashed()->find($adminLH["admin_id"]);

            if($admin)
            {
                $pageTitle = "Admin : ".$admin["name"]." | System Activities From ".$adminLH["sign_in_at"]." To ".$adminLH["last_activity_at"];
                $tableTitle = $pageTitle;

                $this->repository->setPageTitle($pageTitle);

                $this->repository->initDatatable(new AdminActivity());

                $this->repository->setColumns("id", "event", "activity", "activity_model_name", "activity_at");

                $query = $this->repository->model::query();

                $this->repository->setTableTitle($tableTitle)
                    ->enableViewData("view", "export")
                    ->disableViewData("add", "edit", "trashList", "trash", "delete")
                    ->setUrlLabel("view", "View Activity");

                $query->where("admin_login_history_id", "=", $admin_login_history_id);

                return $this->repository->render("admin::layouts.master")->index($query);
            }
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $this->repository->setPageTitle("Admin Activity | View");

        $model = AdminActivity::find($id);

        if($model)
        {
            $record = $model->toArray();

            $admin = Admin::withTrashed()->find($record["admin_id"]);

            $urls = [];
            $urls["listUrl"]=URL::to("/admin/admin_activity/".$model["admin_login_history_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_activity.view', compact('record', 'admin'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }
}
