<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $objTypes = [
        "app_settings" => "0",
        "M_Menu" => "1",
        "M_Role" => "2",
        "M_Menu_Group" => "3",
        "M_Menu_Group_Detail" => "4",
        "M_User" => "5",
    ];
}
