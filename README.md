# Complete PHP Backend Project with MySQL Database

[**راهنمای فارسی**](#راهنمای-فارسی)

This project is a full-featured backend built with PHP, utilizing a MySQL database and PDO for secure database interactions.

## Prerequisites

- **PHP** version 7 or higher
- **MySQL** version 5.6 or higher
- **XAMPP** or any other local web server

## Installation

1. **Clone or Download the Project:**

   Begin by cloning the project or downloading its files.

   ```bash
   git clone https://github.com/mehr1300/php_backend_mysql_pdo.git
   ```

2. **Move Files to the Web Server Directory:**

   Place the project files in the `htdocs` folder of **XAMPP** or the relevant directory of your web server.

3. **Database Configuration:**

   - Rename the file `config-example.php` to `config.php`.
   - Open `config.php` and enter your database information:

     ```php
     <?php
     const SERVERNAME_DB = "localhost";
     const USERNAME_DB = "Database_username";
     const PASSWORD_DB = "Database_password";
     const DATABASE_DB = "Database_database";
     ```

4. **Creating the Database:**

   - Execute the MySQL file located in the `database` folder using a database management tool (like phpMyAdmin) to create the required tables.

5. **Server Configuration for Routing:**

   - **Windows Server:**

      - Create a `web.config` file in the root directory of your project and insert the following content:

        ```xml
        <?xml version="1.0" encoding="UTF-8"?>
        <configuration>
          <system.webServer>
            <rewrite>
              <rules>
                <rule name="Redirect all to index.php" stopProcessing="true">
                  <match url=".*" />
                  <conditions>
                    <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                    <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                  </conditions>
                  <action type="Rewrite" url="interfaces/site/index.php" />
                </rule>
              </rules>
            </rewrite>
          </system.webServer>
        </configuration>
        ```

   - **Linux and cPanel:**

      - Create a `.htaccess` file in the root directory of your project and insert the following content:

        ```apache
        RewriteEngine On
 
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule .* interfaces/site/index.php [L]
        ```

   **Explanation:**

   These configurations are essential for enabling URL routing in your application. They redirect all incoming requests that do not match an existing file or directory to `interfaces/site/index.php`, allowing centralized route management.

## Database Tables

This project requires the creation of three tables in your MySQL database to function correctly. Execute the following SQL statements to create them:

1. **tbl_logs**

   This table is used to store system logs and reports.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_logs` (
     `log_id` int NOT NULL AUTO_INCREMENT,
     `log_type` int NULL DEFAULT NULL,
     `log_from` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `log_details` longtext CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     `ip` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     PRIMARY KEY (`log_id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

2. **tbl_properties**

   This table stores system settings and configurations.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_properties` (
     `id` int NOT NULL AUTO_INCREMENT,
     `last_change` varchar(11) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_category` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_name` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_value` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     PRIMARY KEY (`id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

3. **tbl_user_meta_data**

   This table stores metadata and user request information.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_user_meta_data` (
     `meta_id` int NOT NULL AUTO_INCREMENT,
     `request_method` varchar(5) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `user_ip` varchar(20) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `meta_type` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `meta_info` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `meta_create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`meta_id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

**Important Notes:**

- **Executing the Scripts:** Run the above SQL statements in your database management tool (like phpMyAdmin) to create the necessary tables.
- **Table Names:** Ensure that the table names (`tbl_logs`, `tbl_properties`, `tbl_user_meta_data`) match exactly as used in the project to avoid any issues.
- **Purpose of Tables:** These tables are critical for logging system activities, managing settings, and storing user metadata, ensuring the framework functions properly.

## Usage

### The `PD` Class

```php
<?php
class PD {
    public static function Insert($Table_Name, $Insert_Data): bool|string {//...//}
    public static function Update(string $Table_Name, array $data, string $Condition, array $params): bool|int {//...//}
    public static function Delete(string $Table_Name, string $Condition, array $params): bool {//...//}
    public static function SingleSelect(string $Table_Name, string $Condition,array $params, $What_Row = 'count(*)') {//...//}
    public static function RowSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*") {//...//}
    public static function MultiSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*"): bool|array  {//...//}
}
```

#### Example Usage of the `PD` Class

Below are some examples of how to retrieve data from the database using the `PD` class.

#### `tbl_user` Table

| id | firstname | lastname | gender | age |
|----|-----------|----------|--------|-----|
| 1  | John      | Doe      | M      | 19  |
| 2  | Bob       | Black    | M      | 41  |
| 3  | Zoe       | Chan     | F      | 20  |
| 4  | Kona      | Khan     | M      | 14  |
| 5  | Kader     | Khan     | M      | 56  |

#### Retrieving All Records from a Table

```php
<?php
// Retrieve all records from the tbl_user table
$persons = PD::MultiSelect("tbl_user", "", []);
```

#### Retrieving Data with Conditions

```php
<?php
// Retrieve male users
$malePersons = PD::MultiSelect("tbl_user", "WHERE gender = ?", ["M"]);

// Retrieve ID and firstname of male users
$maleNames = PD::MultiSelect("tbl_user", "WHERE gender = ?", ["M"], "id, firstname");
```

#### Retrieving a Single Row

This method returns only one row.

```php
<?php
// Retrieve user information with id 1
$user = PD::RowSelect("tbl_user", "WHERE id = ?", [1]);
```

##### Result

| id | firstname | lastname | gender | age |
|----|-----------|----------|--------|-----|
| 1  | John      | Doe      | M      | 19  |

#### Retrieving a Single Value

This method returns only one value.

```php
<?php
// Retrieve the firstname of the user with id 1
$firstname = PD::SingleSelect("tbl_user", "WHERE id = ?", [1], "firstname");
```

##### Result

| firstname |
|-----------|
| John      |

#### Descriptions

- **`PD::Insert($Table_Name, $Insert_Data):`** Adds a new record to the table.
- **`PD::Update(string $Table_Name, array $data, string $Condition, array $params):`** Updates existing records in the table.
- **`PD::Delete(string $Table_Name, string $Condition, array $params):`** Deletes records from the table.
- **`PD::SingleSelect(string $Table_Name, string $Condition, array $params, $What_Row = 'count(*)'):`** Directly retrieves a single value from the table.
- **`PD::RowSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*"):`** Retrieves a single row from the table.
- **`PD::MultiSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*"):`** Retrieves multiple records from the table.

---

### The `Sanitizer` Class

The `Sanitizer` class is used for cleaning and validating user inputs to prevent malicious data from entering the system.

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

#### Example Usage of the `Sanitizer` Class

```php
<?php
// Using the Number method to sanitize numeric input
$userId = Sanitizer::Number($_GET['id']);

// Using the Char method to sanitize string input
$username = Sanitizer::Char($_POST['username']);

// Using the Url method to create a safe and standard URL
$url = Sanitizer::Url($_POST['title']);
// Input => this is a url for my post
// Output => this_is_a_url_for_my_post

// Using the Url method with an additional string appended
$url2 = Sanitizer::Url($_POST['title'], "special_string");
// Input => this is a url for my post
// Output => this_is_a_url_for_my_post_special_string

// Using the ImageName method to create a safe and standard image file name
$imageName = Sanitizer::ImageName($_FILES['image']['name']);
// Input => this is an image name
// Output => this_is_an_image_name

// Using the ImageName method with an additional string appended
$imageName = Sanitizer::ImageName($_FILES['image']['name'], "special_string");
// Input => this is an image name
// Output => this_is_an_image_name_special_string

// Using the TextArea method to sanitize multiline text input
$comment = Sanitizer::TextArea($_POST['comment']);

// Using the TextEditor method to sanitize rich text editor input
$content = Sanitizer::TextEditor($_POST['content']);
```

#### Descriptions

- **`Sanitizer::Number($num):`** Converts the input to an integer.
- **`Sanitizer::Char($value):`** Sanitizes string input by removing harmful characters.
- **`Sanitizer::Url($value, $string):`** Cleans the input and formats it for use as a URL.
- **`Sanitizer::ImageName($value, $string):`** Cleans the input and formats it for use as an image filename.
- **`Sanitizer::TextArea($value):`** Sanitizes text input from a textarea.
- **`Sanitizer::TextEditor($value):`** Sanitizes content from a rich text editor.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Support

If you have any questions or issues, please open an issue on the project's GitHub repository.

## Author

- **Mehr** - [GitHub](https://github.com/mehr1300)

---

[GitHub Repository](https://github.com/mehr1300/php_backend_mysql_pdo)

---

# راهنمای فارسی

این پروژه یک بک‌اند کامل بر پایه PHP است که از پایگاه داده MySQL و اتصال PDO برای تعامل امن با دیتابیس استفاده می‌کند.

## پیش‌نیازها

- **PHP** نسخه 7 یا بالاتر
- **MySQL** نسخه 5.6 یا بالاتر
- **XAMPP** یا هر وب‌سرور محلی دیگر

## نصب

1. **کلون یا دانلود پروژه:**

   ابتدا پروژه را کلون کرده یا فایل‌های آن را دانلود کنید.

   ```bash
   git clone https://github.com/mehr1300/php_backend_mysql_pdo.git
   ```

2. **انتقال فایل‌ها به پوشه وب‌سرور:**

   فایل‌های پروژه را در پوشه `htdocs` نرم‌افزار **XAMPP** یا پوشه مربوط به وب‌سرور خود قرار دهید.

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

   - فایل MySQL موجود در پوشه `database` را در محیط مدیریت پایگاه داده (مثل phpMyAdmin) اجرا کنید تا جداول مورد نیاز ایجاد شوند.

5. **پیکربندی سرور برای روتینگ:**

   - **ویندوز سرور:**

      - یک فایل `web.config` در پوشه اصلی پروژه ایجاد کرده و محتوای زیر را در آن قرار دهید:

        ```xml
        <?xml version="1.0" encoding="UTF-8"?>
        <configuration>
          <system.webServer>
            <rewrite>
              <rules>
                <rule name="Redirect all to index.php" stopProcessing="true">
                  <match url=".*" />
                  <conditions>
                    <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                    <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                  </conditions>
                  <action type="Rewrite" url="interfaces/site/index.php" />
                </rule>
              </rules>
            </rewrite>
          </system.webServer>
        </configuration>
        ```

   - **لینوکس و سی‌پنل:**

      - یک فایل `.htaccess` در پوشه اصلی پروژه ایجاد کرده و محتوای زیر را در آن قرار دهید:

        ```apache
        RewriteEngine On
 
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule .* interfaces/site/index.php [L]
        ```

   **توضیح:**

   این پیکربندی‌ها برای فعال‌سازی روتینگ URL در برنامه شما ضروری هستند. آن‌ها تمام درخواست‌های ورودی که با فایل یا پوشه‌ای موجود مطابقت ندارند را به `interfaces/site/index.php` هدایت می‌کنند، که به شما امکان مدیریت مرکزی مسیرها را می‌دهد.

## جداول پایگاه داده

این پروژه برای عملکرد صحیح نیاز به ایجاد سه جدول در پایگاه داده MySQL شما دارد. برای ایجاد آن‌ها، دستورات SQL زیر را اجرا کنید:

1. **tbl_logs**

   این جدول برای ذخیره‌سازی لاگ‌ها و گزارش‌های سیستم استفاده می‌شود.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_logs` (
     `log_id` int NOT NULL AUTO_INCREMENT,
     `log_type` int NULL DEFAULT NULL,
     `log_from` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `log_details` longtext CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     `ip` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     PRIMARY KEY (`log_id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

2. **tbl_properties**

   این جدول برای ذخیره‌سازی تنظیمات و پیکربندی‌های سیستم استفاده می‌شود.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_properties` (
     `id` int NOT NULL AUTO_INCREMENT,
     `last_change` varchar(11) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_category` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_name` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `key_value` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     PRIMARY KEY (`id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

3. **tbl_user_meta_data**

   این جدول برای ذخیره‌سازی اطلاعات متادیتا و درخواست‌های کاربران استفاده می‌شود.

   ```sql
   CREATE TABLE `nt_backend_db`.`tbl_user_meta_data` (
     `meta_id` int NOT NULL AUTO_INCREMENT,
     `request_method` varchar(5) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `user_ip` varchar(20) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `meta_type` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
     `meta_info` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
     `meta_create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`meta_id`) USING BTREE
   ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;
   ```

**نکات مهم:**

- **اجرای کدها:** برای ایجاد این جداول، دستورات SQL فوق را در ابزار مدیریت پایگاه داده خود (مثل phpMyAdmin) اجرا کنید.
- **تطابق نام‌ها:** اطمینان حاصل کنید که نام جداول (`tbl_logs`, `tbl_properties`, `tbl_user_meta_data`) با نام‌هایی که در پروژه استفاده می‌شوند، مطابقت داشته باشند تا از بروز هرگونه مشکل جلوگیری شود.
- **اهمیت جداول:** این جداول برای عملکرد صحیح فریم‌ورک ضروری هستند و اطلاعات حیاتی مانند لاگ‌ها، تنظیمات و متادیتا را ذخیره می‌کنند.

## استفاده

### کلاس `PD`

```php
<?php
class PD {
    public static function Insert($Table_Name, $Insert_Data): bool|string {//...//}
    public static function Update(string $Table_Name, array $data, string $Condition, array $params): bool|int {//...//}
    public static function Delete(string $Table_Name, string $Condition, array $params): bool {//...//}
    public static function SingleSelect(string $Table_Name, string $Condition,array $params, $What_Row = 'count(*)') {//...//}
    public static function RowSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*") {//...//}
    public static function MultiSelect(string $Table_Name,string $Condition,array $params, $What_Row = "*"): bool|array  {//...//}
}
```

#### مثال استفاده از کلاس `PD`

در ادامه چند مثال از نحوه دریافت اطلاعات از پایگاه داده با استفاده از کلاس `PD` آورده شده است.

#### جدول `tbl_user`

| id | firstname | lastname | gender | age |
|----|-----------|----------|--------|-----|
| 1  | John      | Doe      | M      | 19  |
| 2  | Bob       | Black    | M      | 41  |
| 3  | Zoe       | Chan     | F      | 20  |
| 4  | Kona      | Khan     | M      | 14  |
| 5  | Kader     | Khan     | M      | 56  |

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

| id | firstname | lastname | gender | age |
|----|-----------|----------|--------|-----|
| 1  | John      | Doe      | M      | 19  |

#### دریافت یک مقدار خاص

این متد فقط یک مقدار را برمی‌گرداند.

```php
<?php
// دریافت نام کاربری با شناسه 1
$firstname = PD::SingleSelect("tbl_user", "WHERE id = ?", [1], "firstname");
```

##### نتیجه

| firstname |
|-----------|
| John      |

#### توضیحات

- **`PD::Insert($Table_Name, $Insert_Data):`** افزودن مقدار جدید به جدول.
- **`PD::Update(string $Table_Name, array $data, string $Condition, array $params):`** بروزرسانی جدول.
- **`PD::Delete(string $Table_Name, string $Condition, array $params):`** حذف اطلاعات از جدول.
- **`PD::SingleSelect(string $Table_Name, string $Condition, array $params, $What_Row = 'count(*)'):`** دریافت مستقیم یک مقدار از جدول.
- **`PD::RowSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*"):`** دریافت یک سطر از جدول.
- **`PD::MultiSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*"):`** دریافت تمام اطلاعات یک جدول.

---

### کلاس `Sanitizer`

کلاس `Sanitizer` برای تمیز کردن و اعتبارسنجی ورودی‌های کاربر استفاده می‌شود تا از ورود داده‌های مخرب جلوگیری شود.

```php
<?php
class Sanitizer
{
    public static function Number($num): ?int {//...//}
    public static function Char($value): ?string {//...//}
    public static function Url($value, $string = ""): string {//...//}
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
// ورودی => this is a url for my post
// خروجی => this_is_a_url_for_my_post

// استفاده از متد Url با افزودن رشته‌ای به انتها
$url2 = Sanitizer::Url($_POST['title'], "special_string");
// ورودی => this is a url for my post
// خروجی => this_is_a_url_for_my_post_special_string

// استفاده از متد ImageName برای ایجاد نام فایل تصویر امن و استاندارد
$imageName = Sanitizer::ImageName($_FILES['image']['name']);
// ورودی => this is an image name
// خروجی => this_is_an_image_name

// استفاده از متد ImageName با افزودن رشته‌ای به انتها
$imageName = Sanitizer::ImageName($_FILES['image']['name'], "special_string");
// ورودی => this is an image name
// خروجی => this_is_an_image_name_special_string

// استفاده از متد TextArea برای تمیز کردن محتوای متن چندخطی
$comment = Sanitizer::TextArea($_POST['comment']);

// استفاده از متد TextEditor برای تمیز کردن محتوای ویرایشگر متن
$content = Sanitizer::TextEditor($_POST['content']);
```

#### توضیحات

- **`Sanitizer::Number($num):`** ورودی را به عدد صحیح تبدیل می‌کند.
- **`Sanitizer::Char($value):`** ورودی رشته‌ای را تمیز کرده و از کاراکترهای مخرب پاک می‌کند.
- **`Sanitizer::Url($value, $string):`** ورودی را پاک کرده و مناسب استفاده به عنوان URL می‌کند.
- **`Sanitizer::ImageName($value, $string):`** ورودی را پاک کرده و مناسب استفاده به عنوان نام فایل تصویر می‌کند.
- **`Sanitizer::TextArea($value):`** محتوای وارد شده در تکست اریا را تمیز می‌کند.
- **`Sanitizer::TextEditor($value):`** محتوای وارد شده از طریق ویرایشگر متن را تمیز می‌کند.

## License

این پروژه تحت مجوز MIT منتشر شده است. برای اطلاعات بیشتر، فایل [LICENSE](LICENSE) را مشاهده کنید.

## Support

در صورت وجود هرگونه سوال یا مشکل، لطفاً یک Issue در مخزن گیت‌هاب پروژه ثبت کنید.

## Author

- **مهر** - [GitHub](https://github.com/mehr1300)

---

[مخزن گیت‌هاب](https://github.com/mehr1300/php_backend_mysql_pdo)

---

**توجه:** با کلیک بر روی [راهنمای فارسی](#راهنمای-فارسی) در ابتدای متن، می‌توانید مستقیماً به بخش فارسی هدایت شوید.
