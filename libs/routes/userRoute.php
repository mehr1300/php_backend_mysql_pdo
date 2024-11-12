<?php
require_once "../../libs/init.php";



Router::Route('POST', '/user/login',  [UserController::class, 'login']);
Router::Route('POST', '/user/logout',  [UserController::class, 'logout']);
Router::Route('POST', '/user/check-login',  [UserController::class, 'checkLogin']);
Router::Route('POST', '/user/list',  [UserController::class, 'list'],[['Auth::CheckAuth', 'user']]);
