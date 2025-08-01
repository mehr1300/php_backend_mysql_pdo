<?php
require_once "../../libs/init.php";


Router::Route("GET","/index/example",[IndexController::class,"example"]);



