<?php

class AdminController {
    public static function login(): bool|string {
        list($username,$password) = Base::Isset($_POST,['username' => "username",'password' => "password"]);

        if(DEFAULT_USERNAME !== $username ) Base::ReturnError("نام کاربری و یا رمز عبور اشتباه است");
        if(DEFAULT_PASSWORD !== $password ) Base::ReturnError("نام کاربری و یا رمز عبور اشتباه است");
        $data = [
            "user_id" => 1,
            "username" => $username,
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
    public static function list(): bool|string
    {
        $data = [
            "name" => "admin",
            "family" => "post",
            "age" => "22",
        ];
        return json_encode(array(
            "result" => $data,
            "message" => null
        ));
    }
    public static function list2($id,$page): bool|string
    {
        $data = [
            "name" => "admin",
            "family" => "get",
            "id" => $id,
            "page" => $page,
        ];
        return json_encode(array(
            "result" => $data,
            "message" => null
        ));
    }

}
