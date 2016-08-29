<?php

ini_set("display_errors", "1");

mb_internal_encoding("UTF-8");

define("DB_HOST", "localhost");
define("DB_NAME", "compsa");
define("DB_USER", "root");
define("DB_PASS", "root");

spl_autoload_register(function($class) {
	include_once "classes/$class.php";
});

define("MAX_FILESIZE", 8388608);    // 8 MB