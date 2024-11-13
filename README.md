# پروژه بک‌اند کامل PHP با پایگاه داده MySQL

این پروژه یک بک‌اند کامل بر پایه PHP است که از پایگاه داده MySQL و اتصال PDO برای تعامل با دیتابیس استفاده می‌کند.

## پیش‌نیازها

- **PHP** نسخه ۷ یا بالاتر
- **MySQL** نسخه ۵.۶ یا بالاتر
- **XAMPP** یا هر وب سرور محلی دیگر

## نصب

1. **کلون یا دانلود پروژه:**

   ابتدا پروژه را کلون کرده یا فایل‌های آن را دانلود کنید.

   ```bash
   git clone https://github.com/mehr1300/php_backend_mysql_pdo.git
   ```

2. **انتقال فایل‌ها به پوشه وب سرور:**

   فایل‌های پروژه را در پوشه `htdocs` نرم‌افزار **XAMPP** یا پوشه مربوط به وب سرور خود قرار دهید.

   3. **تنظیمات پایگاه داده:**

      - فایل `config-example.php` را به `config.php` تغییر نام دهید.
      - فایل `config.php` را باز کرده و اطلاعات پایگاه داده خود را وارد کنید:

        ```php
        <?php
        const SERVERNAME_DB = "localhost";
        const USERNAME_DB = "Database_username";
        const PASSWORD_DB = "Database_password";
        const DATABASE_DB = "Database_database";
        ```
4. **ایجاد پایگاه داده:**

    - فایل mysql موجود در پوشه `database` را در محیط مدیریت پایگاه داده (مثل phpMyAdmin) اجرا کنید تا جداول مورد نیاز ایجاد شوند.

## استفاده

### مثال‌ها

#### مثال استفاده از کلاس `PD`

در ادامه چند مثال از نحوه دریافت اطلاعات از پایگاه داده با استفاده از کلاس `PD` آورده شده است.

#### جدول `tbl_user`

| id | firstname | lastname | gender | age |
|:---:|:-------------:|:--------:|:------:|:---:|
| 1   | John          |   Doe    |   M    | 19  |
| 2   | Bob           |  Black   |   M    | 41  |
| 3   | Zoe           |   Chan   |   F    | 20  |
| 4   | Kona          |   Khan   |   M    | 14  |
| 5   | Kader         |   Khan   |   M    | 56  |

#### دریافت تمام اطلاعات یک جدول

```php
<?php
// دریافت تمام اطلاعات از جدول tbl_user
$persons = PD::MultiSelect("tbl_user", "", []);
```

#### دریافت اطلاعات با شرط

```php
<?php
// دریافت کاربران با جنسیت مذکر
$malePersons = PD::MultiSelect("tbl_user", "WHERE gender = ?", ["M"]);

// دریافت شناسه و نام کاربران با جنسیت مذکر
$maleNames = PD::MultiSelect("tbl_user", "WHERE gender = ?", ["M"], "id, firstname");
```

#### دریافت یک سطر اطلاعات

این متد تنها یک سطر را برمی‌گرداند.

```php
<?php
// دریافت اطلاعات کاربری با شناسه 1
$user = PD::RowSelect("tbl_user", "WHERE id = ?", [1]);
```

##### نتیجه

|  id | firstname | lastname | gender | age |
|:---:|:----:|:-------------:|:-----:|:---:|
| 1   | John | Doe           | M     | 19  |

#### دریافت یک مقدار خاص

این متد فقط یک مقدار را برمی‌گرداند.

```php
<?php
// دریافت نام کاربری با شناسه 1
$firstname = PD::SingleSelect("tbl_user", "WHERE id = ?", [1], "firstname");
```

##### نتیجه

| firstname  |
|:----:|
| John |


#### کلاس `Sanitizer`

کلاس `Sanitizer` برای تمیز کردن و اعتبارسنجی ورودی‌های کاربر استفاده می‌شود تا از ورود داده‌های مخرب جلوگیری کند.

```php
<?php
class Sanitizer
{
    public static function Number($num): ?int   {//...//}
    public static function Char($value): ?string  {//...// }
    public static function Url($value, $string = ""): string{//...//}
    public static function ImageName($value, $string = ""): string {//...//}
    public static function TextArea($value): ?string {//...//}
    public static function TextEditor($value): ?string {//...//}
}
```

#### مثال استفاده از کلاس `Sanitizer`

```php
<?php
// استفاده از متد Number برای تمیز کردن ورودی عددی
$userId = Sanitizer::Number($_GET['id']);

// استفاده از متد Char برای تمیز کردن ورودی رشته‌ای
$username = Sanitizer::Char($_POST['username']);

// استفاده از متد Url برای ساخت URL امن و استاندارد
$url = Sanitizer::Url($_POST['title']);
//input => this is a url for my post
// output => this_is_a_url_for_my_post

// استفاده از متد Url برای ساخت URL امن و استاندارد به همراه افرودن رشته به انتها
$url2 = Sanitizer::Url($_POST['title'],"special_string");
//input => this is a url for my post
// output => this_is_a_url_for_my_post_special_string

// استفاده از متد ImageName برای ایجاد نام فایل تصویر امن و استاندارد
$imageName = Sanitizer::ImageName($_FILES['image']['name']);
//input => this is a image name
// output => this_is_a_image_name

// استفاده از متد ImageName برای ایجاد نام فایل تصویر امن و استاندارد به همراه اقزودن رشته به انتها
$imageName = Sanitizer::ImageName($_FILES['image']['name'],"special_string");
//input => this is a image name
// output => this_is_a_image_name_special_string

// استفاده از متد TextArea برای تمیز کردن محتوای متن چندخطی
$comment = Sanitizer::TextArea($_POST['comment']);

// استفاده از متد TextEditor برای تمیز کردن محتوای ویرایشگر متن
$content = Sanitizer::TextEditor($_POST['content']);
```

#### توضیحات

- **Sanitizer::Number($num):** ورودی را به عدد صحیح تبدیل می‌کند.
- **Sanitizer::Char($value):** ورودی رشته‌ای را تمیز کرده و از کاراکترهای مخرب پاک می‌کند.
- **Sanitizer::Url($value, $string):** ورودی را پاک میکند و مناسب سازی برای استفاده به عنوان آدرس.
- **Sanitizer::ImageName($value, $string):** ورودی را پاک میکند و مناسب سازی برای استفاده به عنوان اسم تصاویر.
- **Sanitizer::TextArea($value):** محتوای وارد شده در تکست اریا را تمیز می‌کند.
- **Sanitizer::TextEditor($value):** محتوای وارد شده از طریق ویرایشگر متن را تمیز می‌کند.

 
## مجوز

این پروژه تحت مجوز MIT منتشر شده است. برای اطلاعات بیشتر، فایل [LICENSE](LICENSE) را مشاهده کنید.

## پشتیبانی

در صورت وجود هرگونه سوال یا مشکل، لطفاً یک Issue در مخزن گیت‌هاب پروژه ثبت کنید

 

## نویسنده

- **مهر** - [GitHub](https://github.com/mehr1300)
