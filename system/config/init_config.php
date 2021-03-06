<?php

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", getcwd() . DS);
define("APP_PATH"   , ROOT . 'app' . DS);
define("PUBLIC_PATH", ROOT . 'public' . DS);
define("SYSTEM_PATH", ROOT . 'system' . DS);

define("CONTROLLER", "controller" . DS);
define("MODEL", "model" . DS);
define("VIEW", "view" . DS);

define("CONFIG_PATH", SYSTEM_PATH . "config" . DS);
define("CORE_PATH"  , SYSTEM_PATH . 'core' . DS);
define("DB_PATH"    , SYSTEM_PATH . 'database' . DS);
define("LIB_PATH"   , SYSTEM_PATH . "library" . DS);

define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);

require CORE_PATH . "Router.php";
require CORE_PATH . "Controller.php";
require CORE_PATH . "Loader.php";
require DB_PATH   . "Db.php"; // Custom MySQLi class
require DB_PATH   . "PDO_DB.php"; // Extended PDO class with credentials
require CORE_PATH . "Model.php";
require CORE_PATH . "Registry.php";

require CONFIG_PATH . "config.php";
require CONFIG_PATH . "db_config.php";

//$GLOBALS['config'] = include CONFIG_PATH . "config.php";

session_start();
