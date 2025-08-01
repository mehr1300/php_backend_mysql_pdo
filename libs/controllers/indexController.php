<?php

class IndexController {

    public static function example(): bool|string {
        $input = Base::Isset([
            'username' => "username|minLength:3|maxLength:20|label:'نام کاربری'|options:toLow=false,arTfa=true",
            'password' => "password|minLength:8|label:'رمز عبور'",
            'status' => ['active', 'inactive', 'pending'],
            'search' => "string|minLength:1|empty|label:جستجو",
            'old' => "int|min:18|label:سن",
            'email' => "email|empty|maxLength:100|label:ایمیل",
            'money' => "money|min:1000|max:1000000|label:مبلغ",
            'description' => "textarea|length:220|label:توضیحات|options:toLow=true,arTfa=false",
            'role' => "string|in:admin,user,guest|label:نقش",
            'score' => "int|max:100|in:0,50,100|label:امتیاز",
        ]);

        return json_encode([
            "result" => $input,
            "message" => "ok",
        ]);

    }
}
