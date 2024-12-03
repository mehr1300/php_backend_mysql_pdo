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

 Date: 03/12/2024 23:55:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_user_meta_data
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_meta_data`;
CREATE TABLE `tbl_user_meta_data`  (
  `meta_id` int NOT NULL AUTO_INCREMENT,
  `request_method` varchar(5) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `user_ip` varchar(20) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  `meta_type` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci NULL DEFAULT NULL,
  `meta_info` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL,
  `meta_create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`meta_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf16 COLLATE = utf16_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_user_meta_data
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
