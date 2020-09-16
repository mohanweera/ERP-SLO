<?php
$groups = [];

$permGroup = [];
$permGroup["name"] = "Faculty Manager";
$permGroup["slug"] = "faculty";
$permGroup["permissions"][]=["action"=>"/academic/faculty", "name"=>"List Faculties"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/trash", "name"=>"List Faculties in Trash"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/create", "name"=>"Add New Faculty"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/edit", "name"=>"Edit Faculty"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/view", "name"=>"View Faculty"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/activate", "name"=>"Activate Faculty"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/deactivate", "name"=>"Deactivate Faculty"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/delete", "name"=>"Move To Faculty Trash"];
$permGroup["permissions"][]=["action"=>"/academic/faculty/restore", "name"=>"Restore From Faculty Trash"];

$groups[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Department Manager";
$permGroup["slug"] = "department";
$permGroup["permissions"][]=["action"=>"/academic/department", "name"=>"List Departments"];
$permGroup["permissions"][]=["action"=>"/academic/department/trash", "name"=>"List Departments in Trash"];
$permGroup["permissions"][]=["action"=>"/academic/department/create", "name"=>"Add New Department"];
$permGroup["permissions"][]=["action"=>"/academic/department/edit", "name"=>"Edit Department"];
$permGroup["permissions"][]=["action"=>"/academic/department/view", "name"=>"View Department"];
$permGroup["permissions"][]=["action"=>"/academic/department/activate", "name"=>"Activate Department"];
$permGroup["permissions"][]=["action"=>"/academic/department/deactivate", "name"=>"Deactivate Department"];
$permGroup["permissions"][]=["action"=>"/academic/department/delete", "name"=>"Move To Departments Trash"];
$permGroup["permissions"][]=["action"=>"/academic/department/restore", "name"=>"Restore From Departments Trash"];

$groups[]=$permGroup;

return [
    "slug" => "academic",
    "name" => "Academic Operations Manager",
    "groups" => $groups
];

