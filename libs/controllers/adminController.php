<?php

class AdminController {
    public static function login(): bool|string {
        $input = Base::Isset(['username' => "username",'password' => "password"]);

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
    public static function list(): bool|string
    {
//        $data = [
//            "name" => "admin",
//            "family" => "post",
//            "age" => "22",
//        ];
//        return json_encode(array(
//            "result" => $data,
//            "message" => null
//        ));

        $page_number =1;
        $limit = 10000;
        $offset = ($page_number - 1) * $limit;


        $testArray = ["galaxy-a31" , "galaxy-a70" , "galaxy-a10"];
        foreach ($testArray as $value){
            $getData = PD::MultiSelect("tbl_data","WHERE model like '%$value%' ORDER BY id DESC LIMIT $offset,$limit",[]);
            $getDataCount = PD::SingleSelect("tbl_data","WHERE model like '%$value%' ORDER BY id DESC LIMIT $offset,$limit",[]);
            foreach ($getData as $key => $value2) {
                PD::Update("tbl_data",["model" => "galaxy-a31"],"WHERE id = ?",[$value2["id"]]);
            }
        }


//        $getData = PD::MultiSelect("tbl_data","WHERE model like '%galaxy-a31%' ORDER BY id DESC LIMIT $offset,$limit",[]);
//        $getDataCount = PD::SingleSelect("tbl_data","WHERE model like '%galaxy-a31%' ORDER BY id DESC LIMIT $offset,$limit",[]);
//
//                foreach ($getData as $key => $value) {
//                    PD::Update("tbl_data",["model" => "galaxy-a31"],"WHERE id = ?",[$value["id"]]);
//
//                }

//        foreach ($getData as $key => $value) {
//            $model = str_replace("سامسونگ","",$value["model"]);
//            $model = str_replace(" ","-",$model);
//            $model = trim($model);
//            $model = trim($model,"-");
//            $model = strtolower($model);
//            PD::Update("tbl_data",["model" => $model],"WHERE id = ?",[$value["id"]]);
//        }

//        foreach ($getData as $key => $value) {
//            if($value["brand"] == 'other'){
//               PD::Delete("tbl_data","WHERE id = ?",[$value["id"]]);
//            }
//        }

//        foreach ($getData as $key => $value) {
//            if($value != null){
//                $model = str_replace("گیگابایت","G",$value["internal_storage"]);
//
//                PD::Update("tbl_data",["internal_storage" => $model],"WHERE id = ?",[$value["id"]]);
//            }
//        }

        $result = [];
//        foreach ($getData as $key => $value) {
//            if ($value["model"] != null) {
//                if (!in_array($value["model"], $result)) {
//                    $result[] = $value["model"];
//                }
//            }
//        }
        return json_encode(array(
            "getDataCount" => $getDataCount,
            "result" => $result,
            "message" => "ok"
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
