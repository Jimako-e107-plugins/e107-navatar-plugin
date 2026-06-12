<?php

namespace Navatar\Plugin\Listeners;

use Navatar\Plugin\Controllers\Navatar;
use Navatar\Plugin\Main;

class UserEvents
{
    public static function activate($data)
    {
        Navatar::assign($data);
    }

    public static function login($data)
    {
        Navatar::assign($data);
    }
}