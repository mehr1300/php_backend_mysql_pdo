<?php

class IndexController {

    public static array $people = [
        [
            "id" => 1,
            "name" => "555علی رضایی",
            "age" => 28,
            "email" => "ali.rezaei@example.com",
            "phone" => "09123456789"
        ],
        [
            "id" => 2,
            "name" => "سارا محمدی",
            "age" => 32,
            "email" => "sara.mohammadi@example.com",
            "phone" => "09119876543"
        ],
        [
            "id" => 3,
            "name" => "محمد احمدی",
            "age" => 25,
            "email" => "mohammad.ahmadi@example.com",
            "phone" => "09117654321"
        ],
        [
            "id" => 4,
            "name" => "مینا حسینی",
            "age" => 30,
            "email" => "mina.hosseini@example.com",
            "phone" => "09111234567"
        ],
        [
            "id" => 5,
            "name" => "رضا کریمی",
            "age" => 27,
            "email" => "reza.karimi@example.com",
            "phone" => "09113217654"
        ],
        [
            "id" => 6,
            "name" => "الهام شریفی",
            "age" => 29,
            "email" => "elham.sharifi@example.com",
            "phone" => "09114447788"
        ],
        [
            "id" => 7,
            "name" => "حسن عباسی",
            "age" => 35,
            "email" => "hasan.abbasi@example.com",
            "phone" => "09117775544"
        ],
        [
            "id" => 8,
            "name" => "333ندا مرادی",
            "age" => 26,
            "email" => "neda.moradi@example.com",
            "phone" => "09118886655"
        ],
        [
            "id" => 9,
            "name" => "کمال فتحی",
            "age" => 33,
            "email" => "kamal.fathi@example.com",
            "phone" => "09115553322"
        ],
        [
            "id" => 10,
            "name" => "فرزانه قاسمی",
            "age" => 31,
            "email" => "farzaneh.ghasemi@example.com",
            "phone" => "09112221144"
        ]
    ];


    public static function listPerson(): bool|string {

        return json_encode([
            "result" => array_slice(self::$people, 0, 7),
            "message" => "ok",
        ]);

    }

    public static function getPerson($id): bool|string {

        $getData = array_filter(self::$people, function ($person) use ($id) {
            return $person["id"] == $id;
        });

        $result = reset($getData);

        if (!$result) {
            http_response_code(404);
            return json_encode([
                "result" => null,
                "message" => "not found",
            ]);
        }else{
            return json_encode([
                "result" => $result,
                "message" => "ok",
            ]);
        }

    }

    public static function loanList() {

        return json_encode([
            "result" => "success",
            "message" => "ok",
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
