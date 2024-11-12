<?php
require_once "../../core/init.php";

header("Content-Type: x-www-form-urlencoded");

//echo "asasdsdddddddddddddddad";
//echo "asasdsdddddddddddddddad";
$raw_token = Crypto::encryptThis("ssss");
echo $raw_token;
$raw_token = Crypto::decryptThis($raw_token);

if($raw_token){
    echo $raw_token;
}else{
    echo "noooooooooooooooooooo";
}


