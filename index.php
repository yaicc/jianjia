<?php

define("ROOT_PATH",  realpath(dirname(__FILE__)));
define ("APP_PATH", ROOT_PATH . "/application");
define ("STATIC_PATH", ROOT_PATH . "/static");
define ("theme", "default");

$app  = new Yaf_Application(ROOT_PATH . "/conf/application.ini");
$app->bootstrap()->run();