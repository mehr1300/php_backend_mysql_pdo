<?php

use JetBrains\PhpStorm\NoReturn;

class Router
{
    private static array $routes = [];

    /**
     * تشخیص خودکار base path
     */
    private static function getBasePath(): string
    {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return rtrim($scriptName, '/');
    }

    /**
     * ثبت مسیر با پشتیبانی از احراز هویت
     *
     * @param string       $method       متد درخواستی (GET, POST, ...)
     * @param string       $path         مسیر (مثلاً "/admin/products/add")
     * @param string|array $action       اکشن (مثلاً [AdminController::class, 'addProducts'])
     * @param string|null  $requiredRole نقش موردنیاز (مثلاً "admin" یا null برای عدم نیاز به نقش)
     */
    public static function Route(string $method, string $path, string|array $action, ?string $requiredRole = null): void
    {
        $pathRegex = preg_replace('/(:\w+)/', '([a-zA-Z0-9\-]+)', $path);
        self::$routes[] = [
            'method'       => $method,
            'path'         => $pathRegex,
            'action'       => $action,
            'requiredRole' => $requiredRole
        ];
    }

    /**
     * مدیریت درخواست‌ها و اجرای مسیریابی
     */
    public static function handleRequest(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = self::getBasePath();
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        $path = rtrim($path, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // جستجو در بین مسیرهای ثبت شده
        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match('#^' . $route['path'] . '$#', $path, $matches)) {
                array_shift($matches); // حذف المان صفرم که کل استرینگ مچ‌شده است

                // اگر مسیر نیاز به نقش خاص دارد، آن را بررسی می‌کنیم
                if ($route['requiredRole'] && !Auth::CheckAuth($route['requiredRole'])) {
                    // اگر کاربر نقش یا توکن مناسب نداشته باشد، 401 می‌دهیم
                    self::sendUnauthorized();
                }

                // در این مرحله مسیر مچ شده است. حالا چک می‌کنیم اکشن قابل فراخوانی است یا خیر.
                if (!is_callable($route['action'])) {
                    // اگر اکشن (متد کنترلر) وجود نداشت، خطای 404 می‌دهیم
                    self::sendNotFoundFunction();
                }

                // اجرای اکشن
                $result = call_user_func_array($route['action'], $matches);

                // اگر اکشن چیزی برگرداند، چاپش می‌کنیم
                if ($result) {
                    echo $result;
                }
                return;
            }
        }
        self::sendNotFound();
    }

    /**
     * ارسال خطای 404 در صورت نیافتن تابع اکشن
     */
    #[NoReturn]
    private static function sendNotFoundFunction(): void
    {
        die(Base::SetError("توابع مورد نظر یافت نشد.", 404));
    }

    /**
     * ارسال خطای 404 در صورت نیافتن مسیر
     */
    #[NoReturn]
    private static function sendNotFound(): void
    {
        die(Base::SetError("مسیر مورد نظر پیدا نشد.", 404));
    }

    /**
     * ارسال خطای 401 (Unauthorized)
     */
    #[NoReturn]
    private static function sendUnauthorized(): void
    {
        die(Base::SetError("شما اجازه دسترسی به این بخش را ندارید. لطفاً وارد شوید.", 401));
    }

    /**
     * ارسال خطای 403 (Forbidden)
     * (در صورت نیاز اگر نقش نامعتبر بود)
     */
    #[NoReturn]
    private static function sendForbidden(): void
    {
        die(Base::SetError("دسترسی غیرمجاز. نقش شما برای این مسیر کافی نیست.", 403));
    }
}
