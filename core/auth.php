<?php

use JetBrains\PhpStorm\NoReturn;

class auth
{
    public static function CheckAuth(string $requiredRole = null): bool
    {
        $token = self::GetTokenFromRequest();
        $token_contents = $token ? self::ParseToken($token) : false;
        if ($token_contents === false) Base::ReturnError("جهت دسترسی به این بخش ابتدا وارد سیستم شوید.",401);
        if ($requiredRole && (!isset($token_contents['role']) || $token_contents['role'] !== $requiredRole)) {
            Base::ReturnError("دسترسی غیرمجاز.", 403);
        }
        return true;
    }
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
            if (is_array($cookies) && array_key_exists(TOKEN_NAME, $cookies)) {
                return $cookies[TOKEN_NAME];
            }
        }
        return false;
    }
    private static function ParseToken(string $token): array|false
    {
        try {
            $raw_token = crypto::decryptThis($token);
            $raw_token = explode("Ⓔ", $raw_token)[0];
            if (!$raw_token) return false;
            $contents = [];
            $token_lines = explode("Ⓐ", $raw_token);
            foreach ($token_lines as $token_line) {
                if (str_contains($token_line, "Ⓑ")) {
                    $token_line_contents = explode("Ⓑ", $token_line);
                    $contents[$token_line_contents[0]] = $token_line_contents[1];
                }
            }
            if ($contents['token_expire_date'] <= time() || !isset($contents['token_expire_date'])) {
                return false;
            }
            return $contents;
        } catch (Exception $e) {
            return false;
        }
    }
    public static function GenerateToken(array $token_variables): string
    {
        $content_string = "";
        if (!key_exists("role", $token_variables)) {
            Base::ReturnError("خطا در فرآیند احراز هویت",401);
        }
        if (!key_exists("token_expire_date", $token_variables)) {
            $token_variables['token_expire_date'] = time() + TOKEN_EXPIRE_TIME;
        }
        foreach ($token_variables as $content_key => $content_value) {
            $content_string = $content_string . $content_key . "Ⓑ" . $content_value . "Ⓐ";
        }
        return crypto::encryptThis($content_string . "Ⓔ" . time());
    }

}
