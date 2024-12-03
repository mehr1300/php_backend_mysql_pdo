<?php
require_once "../../libs/init.php";

router::Route('POST', '/user/login',  [UserController::class, 'login']);
router::Route('POST', '/user/logout',  [UserController::class, 'logout']);
router::Route('POST', '/user/check-login',  [UserController::class, 'checkLogin']);
router::Route('POST', '/user/list',  [UserController::class, 'list'],[['Auth::CheckAuth', 'user']]);
