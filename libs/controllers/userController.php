<?php

class UserController {
    public static function login(): bool|string {
        $input = Base::Isset($_POST,['username' => "username",'password' => "password"]);
        $username_default = "admin";
        $password_default = "Aa@12345";

        if($username_default !== $input['username'] ) Base::SetError("نام کاربری و یا رمز عبور اشتباه است");
        if($password_default !== $input['password'] ) Base::SetError("نام کاربری و یا رمز عبور اشتباه است");
        $data = [
            "id" => 1,
            "username" => $input['username'],
            "role" => "user",
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
    public static function list(): bool|string
    {
        $data = [
            "name" => "user",
            "family" => "rostami",
            "age" => "22",
        ];
        return json_encode(array(
            "result" => $data,
            "message" => null
        ));
    }

}
