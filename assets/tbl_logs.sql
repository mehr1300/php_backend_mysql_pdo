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

 Date: 03/12/2024 23:55:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_logs
-- ----------------------------
DROP TABLE IF EXISTS `tbl_logs`;
CREATE TABLE `tbl_logs`  (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `log_type` int NULL DEFAULT NULL,
  `log_from` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  `log_details` longtext CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  PRIMARY KEY (`log_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59959 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_logs
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
