<?php
/** Initialize headers */
const STATUS_PROJECT = "DEVELOPMENT"; //Two Value : DEVELOPMENT ,PRODUCT
const AUTHORIZED_URL_DEVELOPMENT = ["http://localhost:5173", "http://localhost:5174", "http://localhost:3000"];
const AUTHORIZED_URL_PRODUCT = ["", ""];
const ORIGINAL_DOMAIN = "example.com";

/** CRYPTO Configuration : */
const PASSWORD_CRYPTO = "PASSWORD@!_!@CRYPTO";


/** Upload Configuration : */
const UPLOAD_ADDRESS = "../../../uploads/";
const UPLOAD_PREFIX = "/uploads/";

/** Config Configuration : */
const EMAIL_LENGTH = 5;
const USERNAME_LENGTH = 5;
const PASSWORD_LENGTH = 6;
const VALID_EMAIL_DOMAINS = ["gmail.com", "yahoo.com", "yandex.com"];
const CODE_LENGTH = 10;
const CODE_SALT = "this-Test-for-code";
const UPLOAD_MAX_FILE_SIZE = 26214400 ;  // 25MB || 1MB = 1 * 1024 * 1024 => BYTE
const UPLOAD_MIN_FILE_SIZE = 512000;  // 500KB || 1KB = 1 * 1024 => BYTE
const VALID_CITY_CODES = [
    '021', // تهران
    '026', // البرز (کرج)
    '025', // قم
    '031', // اصفهان
    '051', // خراسان رضوی (مشهد)
    '056', // خراسان جنوبی (بیرجند)
    '058', // خراسان شمالی (بجنورد)
    '071', // فارس (شیراز)
    '041', // آذربایجان شرقی (تبریز)
    '044', // آذربایجان غربی (ارومیه)
    '045', // اردبیل
    '011', // مازندران (ساری)
    '017', // گلستان (گرگان)
    '013', // گیلان (رشت)
    '066', // لرستان (خرم‌آباد)
    '081', // همدان
    '086', // مرکزی (اراک)
    '083', // ایلام
    '084', // کرمانشاه
    '061', // خوزستان (اهواز)
    '034', // کرمان
    '076', // هرمزگان (بندرعباس)
    '077', // بوشهر
    '054', // سیستان و بلوچستان (زاهدان)
    '087', // کردستان (سنندج)
    '074', // کهگیلویه و بویراحمد (یاسوج)
    '035', // یزد
    '028', // قزوین
    '024', // زنجان
    '023', // سمنان
    '028', // چهارمحال و بختیاری
];

/** user password default login: */
const DEFAULT_USERNAME = "admin";
const DEFAULT_PASSWORD = "Aa@12345";

/** Log User: */
const GET_LOGS_META = false;

/** Token : */
const TOKEN_NAME = "access_token";
const TOKEN_EXPIRE_TIME = 86400;
/** Token SMS Panel Configuration : */

const TOKEN_SMS_PANEL = "";

/** Database Configuration : */
const SERVERNAME_DB = "localhost";
const USERNAME_DB = "Database_username";
const PASSWORD_DB = "Database_password";
const DATABASE_DB = "Database_database";
