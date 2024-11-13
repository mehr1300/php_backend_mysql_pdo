<?php
require_once "../../libs/init.php";

function set_user_meta_data($meta_type = "")
{
    $info = "";
    $request_method = "";
    if(isset($_POST) && !empty($_POST)){
        foreach ($_POST as $key => $value) {
            if($key !=="password"){
                $info .=$key.":".$value."/";
            }else{
                $info .=$key.":"."password_hidden"."/";
            }
        }
        $info = trim($info,"/");
        $request_method = 'POST';
    }
    if(isset($_GET) && !empty($_GET)){
        foreach ($_GET as $key => $value) {
            if($key !=="password"){
                $info .=$key.":".$value."/";
            }else{
                $info .=$key.":"."password_hidden"."/";
            }
        }
        $info = trim($info,"/");
        $request_method = 'GET';
    }
    PD::Insert("tbl_user_meta_data", array(
        "request_method" => $request_method,
        "user_ip" => $_SERVER['REMOTE_ADDR'],
        "user_agent" => $_SERVER['HTTP_USER_AGENT'],
        "meta_type" => $meta_type,
        "meta_info" => $info,
    ));
}


if(GET_LOGS_META){
    set_user_meta_data("log");
}
