<?php

class UserController {
    public static function login(): bool|string {
        list($username,$password) = Base::Isset($_POST,['username' => "username",'password' => "password"]);
        //    $password = Base::HashPassword($password);
        $username_default = "admin";
        $password_default = "Aa@12345";

        if($username_default !== $username ) Base::ReturnError("نام کاربری و یا رمز عبور اشتباه است");
        if($password_default !== $password ) Base::ReturnError("نام کاربری و یا رمز عبور اشتباه است");
        $data = [
            "user_id" => 1,
            "username" => $username,
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
