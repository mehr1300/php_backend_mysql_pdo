<?php
require_once "../../libs/init.php";


router::Route('POST', '/admin/login',  [AdminController::class, 'login']);
router::Route('POST', '/admin/logout',  [AdminController::class, 'logout']);
router::Route('POST', '/admin/check-login',  [AdminController::class, 'checkLogin']);
router::Route('POST', '/admin/list',  [AdminController::class, 'list'], [['Auth::CheckAuth', 'admin']]);
router::Route('GET', '/admin/list2/:id/:page',  [AdminController::class, 'list2'], [['Auth::CheckAuth', 'admin']]);


