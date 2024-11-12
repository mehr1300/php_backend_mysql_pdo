<?php
require_once "../../libs/init.php";

// ثبت مسیرهای GET
//Router::Route('POST', '/user/login', 'login3');

Router::Route('POST', '/admin/login',  [AdminController::class, 'login']);
Router::Route('POST', '/admin/logout',  [AdminController::class, 'logout']);
Router::Route('POST', '/admin/check-login',  [AdminController::class, 'checkLogin']);
Router::Route('POST', '/admin/list',  [AdminController::class, 'list'], [['Auth::CheckAuth', 'admin']]);
Router::Route('GET', '/admin/list2/:id/:page',  [AdminController::class, 'list2'], [['Auth::CheckAuth', 'admin']]);


