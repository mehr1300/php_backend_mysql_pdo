<?php

class IndexController {

    public static function example(): bool|string {
        $result = PD::CallProcedure('test', ['num1' => 1, 'num2' => 3]);

        return json_encode([
            "result" => $result,
            "message" => "ok",
        ]);

    }
}
