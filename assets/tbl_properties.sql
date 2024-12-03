/*
 Navicat Premium Data Transfer

 Source Server         : NtApi
 Source Server Type    : MySQL
 Source Server Version : 80034 (8.0.34)
 Source Host           : 45.149.78.219:3306
 Source Schema         : nt_backend_db

 Target Server Type    : MySQL
 Target Server Version : 80034 (8.0.34)
 File Encoding         : 65001

 Date: 03/12/2024 23:54:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_properties
-- ----------------------------
DROP TABLE IF EXISTS `tbl_properties`;
CREATE TABLE `tbl_properties`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `last_change` varchar(11) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `key_category` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `key_name` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `key_value` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 132 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_properties
-- ----------------------------
INSERT INTO `tbl_properties` VALUES (2, '1669411674', 'SITE', 'DOMAIN_SITE', NULL);
INSERT INTO `tbl_properties` VALUES (4, '1669411674', 'SITE', 'PRIVACY_POLICIES_URL', NULL);
INSERT INTO `tbl_properties` VALUES (9, '1669411674', 'ABOUT', 'ABOUT_INDEX', NULL);
INSERT INTO `tbl_properties` VALUES (13, '1669411674', 'SETTING', 'SETTING_ALLOW_USER_TO_ENTER', NULL);
INSERT INTO `tbl_properties` VALUES (14, '1669411674', 'SETTING', 'SETTING_ALLOW_ADMIN_TO_ENTER', NULL);
INSERT INTO `tbl_properties` VALUES (15, '1669411674', 'SOCIAL_NETWORK', 'TELEGRAM_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (16, '1669411674', 'SOCIAL_NETWORK', 'INSTAGRAM_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (17, '1669411674', 'SOCIAL_NETWORK', 'TWITTER_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (18, '1669411674', 'SOCIAL_NETWORK', 'YOUTUBE_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (19, '1669411674', 'SOCIAL_NETWORK', 'LINKEDIN_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (20, '1669411674', 'SOCIAL_NETWORK', 'APARAT_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (21, '1669411674', 'SOCIAL_NETWORK', 'FACEBOOK_CHANNEL', NULL);
INSERT INTO `tbl_properties` VALUES (22, '1669411674', 'ABOUT', 'ABOUT_BODY', NULL);
INSERT INTO `tbl_properties` VALUES (30, '1669411674', 'CONTACT', 'CONTACT_EMAIL', NULL);
INSERT INTO `tbl_properties` VALUES (31, '1669411674', 'CONTACT', 'CONTACT_PHONE', NULL);
INSERT INTO `tbl_properties` VALUES (32, '1669411674', 'CONTACT', 'CONTACT_TELEGRAM', NULL);
INSERT INTO `tbl_properties` VALUES (33, '1669411674', 'CONTACT', 'CONTACT_BODY', NULL);

SET FOREIGN_KEY_CHECKS = 1;
