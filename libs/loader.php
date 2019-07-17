<?php

require(__DIR__."/Psr/Autoloader.php");
//
\Psr\Autoloader::getInstance()
    ->addNamespaces(
        [
            "Test",
            "Mock",
            "Hotels",
        ],
        __DIR__
    )
    ->register()
;
