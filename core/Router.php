<?php
class Router
{
    private static array $routes = [];

    // تشخیص خودکار base path
    private static function getBasePath(): string
    {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return rtrim($scriptName, '/');
    }

    // ثبت مسیر با پشتیبانی از متد، مسیر، اکشن و middleware
    public static function Route(string $method, string $path, string|array $action, array $middlewares = []): void
    {
        if(!is_callable($action)) self::sendNotFoundFunction();
        $pathRegex = preg_replace('/(:\w+)/', '(\w+)', $path); // تبدیل مسیر به regex
        self::$routes[] = [
            'method' => $method,
            'path' => $pathRegex,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    // اجرای middleware با پشتیبانی از پارامترها
    private static function runMiddlewares(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            if (is_array($middleware)) {
                $method = array_shift($middleware); // دریافت نام متد

                // بررسی اینکه متد یک تابع معتبر است
                if (is_callable($method)) {
                    if (!call_user_func_array($method, $middleware)) {
                        return false; // اگر میدل‌ور موفقیت‌آمیز نبود، مسیر ادامه پیدا نکند
                    }
                } else {
                    throw new Exception("میدل‌ور معتبر نیست: " . json_encode($method));
                }
            } else {
                if (!call_user_func($middleware)) {
                    return false; // اگر میدل‌ور موفقیت‌آمیز نبود، مسیر ادامه پیدا نکند
                }
            }
        }
        return true;
    }


    // مدیریت درخواست‌ها
    public static function handleRequest(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = self::getBasePath();
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        $path = rtrim($path, '/');
        $method = $_SERVER['REQUEST_METHOD'];
        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match('#^' . $route['path'] . '$#', $path, $matches)) {
                array_shift($matches);

                // اجرای middleware
                if (!self::runMiddlewares($route['middlewares'])) {
                    self::sendForbidden();
                    return;
                }

                // اجرای اکشن و دریافت خروجی
                $result = call_user_func_array($route['action'], $matches);

                if ($result) {
                    echo $result;
                }
                return;
            }
        }
        self::sendNotFound();
    }

    // ارسال خطای 404
    private static function sendNotFoundFunction(): void
    {
        Base::ReturnError("توابع مورد نظر یافت نشد.");
    }

    private static function sendNotFound(): void
    {
        Base::ReturnError("صفحه پیدا نشد.", 404);
    }

    // ارسال خطای 403
    private static function sendForbidden(): void
    {
        Base::ReturnError("ممنوع.", 403);
    }
}
