<?php
header("Content-Type: application/json");
die(json_encode(array(
    "status"=>"wrong_url",
    "message"=>"your requests api method not exists."
)));
