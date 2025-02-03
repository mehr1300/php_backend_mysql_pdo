<?php
require_once "../../libs/init.php";


router::Route("GET","/index/person/list",[IndexController::class,"listPerson"]);
router::Route("GET","/index/person/get/:id",[IndexController::class,"getPerson"]);
router::Route("GET","/index/loan/list",[IndexController::class,"loanList"]);
router::Route("POST","/index/loan/list",[IndexController::class,"loanList2"]);
router::Route("PUT","/index/loan/list/:id",[IndexController::class,"loanList3"]);
