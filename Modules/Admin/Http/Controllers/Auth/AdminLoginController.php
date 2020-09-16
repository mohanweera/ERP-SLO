<?php

namespace Modules\Admin\Http\Controllers\Auth;

use App\Country;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\View;
use Modules\Admin\Entities\AdminLoginHistory;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\SystemAccessAdminIpRestriction;
use Modules\Admin\Entities\SystemAccessIpRestriction;
use Modules\Admin\Repositories\SystemAccessIpRestrictionRepository;
use Modules\Admin\Services\Location;
use Modules\Admin\Services\Permission;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin:admin')->except('logout', 'validateSession');

        View::composer("*", function ($view){

            $template = str_replace("/", "-", request()->path());
            $view->with("template", $template);
        });
    }

    protected function guard()
    {
        return Auth::guard("admin");
    }

    /**
     * Show the login form.
     *
     * @return Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin::auth.login',[
            'title' => 'Admin Login',
            'loginRoute' => route('dashboard.login'),
            'forgotPasswordRoute' => 'dashboard.password.request',
        ]);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            return route('dashboard.login');
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @param $admin
     * @return mixed
     */
    protected function authenticated(Request $request, $admin)
    {
        if($admin->status == "1")
        {
            if($admin->super_user == "1")
            {
                $request->session()->put("super_user", true);
            }
            else
            {
                $request->session()->put("super_user", false);
            }

            if($admin->default_admin == "1")
            {
                //this admin have access to all the operations of the system
                $request->session()->put("default_admin", true);

                $notify["status"] = "success";
                $notify["notify"][] = "You just signed in successfully.";
                $notify["notify"][] = "You are welcome " . $admin["name"] . ".";

                $request->session()->flash("notify", $notify);

                $this->recordLoginActivity($admin->id);

                return redirect()->intended(route('dashboard.home'));
            }
            else
            {
                $request->session()->put("default_admin", false);

                //get user role status
                $adminRole = AdminRole::find($admin["admin_role_id"]);

                if ($adminRole->role_status == "1")
                {
                    //validate system accessed IP
                    $ip = Location::getClientIP();

                    if($admin->super_user == "1" || $this->isValidIP($admin->admin_id, $ip))
                    {
                        $notify["status"] = "success";
                        $notify["notify"][] = "You just signed in successfully.";
                        $notify["notify"][] = "You are welcome " . $admin["name"] . ".";

                        $request->session()->flash("notify", $notify);

                        //this is a normal admin, we have to gather permission data of this user
                        $permissions = Permission::getPermissions($admin->id, $adminRole->admin_role_id);
                        $request->session()->put("permissions", $permissions);

                        $allowedRoles = $this->getAllowedUserRoles($admin, $adminRole);
                        $request->session()->put("allowed_roles", $allowedRoles);

                        $this->recordLoginActivity($admin->id);

                        return redirect()->intended(route('dashboard.home'));
                    }
                    else
                    {
                        $this->clearSession();

                        $notify["status"] = "failed";
                        $notify["notify"][] = "Your current network IP address is not allowed to access the system.";
                        $notify["notify"][] = "Please contact system administrator for more information with following IP address.";
                        $notify["notify"][] = "Your current IP address: ".$ip;

                        $loginFailedReason = "Tried to access system from unknown location.";

                        $request->session()->flash("notify", $notify);

                        $this->recordLoginActivity($admin->id, true, $loginFailedReason);

                        return redirect()->back();
                    }
                }
                else
                {
                    $this->clearSession();

                    if ($adminRole->disabled_reason != "")
                    {
                        $notify["status"] = "failed";
                        $notify["notify"][] = "Your admin role has been disabled due to following reason.";
                        $notify["notify"][] = $adminRole->disabled_reason;

                        $request->session()->flash("notify", $notify);

                        $loginFailedReason = $adminRole["disabled_reason"];
                    }
                    else
                    {
                        $notify["status"] = "failed";
                        $notify["notify"][] = "Your admin role has been disabled.";
                        $notify["notify"][] = "Please contact system administrator for more information.";

                        $loginFailedReason = "Admin role has been disabled";

                        $request->session()->flash("notify", $notify);
                    }

                    $this->recordLoginActivity($admin->id, true, $loginFailedReason);

                    return redirect()->back();
                }
            }
        }
        else
        {
            $this->clearSession();

            $notify["status"]="failed";
            if ($admin->disabled_reason!= "")
            {
                $notify["notify"][]="Your account has been disabled due to following reason";
                $notify["notify"][]=$admin->disabled_reason;
                $notify["notify"][]="Please contact system administrator for more information.";

                $loginFailedReason = $admin->disabled_reason;
            }
            else
            {
                $notify["notify"][]="Your account has been disabled.";
                $notify["notify"][]="Please contact system administrator for more information.";

                $loginFailedReason = "Admin account has been disabled.";
            }

            $request->session()->flash("notify", $notify);
            $this->recordLoginActivity($admin->id, true, $loginFailedReason);

            return redirect()->back();
        }
    }

    protected function clearSession()
    {
        $this->guard()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    protected function getAllowedUserRoles($admin, $adminRole)
    {
        $adminRoleAllowed = $adminRole->allowed_roles;
        $adminAllowed = $admin->allowed_roles;
        $adminDisallowed = $admin->disallowed_roles;

        if($adminRoleAllowed == null)
        {
            $adminRoleAllowed = [];
        }

        if($adminAllowed == null)
        {
            $adminAllowed = [];
        }

        if($adminDisallowed == null)
        {
            $adminDisallowed = [];
        }

        $allowedRoles = array_merge($adminAllowed, array_diff($adminRoleAllowed, $adminAllowed));
        $allowedRoles = array_diff($allowedRoles, $adminDisallowed);

        return $allowedRoles;
    }

    protected function isValidIP($adminId, $ip)
    {
        $ipRepo = new SystemAccessIpRestrictionRepository();
        $ipHash = $ipRepo->generateIPHash($ip);

        //check if this IP exists in the system
        $sysIP = SystemAccessIpRestriction::query()->where(["ip_address_key" => $ipHash])->first();

        if($sysIP && $sysIP->access_status == "1")
        {
            return true;
        }
        else
        {
            //check if this Admin have special access to this IP
            $adminIP = SystemAccessAdminIpRestriction::query()->where(["ip_address_key" => $ipHash])->first();

            if($adminIP && $adminIP->access_status == "1")
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $notify["status"]="failed";
        $notify["notify"][]="Signing into the system was failed.";
        $notify["notify"][]="Please try again.";

        $request->session()->flash("notify", $notify);

        return redirect()->back();
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @param int $method
     * @return mixed
     */
    public function logout(Request $request, $method=1)
    {
        $adminLoginHistoryId = $request->session()->get("admin_login_history_id");

        if($adminLoginHistoryId == "")
        {
            if(isset($_COOKIE["adminLoginHistoryId"]))
            {
                $adminLoginHistoryId = $_COOKIE["adminLoginHistoryId"];
            }
        }

        $this->recordLogOutActivity($adminLoginHistoryId, $method);

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if($method)
        {
            $notify["status"]="success";
            $notify["notify"][]="You just signed out successfully.";
            $notify["notify"][]="See you again soon.";
        }
        else
        {
            $notify["status"]="warning";
            $notify["notify"][]="Your session has been expired due to inactivity.";
        }

        if($request->expectsJson())
        {
            $response["notify"]=$notify;
            return response()->json($response, 201);
        }
        else
        {
            $request->session()->flash("notify", $notify);
            return redirect()->route('dashboard.login');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function validateSession(Request $request)
    {
        if(isset($_COOKIE["adminLoginHistoryId"]) && isset($_COOKIE["adminLastActivityAt"]))
        {
            $adminLastActivityAt = strtotime($_COOKIE["adminLastActivityAt"]);

            $sessionTime = config("session.lifetime");
            $sessionTime = intval($sessionTime)*60;

            if($adminLastActivityAt<=time()-$sessionTime)
            {
                return $this->logout($request, 0);
            }
            else
            {
                $adminLoginHistoryId = $_COOKIE["adminLoginHistoryId"];
                $adminLH = AdminLoginHistory::find($adminLoginHistoryId);

                if($adminLH)
                {
                    if($adminLH["sign_out_at"] == "")
                    {
                        $notify["status"]="success";
                        $notify["notify"][]="In session.";
                    }
                    else
                    {
                        $notify["status"]="failed";
                        $notify["notify"][]="Your session has been expired.";
                    }
                }
                else
                {
                    $notify["status"]="failed";
                    $notify["notify"][]="Your session has been expired.";
                }

                $response["notify"]=$notify;
                return response()->json($response, 201);
            }
        }
    }

    private function recordLogOutActivity($adminLoginHistoryId, $method)
    {
        if($adminLoginHistoryId!="")
        {
            $lh = AdminLoginHistory::find($adminLoginHistoryId);

            if($lh)
            {
                $lh->online_status = 0;
                $lh->sign_out_type = $method; //manual or auto sign out; manual:1, auto:0
                $lh->sign_out_at = date("Y-m-d H:i:s", time());

                $lh->save();

                if(isset($_COOKIE["adminLoginHistoryId"]))
                {
                    setcookie("adminLoginHistoryId", "", time()- 60, "/", "", 0);
                }
            }
        }
    }

    private function recordLoginActivity($adminId, $failed=false, $loginFailedReason="")
    {
        $ip = Location::getClientIP();
        $geoData = Location::getGeoData($ip);

        $countryCode = $geoData["countryCode"];

        $country = Country::where("country_code", "=", $countryCode)->first();

        $countryId = null;
        if($country)
        {
            $countryId = $country->country_id;
        }

        $adminLH = new AdminLoginHistory();
        $adminLH->admin_id = $adminId;
        $adminLH->login_ip = $ip;
        $adminLH->country_id = $countryId;
        $adminLH->city = $geoData["city"];
        if($failed)
        {
            $adminLH->login_failed_reason = $loginFailedReason;
            $adminLH->online_status = 0;
        }
        else
        {
            $adminLH->online_status = 1;
        }
        $adminLH->last_activity_at = date("Y-m-d H:i:s", time());
        $adminLH->sign_in_at = date("Y-m-d H:i:s", time());

        $save = $adminLH->save();

        if($save)
        {
            $primaryKey = $adminLH->getKeyName();
            $admin_login_history_id = $adminLH->$primaryKey;

            request()->session()->put("admin_login_history_id", $admin_login_history_id);

            $sessionTime = config("session.lifetime");
            $sessionTime = intval($sessionTime)*60;

            setcookie("adminLoginHistoryId", $admin_login_history_id, time()+$sessionTime+3600);
            setcookie("adminLastActivityAt", $adminLH->last_activity_at, time()+$sessionTime+3600);
        }
    }
}
