<?php

//Load Configuration File

require_once "config/config.php";

//Autoload Core Libraries

spl_autoload_register(

    function ($classname) {
        require_once "core/" .$classname. ".php";
    }
);