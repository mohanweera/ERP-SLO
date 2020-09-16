<?php
namespace Modules\Admin\Observers;

use Modules\Admin\Entities\AdminActivity;
use Modules\Admin\Entities\AdminLoginHistory;

class AdminActivityObserver
{
    /**
     * Listen to the created event.
     *
     * @param $model
     * @return void
     */
    public function created($model)
    {
        $newData = $model->toArray();
        $oldData = [];

        $event = "created";
        $activity = "Created a new record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function updated($model)
    {
        //this is a record update
        $newData = $model->getChanges();

        $oldData = [];
        foreach($newData as $key => $value)
        {
            $oldData[$key] = $model->getOriginal($key);
        }

        if(array_key_exists("deleted_at", $newData))
        {
            //this means this event was triggered due to restoration of the record
            //this is update event is not triggered by a user, but automated in laravel
        }
        else
        {
            $event = "updated";
            $activity = "Updated a record.";

            $this->recordActivity($model, $event, $oldData, $newData, $activity);
        }
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function deleted($model)
    {
        //this is a record update
        $newData = [];
        $oldData = [];

        //determine if this is a soft delete or force delete
        if (method_exists($model, "forceDelete"))
        {
            $event = "trashed";
            $activity = "Moved a record to the trash.";
        }
        else
        {
            $event = "deleted";
            $activity = "Permanently deleted a record.";
        }

        if(array_key_exists("deleted_by", $model->getAttributes()))
        {
            if ($model->trashed())
            {
                if(isset(auth("admin")->user()->admin_id))
                {
                    $columns = ["deleted_by" => auth("admin")->user()->admin_id];
                    $query = $model->newQueryWithoutScopes()->where($model->getKeyName(), $model->getKey());

                    $query->update($columns);
                }
            }
        }

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function restored($model)
    {
        //this is a record restore
        $newData = [];
        $oldData = [];

        $event = "restored";
        $activity = "Restored a record.";

        if(array_key_exists("deleted_by", $model->getAttributes()))
        {
            $columns = ["deleted_by" => null];
            $query = $model->newQueryWithoutScopes()->where($model->getKeyName(), $model->getKey());

            $query->update($columns);
        }

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function forceDeleted($model)
    {
        //this is a record update
        $newData = [];
        $oldData = $model->toArray();

        $event = "deleted";
        $activity = "Permanently deleted a record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the creating event.
     *
     * @param $model
     * @return void
     */
    public function creating($model)
    {
        if(in_array("created_by", $model->getFillable()))
        {
            if(isset(auth("admin")->user()->admin_id))
            {
                $model->created_by = auth("admin")->user()->admin_id;
            }
        }
    }

    /**
     * Listen to the updating event.
     *
     * @param $model
     * @return void
     */
    public function updating($model)
    {
        if(in_array("created_by", $model->getFillable()))
        {
            if(isset(auth("admin")->user()->admin_id))
            {
                $model->updated_by = auth("admin")->user()->admin_id;
            }
        }
    }

    /**
     * @param $model
     * @param string $event
     * @param array $oldData
     * @param array $newData
     * @param string $activity
     */
    private function recordActivity($model, $event, $oldData=[], $newData=[], $activity="")
    {
        if(isset(auth("admin")->user()->admin_id))
        {
            $primaryKey = $model->getKeyName();
            $modelName = $className = get_class($model);
            $modelId = $model->$primaryKey;

            $request = request();

            $admin_id = auth("admin")->user()->admin_id;
            $admin_login_history_id = $request->session()->get("admin_login_history_id");

            //set real activity from system if exists
            $currentActivity = request()->session()->get("currentActivity");
            if($currentActivity!="")
            {
                $activity = $currentActivity;
            }

            $adminActModel = new AdminActivity();
            $adminActModel->admin_login_history_id = $admin_login_history_id;
            $adminActModel->admin_id = $admin_id;
            $adminActModel->activity = $activity;
            $adminActModel->event = $event;
            $adminActModel->activity_old_data = $oldData;
            $adminActModel->activity_new_data = $newData;
            $adminActModel->activity_model_name = $modelName;
            $adminActModel->activity_model = $modelId;
            $adminActModel->activity_at = date("Y-m-d H:i:s", time());

            $adminActModel->save();

            //update last activity time
            $data["last_activity_at"]=$adminActModel->activity_at;

            $adminLH = AdminLoginHistory::find($admin_login_history_id);
            $adminLH->last_activity_at = $adminActModel->activity_at;

            $adminLH->save();

            //update login history id cookie with session time

            $sessionTime = config("session.lifetime");
            $sessionTime = intval($sessionTime)*60;

            setcookie("adminLoginHistoryId", $admin_login_history_id, time()+$sessionTime+3600);
            setcookie("adminLastActivityAt", $adminLH->last_activity_at, time()+$sessionTime+3600);
        }
    }
}
