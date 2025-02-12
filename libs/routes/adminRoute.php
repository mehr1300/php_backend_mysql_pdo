<?php
require_once "../../libs/init.php";


Router::Route('POST', '/admin/login',  [AdminController::class, 'login']);
Router::Route('POST', '/admin/logout',  [AdminController::class, 'logout']);
Router::Route('POST', '/admin/check-login',  [AdminController::class, 'checkLogin']);
Router::Route('POST', '/admin/list',  [AdminController::class, 'list'], 'admin');
Router::Route('GET', '/admin/list2/:id/:page',  [AdminController::class, 'list2'], 'admin');


