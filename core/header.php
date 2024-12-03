<?php

/** Error Report Configuration : */
ini_set('display_errors', STATUS_PROJECT === "DEVELOPMENT" ? 'On' : 'Off');
ini_set('display_startup_errors', STATUS_PROJECT === "DEVELOPMENT" ? 'On' : 'Off');
error_reporting(E_ALL);


/** Initialize headers */
date_default_timezone_set('Asia/Tehran');
header("Content-Type: x-www-form-urlencoded");
if(isset($_SERVER['HTTP_ORIGIN'])){
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if(STATUS_PROJECT === "PRODUCT"){
        if (in_array($http_origin , AUTHORIZED_URL_PRODUCT))
        {
            header("Access-Control-Allow-Origin: $http_origin");
        }
    }else{
        if (in_array($http_origin , AUTHORIZED_URL_DEVELOPMENT))
        {
            header("Access-Control-Allow-Origin: $http_origin");
        }
    }
}
header('Access-Control-Allow-Credentials : true');
header("Access-Control-Allow-Headers: *");
