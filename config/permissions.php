<?php
$groups = [];

//this is a sample group
/*$permGroup = [];
$permGroup["name"] = "Name Of The Group";
$permGroup["slug"] = "sample_group_slug"; //underscore separated slug
$permGroup["permissions"][]=["action"=>"/sample", "name"=>"List Samples"];
$permGroup["permissions"][]=["action"=>"/sample/trash", "name"=>"List Samples in Trash"];
$permGroup["permissions"][]=["action"=>"/sample/create", "name"=>"Add New Sample"];
$permGroup["permissions"][]=["action"=>"/sample/edit", "name"=>"Edit Sample"];
$permGroup["permissions"][]=["action"=>"/sample/view", "name"=>"View Sample"];
$permGroup["permissions"][]=["action"=>"/sample/activate", "name"=>"Activate Sample"];
$permGroup["permissions"][]=["action"=>"/sample/deactivate", "name"=>"Deactivate Sample"];
$permGroup["permissions"][]=["action"=>"/sample/delete", "name"=>"Move To Sample Trash"];
$permGroup["permissions"][]=["action"=>"/sample/restore", "name"=>"Restore From Sample Trash"];

$groups[]=$permGroup;*/

return [
    "slug" => "default",
    "name" => "Default System Permissions",
    "groups" => $groups
];

