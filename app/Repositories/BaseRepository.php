<?php
namespace App\Repositories;

use ArrayObject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Traits\Datatable;
use Modules\Admin\Services\Permission;

class BaseRepository
{
    use Datatable;

    private $urls = [];
    private $pageTitle = "";
    public $isValidData = false;

    public function __construct()
    {
        $this->setViewComposer();
    }

    /**
     * Setup view variables
     * @return void
     */
    private function setViewComposer()
    {
        View::composer("*", function ($view){

            if(!isset($view->getData()["pageTitle"]))
            {
                $view->with("pageTitle", $this->pageTitle);
            }

            if(!isset($view->getData()["urls"]))
            {
                $view->with("urls", $this->getPageUrls());
            }
        });
    }

    /**
     * @param $pageTitle
     * @return void
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @param string $accessKey Key variable to access the URL from array
     * @param string $url System URL
     * @return void
     */
    public function setPageUrl($accessKey, $url)
    {
        $this->urls[$accessKey] = $url;
    }

    /**
     * @param array $urls List of URLs with array
     * @return void
     */
    public function setPageUrls($urls)
    {
        if(is_array($urls) && count($urls)>0)
        {
            foreach ($urls as $key => $url)
            {
                $this->urls[$key] = $url;
            }
        }
    }

    /**
     * @return ArrayObject
     */
    private function getPageUrls()
    {
        $urls = array();
        if(count($this->urls)>0)
        {
            $urls = $this->validateUrls($this->urls);
        }

        return $urls;
    }

    /**
     * @param $model
     * @return array
     */
    public function saveModel($model)
    {
        $save = $model->save();

        if($save)
        {
            $notify = array();
            $notify["status"]="success";
            $notify["notify"][]="Successfully saved the details.";

            $response["record"]=$model;
            $response["notify"]=$notify;
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details saving was failed";

            $response["notify"]=$notify;
        }

        return $response;
    }

    /**
     * @param $model
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return mixed
     */
    public function getValidatedData($model, $rules, $messages=[], $customAttributes=[])
    {
        $postData = Validator::make(request()->all(), $rules, $messages, $customAttributes);

        if ($postData->fails())
        {
            $this->isValidData = false;
            return $this->getValidationErrors($postData->errors());
        }
        else
        {
            $this->isValidData = true;
            $data = $postData->validated();
        }

        foreach ($data as $key => $value)
        {
            $model->$key = $value;
        }

        return $model;
    }

    /**
     * @param $errors
     * @return mixed
     */
    public function getValidationErrors($errors)
    {
        $errors = json_decode(json_encode($errors), true);

        $validationResponse["status"]="failed";

        foreach($errors as $key => $error)
        {
            if(is_array($error) && count($error)>0)
            {
                foreach ($error as $err)
                {
                    $validationResponse["notify"][]=$err;
                }
            }
            else
            {
                $validationResponse["notify"][]=$error;
            }
        }

        $response["notify"]=$validationResponse;

        return $response;
    }

    /**
     * @param array $response
     * @param bool $redirect
     * @param string $url
     * @return mixed
     */
    public function handleResponse($response, $redirect=true, $url="")
    {
        if(isset($response["status"]) && isset($response["notify"]))
        {
            $status = $response["status"];
            $notify = $response["notify"];

            $response["notify"]["status"]=$status;
            $response["notify"]["notify"]=$notify;
        }

        if(request()->expectsJson())
        {
            return response()->json($response, 201);
        }
        else
        {
            if($redirect)
            {
                if($url != "")
                {
                    return redirect()->back();
                }
                else
                {
                    return redirect()->to($url);
                }
            }
            else
            {
                request()->session()->flash("response", $response);
            }
        }
    }

    /**
     * @param array $urls
     * @return array
     */
    public function validateUrls($urls=[])
    {
        return Permission::validateUrls($urls);
    }
}
