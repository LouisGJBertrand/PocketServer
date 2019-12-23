<?php

    print("setting up views".PHP_EOL);

    $views = new views;

    $views->newView("userCapable.home","views/home.action.php");
    $views->newView("userError.404","views/404.action.php");

    return $views;