<?php
require_once "../../libs/init.php";


Router::Route("GET","/index/person/list",[IndexController::class,"listPerson"]);
Router::Route("GET","/index/person/get/:id",[IndexController::class,"getPerson"]);
Router::Route("GET","/index/loan/list",[IndexController::class,"loanList"]);
Router::Route("POST","/index/loan/list",[IndexController::class,"loanList2"]);
Router::Route("PUT","/index/loan/list/:id",[IndexController::class,"loanList3"]);
