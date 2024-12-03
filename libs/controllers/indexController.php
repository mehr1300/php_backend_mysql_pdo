<?php

class IndexController {
    public static function loanList() {

        return json_encode([
            "result" => "success",
            "message" => "درست شد1",
        ]);

    }
    public static function loanList2() {

        return json_encode([
            "result" => "success",
            "message" => "درست شد2",
        ]);

    }
    public static function loanList3($id) {

        return json_encode([
            "result2" => $id,
            "result" => "success",
            "message" => "درست شد3",
        ]);

    }
}
