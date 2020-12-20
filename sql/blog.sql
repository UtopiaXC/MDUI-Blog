/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 80016
 Source Host           : localhost:3306
 Source Schema         : blog_wu

 Target Server Type    : MySQL
 Target Server Version : 80016
 File Encoding         : 65001

 Date: 17/12/2020 16:45:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for indexes
-- ----------------------------
DROP TABLE IF EXISTS `indexes`;
CREATE TABLE `indexes`  (
  `IID` int(10) NOT NULL AUTO_INCREMENT,
  `index_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`IID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of indexes
-- ----------------------------

-- ----------------------------
-- Table structure for links
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links`  (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `LinkTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `Link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of links
-- ----------------------------

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages`  (
  `PID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `index_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `Description` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `Content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `LatestSubmit` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`PID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pages
-- ----------------------------

-- ----------------------------
-- Table structure for pictures
-- ----------------------------
DROP TABLE IF EXISTS `pictures`;
CREATE TABLE `pictures`  (
  `PID` int(11) NOT NULL AUTO_INCREMENT,
  `PicTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `PicLink` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`PID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pictures
-- ----------------------------
INSERT INTO `pictures` VALUES (1, '1', 'https://s3.ax1x.com/2020/12/17/r31fD1.png');
INSERT INTO `pictures` VALUES (2, '2', 'https://s3.ax1x.com/2020/12/17/r312v9.jpg');
INSERT INTO `pictures` VALUES (3, '3', 'https://s3.ax1x.com/2020/12/17/r31WuR.jpg');
INSERT INTO `pictures` VALUES (4, '4', 'https://s3.ax1x.com/2020/12/17/r31ggJ.jpg');
INSERT INTO `pictures` VALUES (5, '5', 'https://s3.ax1x.com/2020/12/17/r31c34.jpg');
INSERT INTO `pictures` VALUES (6, '6', 'https://s3.ax1x.com/2020/12/17/r31hHx.jpg');
INSERT INTO `pictures` VALUES (7, '7', 'https://s3.ax1x.com/2020/12/17/r315E6.jpg');
INSERT INTO `pictures` VALUES (8, '8', 'https://s3.ax1x.com/2020/12/17/r31bgH.png');
INSERT INTO `pictures` VALUES (9, '9', 'https://s3.ax1x.com/2020/12/17/r31H8e.jpg');
INSERT INTO `pictures` VALUES (10, '10', 'https://s3.ax1x.com/2020/12/17/r317CD.jpg');
INSERT INTO `pictures` VALUES (11, '11', 'https://s3.ax1x.com/2020/12/17/r31IUK.jpg');
INSERT INTO `pictures` VALUES (12, '12', 'https://s3.ax1x.com/2020/12/17/r31o4O.jpg');
INSERT INTO `pictures` VALUES (13, '13', 'https://s3.ax1x.com/2020/12/17/r31qvd.jpg');
INSERT INTO `pictures` VALUES (14, '14', 'https://s3.ax1x.com/2020/12/17/r31XDI.jpg');
INSERT INTO `pictures` VALUES (15, '15', 'https://s3.ax1x.com/2020/12/17/r31OKA.jpg');
INSERT INTO `pictures` VALUES (16, '16', 'https://s3.ax1x.com/2020/12/17/r31jbt.png');
INSERT INTO `pictures` VALUES (17, '17', 'https://s3.ax1x.com/2020/12/17/r31zUf.jpg');
INSERT INTO `pictures` VALUES (18, '18', 'https://s3.ax1x.com/2020/12/17/r31xVP.jpg');
INSERT INTO `pictures` VALUES (19, '19', 'https://s3.ax1x.com/2020/12/17/r33S58.jpg');
INSERT INTO `pictures` VALUES (20, '20', 'https://s3.ax1x.com/2020/12/17/r33ZV0.png');
INSERT INTO `pictures` VALUES (21, '21', 'https://s3.ax1x.com/2020/12/17/r33kKs.png');
INSERT INTO `pictures` VALUES (22, '22', 'https://s3.ax1x.com/2020/12/17/r339PS.jpg');
INSERT INTO `pictures` VALUES (23, '23', 'https://s3.ax1x.com/2020/12/17/r33C8g.jpg');
INSERT INTO `pictures` VALUES (24, '24', 'https://s3.ax1x.com/2020/12/17/r33P2Q.jpg');
INSERT INTO `pictures` VALUES (25, '25', 'https://s3.ax1x.com/2020/12/17/r33Ebq.png');
INSERT INTO `pictures` VALUES (26, '26', 'https://s3.ax1x.com/2020/12/17/r33ivj.jpg');
INSERT INTO `pictures` VALUES (27, '27', 'https://s3.ax1x.com/2020/12/17/r33Arn.jpg');
INSERT INTO `pictures` VALUES (28, '28', 'https://s3.ax1x.com/2020/12/17/r33eaV.jpg');
INSERT INTO `pictures` VALUES (29, '29', 'https://s3.ax1x.com/2020/12/17/r33m5T.jpg');
INSERT INTO `pictures` VALUES (30, '30', 'https://s3.ax1x.com/2020/12/17/r33KGF.jpg');

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags`  (
  `TID` int(10) NOT NULL AUTO_INCREMENT,
  `Tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `PID` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`TID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tags
-- ----------------------------
-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `TokenID` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `Token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`UID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'admin', 'e47af108b04c61795c5e522f381066e8', NULL, NULL);

-- ----------------------------
-- Table structure for web_message
-- ----------------------------
DROP TABLE IF EXISTS `web_message`;
CREATE TABLE `web_message`  (
                                `ID` int(11) NOT NULL AUTO_INCREMENT,
                                `WebTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
                                `WebContent` varchar(2550) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
                                PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of web_message
-- ----------------------------
INSERT INTO `web_message` VALUES (1, 'title', 'UtopiaXC\'s Blog');
INSERT INTO `web_message` VALUES (2, 'footer', 'Powered By <a href=\'https://github.com/UtopiaXC/MDUI-Blog/\' target=\'_blank\'>MDUI-Blog</a> | Designed By <a href=\'https://www.utopiaxc.cn/\' target=\'_blank\'>UtopiaXC</a>');

-- ----------------------------
-- Triggers structure for table pages
-- ----------------------------
DROP TRIGGER IF EXISTS `set_page_submit_time`;
delimiter ;;
CREATE TRIGGER `set_page_submit_time` BEFORE INSERT ON `pages` FOR EACH ROW BEGIN
SET new.LatestSubmit=NOW();
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table pages
-- ----------------------------
DROP TRIGGER IF EXISTS `set_page_update_time`;
delimiter ;;
CREATE TRIGGER `set_page_update_time` BEFORE UPDATE ON `pages` FOR EACH ROW BEGIN
SET new.LatestSubmit=NOW();
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
