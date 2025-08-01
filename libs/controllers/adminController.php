<?php

class AdminController {
    public static function login(): bool|string {
        $input = Base::Isset(
            [
                'username' => "username",
                'password' => "password",
            ]
        );

        if(DEFAULT_USERNAME !== $input['username'] ) return Base::SetError("نام کاربری و یا رمز عبور اشتباه است");
        if(DEFAULT_PASSWORD !== $input['password'] ) return Base::SetError("نام کاربری و یا رمز عبور اشتباه است");
        $data = [
            "id" => 1,
            "username" => $input['username'],
            "role" => "admin",
        ];
        $token = auth::GenerateToken($data);
        Base::SendToken($token);

        return json_encode([
            "result" => null,
            "message" => "شما با موفقیت وارد شدید."
        ]);
    }
    public static function checkLogin(): bool|string
    {
        return json_encode([
            "result" => null,
            "message" => null
        ]);
    }
    public static function logout(): bool|string
    {
        Base::SendToken("",time());
        return json_encode(array(
            "result" => null,
            "message" => "خروج از حساب کاربری با موفقیت انجام شد."
        ));
    }



}
