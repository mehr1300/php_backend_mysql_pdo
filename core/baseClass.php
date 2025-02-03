<?php

use JetBrains\PhpStorm\NoReturn;

class Base
{
    public static function SendToken($token ,$expiresTime = TOKEN_EXPIRE_TIME , $tokenName = TOKEN_NAME,$path = "/", $secure = true ,$httponly = true ,$sameSite = "None"): string
    {
        $domain = STATUS_PROJECT == "DEVELOPMENT" ? $_SERVER['HTTP_HOST'] : "." . ORIGINAL_DOMAIN;
        return setcookie($tokenName, $token, [
            "expires" => time() + $expiresTime,
            "path" => $path,
            "secure" => $secure,
            "httponly" => $httponly,
            "domain" => $domain,
            "sameSite" => $sameSite,

        ]);
    }
    #[NoReturn] public static function ReturnError($message = "خطا در عملیات",$code = 400): void
    {
        header("Content-Type: x-www-form-urlencoded");
        http_response_code($code);
        die (json_encode(array(
            "result" => null,
            "message" => $message
        )));
    }
    #[NoReturn] public static function BaseRedirectTo($addr): void
    {
        header("location:$addr");
        exit;
    }
    public static function HashPassword($str): string
    {
        return password_hash($str, PASSWORD_ARGON2ID);
    }
    public static function PasswordVerify($entered_password,$hashed_password_from_db): bool {
        if (password_verify($entered_password, $hashed_password_from_db)) {
            return true;
        } else {
            return false;
        }
    }
    public static function Isset($BaseArray,$keys): array {
        $result = [];
        foreach ($keys as $key => $value) {
            if (!isset($BaseArray[$key])) {
                self::ReturnError("مقادیر مورد نیاز ارسال نشده است . ");
            }
//            if (empty($BaseArray[$key])) {
//                self::ReturnError("مقادیر مورد نیاز ارسال نشد است.");
//            }
            $item = match ($value){
                "uuid" => Validate::UUID($BaseArray[$key]),
                "username" => Validate::Username($BaseArray[$key]),
                "password" => Validate::Password($BaseArray[$key]),
                "national_code" => Validate::NationalCode($BaseArray[$key]),
                "mobile" => Validate::Mobile($BaseArray[$key]),
                "phone" => Validate::Phone($BaseArray[$key]),
                "email" => Validate::Email($BaseArray[$key]),
                "code" => Code::Validate($BaseArray[$key]),
                "int" => Sanitizer::Number($BaseArray[$key]),
                "string" => Sanitizer::Char($BaseArray[$key]),
                "textarea" => Sanitizer::TextArea($BaseArray[$key]),
                "text_editor" => Sanitizer::TextEditor($BaseArray[$key]),
                "" => $BaseArray[$key],
            };
            $result = [...$result,$item];
        }
        return $result;
    }
    public static function ChangeSpaceWithChar($value,$char = "-"): string
    {
        $value = Sanitizer::Char($value);
        $value = preg_replace('/\s+/', $char, $value);
        $value = htmlspecialchars($value);
        return htmlentities($value);
    }
    public static function ValidateNumberLessZero($num,$message = "خطا در عملیات"): ?int {
        if (Sanitizer::Number($num) > 0) {
            self::ReturnError($message);
        }
        return Sanitizer::Number($num);
    }
    public static function ValidateNumberGreaterZero($num,$message = "خطا در عملیات"): ?int {
        if (Sanitizer::Number($num) < 1) {
            self::ReturnError($message);
        }
        return Sanitizer::Number($num);
    }
    public static function SendSMS($data = null)
    {
        $url = "https://api.kavenegar.com/v1/66793962306279564942496C4867304B574F73564D732B76346A337135564665524D345A47596B766C4C303D/sms/send.json";

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'charset: utf-8'
        );
        $fields_string = "";
        if (!is_null($data)) {
            $fields_string = http_build_query($data);
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

        $response = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $curl_errno = curl_errno($handle);
        $curl_error = curl_error($handle);
        if ($curl_errno) {
            return "error";
        }
        $json_response = json_decode($response);
        if ($code != 200 && is_null($json_response)) {
            self::ReturnError("خطا در ارسال پیامک ورود"."- ارور 2");
        } else {
            $json_return = $json_response->return;
            if ($json_return->status != 200) {
                self::ReturnError("خطا در ارسال پیامک ورود"."-ارور 3");
            }
            return $json_return->status;
        }
    }
    public static function CreatePagingNumber($row_per_page,$page_number): array {
        $limit = self::ValidateNumberGreaterZero($row_per_page);
        $offset = (Sanitizer::Number($page_number) - 1) * $limit;
        return [$limit,$offset];
    }
    public static function ValNotExistInDbReturn($table_name,$condition,array $params,$message = "این رکورد تکراری است و قبلا استفاده شده است.")
    {
        self::ValidateNumberLessZero(PD::SingleSelect($table_name, $condition,$params),$message);
        return $params[0];
    }
    public static function ValExistInDbReturn($table_name,$condition,array $params,$message = "چنین رکوردی وجود ندارد.")
    {
        self::ValidateNumberGreaterZero(PD::SingleSelect($table_name, $condition,$params),$message);
        return $params[0];
    }
    public static function DuplicateValue(array $Duplicate_List , string $Table_Name, string $Condition): bool|int
    {
        if(!empty($Duplicate_List)){
            foreach ($Duplicate_List as $key => $value){
                $count =  Pd::SingleSelect($Table_Name,$Condition,array($key));
                if($count > 0){
                    self::ReturnError($value);
                }
            }
        }
        return true;
    }
}

class Sanitizer
{
    private static function PreventDefault($input): array|string
    {
        $value = str_replace("= ?", "", $input);
        $value = preg_replace("/\x{200c}/u", ' ', $value);
        $value = preg_replace("/\x{200F}/u", '', $value);
        $value = str_replace(">", "", $value);
        $value = str_replace("script>", "", $value);
        $value = str_replace("<script", "", $value);
        return str_replace("<", "", $value);
    }
    private static function ClearChar($value): string
    {
        $value = str_replace(['"', '""', "''", "'",'/'], '', $value);
        $value = preg_replace("/\x{200c}/u", ' ', $value);
        $value = preg_replace("/\x{200F}/u", '', $value);
        return preg_replace('/\s+/', ' ', $value);
    }
    private static function ClearAllSpecialChar($value): string
    {
        $value = str_replace(['>',',',",,","'","''",'"', '""', "''", '.', ':', '_', ']','/', '[', '|', '{', '}', '>', '<', "\u200C"], "", $value);
        $value = str_replace(["  ","   ","    ","     ","      ","       "], " ", $value);
        return preg_replace('/\s+/', ' ', $value);
    }
    public static function Number($num): ?int
    {
        return (int)$num;
    }
    public static function Char($value): ?string
    {
        $value = trim($value);
        $value = strtolower($value);
        $value = TextHelper::textArToFa($value);
        $value = TextHelper::numFaToEn($value);
        $value = self::PreventDefault($value);
        $value = self::ClearChar($value);
        if (function_exists("addslashes")) {
            $value = addslashes($value);
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
    public static function Url($value, $string = ""): string
    {
        $value = trim($value);
        $value = strtolower($value);
        $value = $value." ".$string ;
        $value = TextHelper::textArToFa($value);
        $value = TextHelper::numFaToEn($value);
        $value = self::PreventDefault($value);
        $value = self::ClearAllSpecialChar($value);
        $value = preg_replace('/\s+/', "-", $value);
        if (function_exists("addslashes")) {
            $value = addslashes($value);
        }
        $value = htmlspecialchars($value);
        return htmlentities($value);
    }
    public static function ImageName($value, $string = ""): string
    {
        $value = trim($value);
        $value = strtolower($value);
        $value = $value." ".$string ;
        $value = TextHelper::textArToFa($value);
        $value = TextHelper::numFaToEn($value);
        $value = self::PreventDefault($value);
        $value = self::ClearAllSpecialChar($value);
        $value = preg_replace('/\s+/', "-", $value);
        if (function_exists("addslashes")) {
            $value = addslashes($value);
        }
        $value = htmlspecialchars($value);
        return htmlentities($value);
    }
    public static function TextArea($value): ?string
    {
        $value = trim($value);
        $value = TextHelper::textArToFa($value);
        $value = TextHelper::numFaToEn($value);
        $value = str_replace("= ?", "", $value);
        $value = str_replace("script>", "", $value);
        $value = str_replace("<script", "", $value);
        $value = str_replace("<", "", $value);
        $value = str_replace(">", "", $value);
        if (function_exists("addslashes")) {
            $value = addslashes($value);
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
    public static function TextEditor($value): ?string
    {
        $value = trim($value);
        $value = strtolower($value);
        $value = TextHelper::textArToFa($value);
        $value = TextHelper::numFaToEn($value);
        $value = str_replace("= ?", "", $value);
        $value = str_replace("script>", "", $value);
        return str_replace("<script", "", $value);
    }
}

class TextHelper {
    public static function numEnToFa($string): array|string {
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);
        return str_replace($latin_num, $persian_num, $string);
    }

    public static function numFaToEn($string): array|string {
        $persian1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $persian2 = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $num = range(0, 9);
        $string = str_replace($persian1, $num, $string);
        return str_replace($persian2, $num, $string);
    }

    public static function textArToFa($text): array|string {
        $arabic_digits = array('ي', 'ك', 'ة', 'ۀ', 'هٔ', 'ؤ', 'أ');
        $persian_digits = array('ی', 'ک', 'ه', 'ه', 'ه', 'و', 'ا');
        return str_replace($arabic_digits, $persian_digits, $text);
    }
}

class Code{
    public static function Validate(string $code): string
    {
        $code = strtoupper(preg_replace('/[^A-Z0-9]/', '', $code));
        if (!self::validateCode($code)) {
            Base::ReturnError("کد ارسالی صحیح نیست.");
        }
        return $code;
    }
    public static function Generate(): string
    {
        do {
            $uniqueCode = self::generateUniqueCode();
        } while (!self::validateCode($uniqueCode) || self::isCodeExists($uniqueCode));
        self::storeCode($uniqueCode);
        return $uniqueCode;
    }
    private static function generateUniqueCode(): string
    {
        $code = '';
        try {
            for ($i = 0; $i < CODE_LENGTH; $i++) {
                $index = random_int(0, strlen(CODE_SALT) - 1);
                $code .= CODE_SALT[$index];
            }
        } catch (Exception $e) {
            Base::ReturnError("خطا در تولید کد منحصر به فرد: " . $e->getMessage());
        }

        // افزودن Salt به کد
        $saltedCode = $code . CODE_SALT;

        // محاسبه رقم کنترل با استفاده از Salt
        $checkDigit = self::calculateCheckDigit($saltedCode);

        return $code . $checkDigit;
    }
    private static function calculateCheckDigit(string $code): int
    {
        $sum = 0;
        $length = strlen($code);
        for ($i = 0; $i < $length; $i++) {
            $char = strtoupper($code[$i]);
            $value = ctype_digit($char) ? (int)$char : ord($char) - 55; // A=10, B=11, ..., Z=35
            $sum += ($i + 1) * $value;
        }
        return $sum % 11;
    }
    private static function validateCode(string $fullCode): bool
    {
        if (strlen($fullCode) !== (CODE_LENGTH + 1)) {
            return false;
        }
        $code = substr($fullCode, 0, CODE_LENGTH);
        $checkDigit = (int)substr($fullCode, CODE_LENGTH, 1);
        $saltedCode = $code . CODE_SALT;
        return self::calculateCheckDigit($saltedCode) === $checkDigit;
    }
    private static function isCodeExists(string $code): bool
    {
        $stmt = connect()->prepare("SELECT COUNT(*) FROM tbl_unique_codes WHERE unique_code = ?");
        $stmt->execute([$code]);
        return (bool)$stmt->fetchColumn();
    }
    private static function storeCode(string $code): void
    {
        $stmt = connect()->prepare("INSERT INTO tbl_unique_codes (unique_code) VALUES (?)");
        if (!$stmt->execute([$code])) {
            Base::ReturnError("خطا در ذخیره‌سازی کد منحصر به فرد.");
        }
    }
}

class PD {
    public static function Insert($Table_Name, $Insert_Data): bool|string
    {
        $db = connect();
        $fields = array_keys($Insert_Data);
        $key = empty($fields) ? "" : "`".implode("`,`", $fields)."`";
        $test = array_fill(0, count($fields), "?");
        $values = array_values($Insert_Data);
        $query = "INSERT INTO $Table_Name($key) VALUES (" . implode(",", $test) . ")";
        $stmt = $db->prepare($query);
        $stmt = $stmt->execute($values);
        if ($stmt) {
            return $db->lastInsertId();
        }
        return false;
    }
    public static function Update(string $Table_Name, array $data, string $Condition, array $params): bool|int
    {
        $db = connect();
        $Condition_string = '';
        if (!empty($Condition)) {
            if (substr(strtoupper(trim($Condition)), 0, 5) != 'WHERE') {
                $Condition_string = " WHERE " . $Condition;
            } else {
                $Condition_string = " " . trim($Condition);
            }
        }
        $query = "UPDATE " . $Table_Name . " SET ";
        $sets = array();
        foreach ($data as $column => $value) {
            $sets[] = "`" . $column . "` = ?";
        }
        $query .= implode(', ', $sets);
        $query .= $Condition_string;
        $array_values = array_values($data);
        $value = [...$array_values, ...$params];
        $stmt = $db->prepare($query);
        return $stmt->execute($value);
    }
    public static function Delete(string $Table_Name, string $Condition, array $params): bool {
        $db = connect();
        $Condition_string = '';
        if (!empty($Condition)) {
            if (substr(strtoupper(trim($Condition)), 0, 5) != 'WHERE') {
                $Condition_string = " WHERE " . $Condition;
            } else {
                $Condition_string = " " . trim($Condition);
            }
        }
        $query = "DELETE FROM " . $Table_Name . $Condition_string;
        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    }
    public static function SingleSelect(string $Table_Name, string $Condition,array $params, $What_Row = 'count(*)' )
    {
        $stmt = connect()->prepare("SELECT " . $What_Row . " FROM " . $Table_Name . " " . $Condition . "");
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result)){
            return $result[$What_Row];
        }else{
            return false;
        }
    }
    public static function RowSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*")
    {
        $stmt = connect()->prepare("SELECT " . $What_Row . " FROM " . $Table_Name . " " . $Condition . "");
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function MultiSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*"): bool|array {
        $stmt = connect()->prepare("SELECT " . $What_Row . " FROM " . $Table_Name . " " . $Condition . "");
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result?:[];
    }
    public static function GetProperties($Key)
    {
        $stmt = connect()->prepare("SELECT key_value  FROM tbl_properties WHERE key_name = ?");
        $stmt->execute(array($Key));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($result) && !empty($result)){
            return $result['key_value'];
        }else{
            return "CANT_FIND_KEY";
        }
    }
    public static function SetProperties($Key,$value): void {
        $Data = array(
            'key_value' => $value,
            'last_change' => time()
        );
        self::Update("tbl_properties", $Data, "WHERE key_name = ? " , array($Key));
    }
    public static function SetLoginCountAdmin(int $admin_id): void
    {
        self::Insert("tbl_login_count_admin", array(
            'login_time' => time(),
            'admin_id' => $admin_id,
            "ip" => $_SERVER['REMOTE_ADDR'],
        ));
    }
    public static function SetLoginCountUser(int $user_id): void
    {
        self::Insert("tbl_login_count_user", array(
            'login_time' => time(),
            'user_id' => $user_id,
            "ip" => $_SERVER['REMOTE_ADDR'],
        ));
    }
    public static function SetLogs(int $Log_Type, $Log_From, $Log_Details): void
    {
        self::Insert("tbl_logs", array(
            'log_type' => $Log_Type,
            'log_from' => $Log_From,
            'log_details' => $Log_Details,
            "ip" => $_SERVER['REMOTE_ADDR'],
            "user_agent" => $_SERVER['HTTP_USER_AGENT'],
        ));
    }
}

class Validate{
    public static function UUID($uuid): ?string
    {
        if (!preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $uuid)) {
            Base::ReturnError("UUID وارد شده معتبر نیست.");
        }
        return $uuid;
    }
    public static function Username($username): ?string
    {
        $username = Sanitizer::Char($username);
        if (!preg_match('/^[A-Za-z0-9_]{' . USERNAME_LENGTH . ',}$/', $username)) {
            Base::ReturnError("نام کاربری باید شامل حروف و اعداد انگلیسی باشد و حداقل ".USERNAME_LENGTH." کاراکتر داشته باشد.");
        }
        return $username;
    }
    public static function Password($password): bool|string
    {
        if (preg_match('/[^a-zA-Z0-9@#$!]/', $password)) {
            Base::ReturnError("پسورد ارسالی دارای کاکتر های مجاز نیست.");
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!]).+$/', $password)) {
            Base::ReturnError("پسورد ارسالی شامل موارد مجاز (@#$! a-z 1-9) نیست.");
        }
        if (strlen($password) < PASSWORD_LENGTH) {
            Base::ReturnError("پسورد ارسالی از تعداد ".PASSWORD_LENGTH." کارکتر مجاز برای رمز عبور کمتر است.");
        }
        return $password;
    }
    public static function Email($email): ?string
    {
        $email = Sanitizer::Char($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::ReturnError("فرمت ایمیل نامعتبر است");
        }

        $email_parts = explode("@", $email);
        if (strlen($email_parts[0]) < EMAIL_LENGTH) {
            Base::ReturnError("حداقل تعداد کاراکتر مجاز برای ایمیل ".EMAIL_LENGTH." کاراکتر است");
        }

        $email_domain = $email_parts[1];
        if (!in_array($email_domain, VALID_EMAIL_DOMAINS)) {
            Base::ReturnError("دامنه ایمیل وارد شده جز ایمیل‌های مجاز نمی‌باشد");
        }
        return $email;
    }
    public static function Mobile($mobile): null|string
    {
        $mobile = Sanitizer::Char($mobile);
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
            Base::ReturnError("ساختار وارد شده برای شماره موبایل صحیح نیست. شماره موبایل باید با 09 شروع شود و 11 رقم داشته باشد.");
        }
        return $mobile;
    }
    public static function Phone($phone): null|string
    {
        $phone = Sanitizer::Char($phone);
        if (!preg_match('/^0\d{10}$/', $phone)) {
            Base::ReturnError("ساختار وارد شده برای شماره تلفن صحیح نیست. شماره تلفن باید با کد شهر شروع شود و 11 رقم داشته باشد.");
        }
        $cityCode = substr($phone, 0, 3);
        if (!in_array($cityCode, VALID_CITY_CODES, true)) {
            Base::ReturnError("کد شهر وارد شده معتبر نیست. لطفاً کد شهر صحیح را وارد کنید.");
        }
        return $phone;
    }
    public static function NationalCode($code)
    {
        $code = Sanitizer::Char($code);
        if (!preg_match('/^[0-9]{10}$/', $code)) {
            Base::ReturnError("کد ملی وارد شده صحیح نیست");
        }
        for ($i = 0; $i < 10; $i++)
            if (preg_match('/^' . $i . '{10}$/', $code)) {
                Base::ReturnError("کد ملی وارد شده صحیح نیست");
            }

        for ($i = 0, $sum = 0; $i < 9; $i++)
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
            return $code;
        }
        Base::ReturnError("کد ملی وارد شده صحیح نیست");
    }
}

class Upload{
    public static function Image($file, $address, $folderName, $fileName): array|string
    {
        $errors = 1;
        if (isset($file)) {
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $file_type = $file['type'];
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            $extensions = array("jpeg", "jpg", "png",'mp4','mkv',"pdf");
            if (in_array($file_ext, $extensions) === false) {
                $errors = "فرمت فایل وارد شده مجاز نیست.";
            }

            $image = ["jpeg", "jpg", "png"];
            $video = ['mp4','mkv'];
            $doc = ['pdf'];

            $file_ext_type = 'image';
            if(in_array($file_ext,$video)){
                $file_ext_type = "video";
            }
            if(in_array($file_ext,$doc)){
                $file_ext_type = "doc";
            }

            //در صورت آپلود نشدن عکس به محمدودیت حجم سرور هم نگاه کنید
            //حجم فایل به بایت است
            if ($file_size > 2500000 ) {
                $errors = 'حجم فایل نباید بیشتر از 20mb باشد.';
                return array("status" => "failed", "url" => null, "message" => $errors, "type" => $file_ext_type);
            }

            if ($file_size < 6250 ) {
                $errors = 'حجم فایل نباید کمتر از 50kb باشد.';
                return array("status" => "failed", "url" => null, "message" => $errors, "type" => $file_ext_type);


            }


            if (!file_exists($address . $folderName)) {
                mkdir($address . $folderName, 0777, true);
            }

            $file_pattern = $address . $folderName . "/" . $fileName . ".*";// Assuming your files are named like profiles/bb-x62.foo, profiles/bb-x62.bar, etc.
            array_map("unlink", glob($file_pattern));

            $url = $address . $folderName . "/" . $fileName . "." . $file_ext;
            $returnUrl = $folderName . "/" . $fileName . "." . $file_ext;
            if ($errors === 1) {
                move_uploaded_file($file_tmp, $url);
                return array("status" => "success", "url" => $returnUrl, "message" => $errors, "type" => $file_ext_type);
            } else {
                return array("status" => "failed", "url" => null, "message" => $errors, "type" => $file_ext_type);
            }
        }
        return array("status" => "failed", "url" => null, "message" => $errors, "type" => "");
    }
    public static function MultiImage($file, $address, $folderName, $fileName): array|string
    {
        $errors = 1;
        $result = [];
        if (isset($file)) {
            for ($i = 0; $i < count($file['name']); $i++) {
                $fileNames = $fileName."-".rand(100000000,999999999);
                $file_name = $file['name'][$i];
                $file_size = $file['size'][$i];
                $file_tmp = $file['tmp_name'][$i];
                $file_type = $file['type'][$i];
                $file_ext = pathinfo($file['name'][$i], PATHINFO_EXTENSION);

                $extensions = array("jpeg", "jpg", "png",'mp4','mkv',"pdf");
                if (in_array($file_ext, $extensions) === false) {
                    $errors = "فرمت فایل وارد شده مجاز نیست. ";
                }

                $image = ["jpeg", "jpg", "png",];
                $video = ['mp4','mkv'];
                $doc = ['pdf'];

                $file_ext_type = 'image';
                if(in_array($file_ext,$video)){
                    $file_ext_type = "video";
                }
                if(in_array($file_ext,$doc)){
                    $file_ext_type = "doc";
                }

                self::CheckMaxSize($file_size,$file_ext_type);
                self::CheckMinSize($file_size,$file_ext_type);



                if (!file_exists($address . $folderName)) {
                    mkdir($address . $folderName, 0777, true);
                }

                $file_pattern = $address . $folderName . "/" . $fileNames . ".*";// Assuming your files are named like profiles/bb-x62.foo, profiles/bb-x62.bar, etc.
                array_map("unlink", glob($file_pattern));

                $url = $address . $folderName . "/" . $fileNames . "." . $file_ext;
                $returnUrl = $folderName . "/" . $fileNames . "." . $file_ext;
                if ($errors === 1) {
                    move_uploaded_file($file_tmp, $url);
                    $result[] = $returnUrl;

                }
            }
            return array("status" => "success", "result" => $result, "message" => $errors, "type" => $file_ext_type);
        }
        return array("status" => "failed", "url" => null, "message" => $errors, "type" => "");
    }
    public static function MultiSubImage($file,$sub, $address, $folderName, $fileName): array|string
    {
        $errors = 1;
        $result = [];
        if (isset($file)) {
            for ($i = 0; $i < count($file['name']); $i++) {
                $fileNames = $fileName."-".rand(100000000,999999999);
                $file_name = $file['name'][$i][$sub];
                $file_size = $file['size'][$i][$sub];
                $file_tmp = $file['tmp_name'][$i][$sub];
                $file_type = $file['type'][$i][$sub];
                //                $file_ext = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
                $file_ext = "jpg";

                $extensions = array("jpeg", "jpg", "png",'mp4','mkv',"pdf");
                if (in_array($file_ext, $extensions) === false) {
                    $errors = "فرمت فایل وارد شده مجاز نیست.";
                }

                $image = ["jpeg", "jpg", "png",];
                $video = ['mp4','mkv'];
                $doc = ['pdf'];

                $file_ext_type = 'image';
                if(in_array($file_ext,$video)){
                    $file_ext_type = "video";
                }
                if(in_array($file_ext,$doc)){
                    $file_ext_type = "doc";
                }

                self::CheckMaxSize($file_size,$file_ext_type);
                self::CheckMinSize($file_size,$file_ext_type);

                if (!file_exists($address . $folderName)) {
                    mkdir($address . $folderName, 0777, true);
                }

                $file_pattern = $address . $folderName . "/" . $fileNames . ".*";// Assuming your files are named like profiles/bb-x62.foo, profiles/bb-x62.bar, etc.
                array_map("unlink", glob($file_pattern));

                $url = $address . $folderName . "/" . $fileNames . "." . $file_ext;
                $returnUrl = $folderName . "/" . $fileNames . "." . $file_ext;
                if ($errors === 1) {
                    move_uploaded_file($file_tmp, $url);
                    $result[] = $returnUrl;

                }
            }
            return array("status" => "success", "result" => $result, "message" => $errors, "type" => $file_ext_type);
        }
        return array("status" => "failed", "url" => null, "message" => $errors, "type" => "");
    }

    private static function CheckMaxSize($file_size,$file_ext_type): void {
        if ($file_size > UPLOAD_MAX_FILE_SIZE) {
            $max_size_mb = UPLOAD_MAX_FILE_SIZE / (1024 * 1024);
            $errors = 'حجم فایل نباید بیشتر از '.$max_size_mb.'mb باشد.';
            http_response_code(400);
            die ( json_encode(array(
                "result" => null,
                "message" => $errors,
                "type" => $file_ext_type,
                "file_ext" => $file_ext_type,
                "file_size" => $file_size
            )));
        }
    }
    private static function CheckMinSize($file_size,$file_ext_type): void {
        if ($file_size < UPLOAD_MIN_FILE_SIZE) {
            $min_size_kb = UPLOAD_MIN_FILE_SIZE / (1024);
            $errors = 'حجم فایل نباید کمتر از '.$min_size_kb.'kb باشد.';
            http_response_code(400);
            die ( json_encode(array(
                "result" => null,
                "message" => $errors,
                "type" => $file_ext_type,
                "file_ext" => $file_ext_type,
                "file_size" => $file_size
            )));
        }

    }

}


