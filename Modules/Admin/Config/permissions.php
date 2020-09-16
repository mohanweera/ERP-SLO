<?php
$groups = [];

$permGroup = [];
$permGroup["name"] = "Administrator Manager";
$permGroup["slug"] = "admin";
$permGroup["permissions"][]=["action"=>"/admin/admin", "name"=>"List Administrators"];
$permGroup["permissions"][]=["action"=>"/admin/admin/trash", "name"=>"List Administrators in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin/create", "name"=>"Add New Administrator"];
$permGroup["permissions"][]=["action"=>"/admin/admin/edit", "name"=>"Edit Administrator"];
$permGroup["permissions"][]=["action"=>"/admin/admin/view", "name"=>"View Administrator"];
$permGroup["permissions"][]=["action"=>"/admin/admin/activate", "name"=>"Activate Administrator"];
$permGroup["permissions"][]=["action"=>"/admin/admin/deactivate", "name"=>"Deactivate Administrator"];
$permGroup["permissions"][]=["action"=>"/admin/admin/delete", "name"=>"Move To Administrator Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin/restore", "name"=>"Restore From Administrator Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin/grant_permissions", "name"=>"Grant Permissions To Administrators"];
$permGroup["permissions"][]=["action"=>"/admin/admin/revoke_permissions", "name"=>"Revoke Permissions Of Administrators"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_history", "name"=>"List Administrator Permission Change History"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_history/view", "name"=>"View Administrator Permission Change History Record"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Administrator Role Manager";
$permGroup["slug"] = "admin_role";
$permGroup["permissions"][]=["action"=>"/admin/admin_role", "name"=>"List Administrator Roles"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/trash", "name"=>"List Administrator Roles in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/create", "name"=>"Add New Administrator Role"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/edit", "name"=>"Edit Administrator Role"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/view", "name"=>"View Administrator Role"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/activate", "name"=>"Activate Administrator Role"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/deactivate", "name"=>"Deactivate Administrator Role"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/delete", "name"=>"Move To Administrator Roles Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role/restore", "name"=>"Restore From Administrator Roles Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role_permission_history", "name"=>"List Administrator Role Permission Change History"];
$permGroup["permissions"][]=["action"=>"/admin/admin_role_permission_history/view", "name"=>"View Administrator Role Permission Change History Record"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin Permission System Manager";
$permGroup["slug"] = "admin_permission_system";
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system", "name"=>"List Admin Permission Systems"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/trash", "name"=>"List Admin Permission Systems in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/create", "name"=>"Add New Admin Permission System"];
$permGroup["permissions"][]=["action"=>"/admin/import_permissions", "name"=>"Import permissions to the system"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/edit", "name"=>"Edit Admin Permission System"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/view", "name"=>"View Admin Permission System"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/activate", "name"=>"Activate Admin Permission System"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/deactivate", "name"=>"Deactivate Admin Permission System"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/delete", "name"=>"Move To Admin Permission Systems Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/restore", "name"=>"Restore From Admin Permission Systems Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_system/import_permissions", "name"=>"Bulk Import Permissions To The System"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin Permission Module Manager";
$permGroup["slug"] = "admin_permission_module";
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module", "name"=>"List Admin Permission Modules"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/trash", "name"=>"List Admin Permission Modules in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/create", "name"=>"Add New Admin Permission Module"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/edit", "name"=>"Edit Admin Permission Module"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/view", "name"=>"View Admin Permission Module"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/activate", "name"=>"Activate Admin Permission Module"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/deactivate", "name"=>"Deactivate Admin Permission Module"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/delete", "name"=>"Move To Admin Permission Modules Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_module/restore", "name"=>"Restore From Admin Permission Modules Trash"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin Permission Group Manager";
$permGroup["slug"] = "admin_permission_group";
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group", "name"=>"List Admin Permission Groups"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/trash", "name"=>"List Admin Permission Groups in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/create", "name"=>"Add New Admin Permission Group"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/edit", "name"=>"Edit Admin Permission Group"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/view", "name"=>"View Admin Permission Group"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/activate", "name"=>"Activate Admin Permission Group"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/deactivate", "name"=>"Deactivate Admin Permission Group"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/delete", "name"=>"Move To Admin Permission Groups Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_permission_group/restore", "name"=>"Restore From Admin Permission Groups Trash"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin System Permission Manager";
$permGroup["slug"] = "admin_system_permission";
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission", "name"=>"List Admin System Permissions"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/trash", "name"=>"List Admin System Permissions in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/create", "name"=>"Add New Admin System Permission"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/edit", "name"=>"Edit Admin System Permission"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/view", "name"=>"View Admin System Permission"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/activate", "name"=>"Activate Admin System Permission"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/deactivate", "name"=>"Deactivate Admin System Permission"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/delete", "name"=>"Move To Admin System Permissions Trash"];
$permGroup["permissions"][]=["action"=>"/admin/admin_system_permission/restore", "name"=>"Restore From Admin System Permissions Trash"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "System Access IP Address Manager";
$permGroup["slug"] = "system_access_ip_restriction";
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction", "name"=>"List System Access IP Addresses"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/trash", "name"=>"List System Access IP Addresses in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/create", "name"=>"Add New System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/edit", "name"=>"Edit System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/view", "name"=>"View System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/activate", "name"=>"Activate System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/deactivate", "name"=>"Deactivate System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/delete", "name"=>"Move To System Access IP Addresses Trash"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_ip_restriction/restore", "name"=>"Restore From System Access IP Addresses Trash"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin System Access IP Address Manager";
$permGroup["slug"] = "system_access_admin_ip_restriction";
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction", "name"=>"List Admin System Access IP Addresses"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/trash", "name"=>"List Admin System Access IP Addresses in Trash"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/create", "name"=>"Add New Admin System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/edit", "name"=>"Edit Admin System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/view", "name"=>"View Admin System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/activate", "name"=>"Activate Admin System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/deactivate", "name"=>"Deactivate Admin System Access IP Address"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/delete", "name"=>"Move To Admin System Access IP Addresses Trash"];
$permGroup["permissions"][]=["action"=>"/admin/system_access_admin_ip_restriction/restore", "name"=>"Restore From Admin System Access IP Addresses Trash"];

$groups[]=$permGroup;

return [
    "slug" => "admin",
    "name" => "Administrator Operations Manager",
    "groups" => $groups
];

