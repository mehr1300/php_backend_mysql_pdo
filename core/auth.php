<?php

class Auth
{
    /**
     * این متد بررسی می‌کند که آیا توکن معتبر است یا خیر.
     * در صورت معتبر بودن توکن، نقش و تاریخ انقضا نیز بررسی می‌شود.
     */
    public static function CheckAuth(string $requiredRole = null): bool
    {
        $token = self::GetTokenFromRequest();
        if (!$token) {
            die(Base::SetError("توکن ارسال نشده است. لطفاً ابتدا وارد سیستم شوید.", 401));
        }
        $tokenContents = self::ParseToken($token);
        if ($tokenContents === false) {
            die(Base::SetError("توکن نامعتبر یا منقضی شده. لطفاً مجدداً وارد شوید.", 401));
        }

        if (isset($tokenContents['token_expire_date']) && $tokenContents['token_expire_date'] <= time()) {
            die(Base::SetError("توکن منقضی شده است. لطفاً  مجدد وارد شوید.", 401));
        }

        if ($requiredRole && (!isset($tokenContents['role']) || $tokenContents['role'] !== $requiredRole)) {
            die(Base::SetError("دسترسی غیرمجاز. شما اجازه دسترسی به این مسیر را ندارید.", 403));
        }

        $GLOBALS[TOKEN_NAME] = $tokenContents;

        return true;
    }

    /**
     * گرفتن توکن از هدر درخواست
     */
    private static function GetTokenFromRequest(): string|false
    {
        $headers = apache_request_headers();
        if (isset($headers['Cookie'])) {
            $cookie = str_replace(" ", "", $headers['Cookie']);
            $headerCookies = explode(';', $cookie);
            $cookies = [];
            foreach ($headerCookies as $itm) {
                list($key, $val) = explode('=', $itm, 2);
                $cookies[$key] = $val;
            }
            if (isset($cookies[TOKEN_NAME])) {
                return $cookies[TOKEN_NAME];
            }
        }
        return false;
    }

    /**
     * پارس کردن توکن به آرایه
     */
    private static function ParseToken(string $token): array|false
    {
        try {
            $raw_token = crypto::decryptThis($token);
            $raw_token = explode("Ⓔ", $raw_token)[0]; // حذف بخش زمان
            if (!$raw_token) return false;
            $contents = [];
            $token_lines = explode("Ⓐ", $raw_token);
            foreach ($token_lines as $token_line) {
                if (str_contains($token_line, "Ⓑ")) {
                    $token_line_contents = explode("Ⓑ", $token_line);
                    $key = $token_line_contents[0];
                    $val = $token_line_contents[1] ?? null;
                    $contents[$key] = $val;
                }
            }

            return $contents;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * برای ساخت توکن جدید
     */
    public static function GenerateToken(array $token_variables): string
    {
        if (!isset($token_variables['role'])) {
            die(Base::SetError("خطا در فرآیند احراز هویت: نقش کاربر مشخص نیست", 401));
        }
        if (!isset($token_variables['token_expire_date'])) {
            $token_variables['token_expire_date'] = time() + TOKEN_EXPIRE_TIME;
        }
        $content_string = "";
        foreach ($token_variables as $content_key => $content_value) {
            $content_string .= $content_key . "Ⓑ" . $content_value . "Ⓐ";
        }
        return crypto::encryptThis($content_string . "Ⓔ" . time());
    }
}

