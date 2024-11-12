<?php
if (file_exists("../core/init.php")) {
    require_once "../core/init.php";
} elseif (file_exists("../../core/init.php")) {
    require_once "../../core/init.php";
}
require_once 'Middleware.php';
require_once "logs.php";

require_once "controllers/indexController.php";
require_once "controllers/userController.php";
require_once "controllers/adminController.php";

require_once 'routes/indexRoute.php';
require_once 'routes/userRoute.php';
require_once 'routes/adminRoute.php';

