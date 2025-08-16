# Complete PHP Backend Project with MySQL Database


This project is a full-featured backend built with PHP, utilizing a MySQL database and PDO for secure database interactions.

### Updates 1.2.6:
- **Update `Sanitizer::Url`**.
- **Add  `Validate::Url`**.

### Updates 1.2.5:
- **Update `Base::Isset`**.
- **Add  `Validate::Money`**.

### Updates 1.2.4:
- **Add And Update `PD::Transaction`**.
- **Add  `Validate::Number`**.
- **Add  `Sanitizer::Site`**.
- **Add  `Sanitizer::Image`**.
- **Updated the `header.php` file**.
- **Updated the `configs-example.php` file**.
- **Updated the `auth.php` file**.
- **Updated the `baseClass.php` file**.


### Updates 1.2.3:
- **Removed the `CreatePagingNumber` class** due to redundancy with `Paging`.
- **Updated the `configs-example` file**.
- **Fixed issue with token** in `$GLOBALS['token_contents']` and corrected it to `$GLOBALS[TOKEN_NAME]`.

## Updates 1.2.2
- **Fixed path error when function is missing**
- Add **Transaction**
- Add in class Base **IssetCustom**
- Add in class Base::IssetCustom **type money**

## Updates 1.2.1
- **Fixed path error when function is missing**
- Resolved **authentication** issues
- Simplified and improved user authentication **security**
- Changed the process and functionality of retrieving data in different input types using **Base::Isset**
- Added **Base::SetData**
- Renamed **ReturnError** to **SetError**
- add Support **UUID**

## Prerequisites

- **PHP** version 8 or higher
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

6. **Assets Folder:**

   A folder named `assets` is present in the root directory of the project. This folder contains the following files that you can use if needed:

   - `tbl_logs.sql`
   - `tbl_properties.sql`
   - `tbl_user_meta_data.sql`
   - `web.config`
   - `.htaccess`

   You can execute the SQL scripts to create the necessary tables in your database and use the configuration files for server setup as required.

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
    public static function SingleSelect(string $Table_Name, string $Condition, array $params, $What_Row = 'count(*)') {//...//}
    public static function RowSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*") {//...//}
    public static function MultiSelect(string $Table_Name, string $Condition, array $params, $What_Row = "*"): bool|array  {//...//}
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


#### Example Usage of the `Base::Isset`

```php
<?php

// Professional (Concise) Usage Example
// Compact schema using pipe format for most fields, with all features demonstrated.

$input = Base::Isset([
    'username' => "username|minLength:3|maxLength:20|label:'نام کاربری'|options:toLow=false,arTfa=true",
    'password' => "password|minLength:8|label:'رمز عبور'",
    'status' => ['active', 'inactive', 'pending'],
    'search' => "string|minLength:1|empty|label:جستجو",
    'old' => "int|min:18|label:سن",
    'email' => "email|empty|maxLength:100|label:ایمیل",
    'money' => "money|min:1000|max:1000000|label:مبلغ",
    'description' => "textarea|length:200|label:توضیحات|options:toLow=true,arTfa=false",
    'role' => "string|in:admin,user,guest|label:نقش",
    'score' => "int|max:100|in:0,50,100|label:امتیاز",
]);


// Simple (Detailed) Usage Example with English Comments
// Uses associative arrays for readability, showing optional fields, labels, and options.
// Ideal for beginners; backward compatible with simple rules.

$input = Base::Isset([
    // Associative array: Username with length rules, custom label, and options (disable toLow, enable arTfa).
    'username' => [
        'type' => 'username',       // Base validation type.
        'minLength' => 3,           // Minimum length requirement.
        'maxLength' => 20,          // Maximum length requirement.
        'label' => 'نام کاربری',   // Custom label for error messages (optional; defaults to field name).
        'options' => ['toLow' => false, 'arTfa' => true],  // String processing options (optional, defaults to true for both).
    ],
    // String pipe: Password with min length and custom label (no options needed).
    'password' => "password|minLength:8|label:'رمز عبور'",
    // List array: Enum for status (must match one; default label is 'status').
    'status' => ['active', 'inactive', 'pending'],
    // String pipe: Optional search string with min length and label.
    'search' => "string|minLength:1|empty|label:جستجو",
    // String pipe: Integer age with min value and label.
    'old' => "int|min:18|label:سن",
    // Associative array: Optional email with max length and label (no options).
    'email' => [
        'type' => 'email',
        'empty' => true,            // Makes field optional.
        'maxLength' => 100,
        'label' => 'ایمیل',
    ],
    // Associative array: Money with min/max values and label.
    'money' => [
        'type' => 'money',
        'min' => 1000,
        'max' => 1000000,
        'label' => 'مبلغ',
    ],
    // Associative array: Textarea with exact length, label, and options (enable toLow, disable arTfa).
    'description' => [
        'type' => 'textarea',
        'length' => 200,            // Exact length requirement.
        'label' => 'توضیحات',
        'options' => ['toLow' => true, 'arTfa' => false],
    ],
    // Associative array: String with enum ('in') rule and label.
    'role' => [
        'type' => 'string',
        'in' => 'admin,user,guest', // Must be one of these values.
        'label' => 'نقش',
    ],
    // String pipe: Integer score with max and enum rules, plus label.
    'score' => "int|max:100|in:0,50,100|label:امتیاز",
]);

// Notes:
// - Labels are optional; if omitted, uses field name (e.g., 'username') in errors.
// - Options apply only to string-based types (string, textarea, text_editor) and are optional.
// - All inputs are securely sanitized (e.g., htmlspecialchars, addslashes, XSS filters).
// - Supports backward compatibility with simple rules like 'username' => 'username'.
 
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
