<?php
ob_start();

$paths = parse_ini_file("defines.php");

date_default_timezone_set("Europe/Istanbul");
setlocale(LC_TIME,'turkish');

require_once($paths['path']."/vendor/autoload.php");

require_once($paths['path']."/dbOP/Db.class.php");
require_once($paths['path']."/dbOP/Log.class.php");

require_once($paths['path']."/dbOP/User.class.php");

require_once($paths['path']."/dbOP/Target.class.php");
require_once($paths['path']."/dbOP/TargetTag.class.php");
require_once($paths['path']."/dbOP/DataFeed.class.php");

require_once($paths['path']."/dbOP/class.upload.php");
require_once($paths['path']."/dbOP/func.php");

@session_start();
?>