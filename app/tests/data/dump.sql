/*
 Navicat Premium Data Transfer

 Source Server         : conexión proyecto Gameaffinity
 Source Server Type    : MySQL
 Source Server Version : 80036 (8.0.36)
 Source Host           : localhost:4306
 Source Schema         : symfony_docker

 Target Server Type    : MySQL
 Target Server Version : 80036 (8.0.36)
 File Encoding         : 65001

 Date: 11/05/2024 19:45:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for developer
-- ----------------------------
DROP TABLE IF EXISTS `developer`;
CREATE TABLE `developer`  (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foundation_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54315 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for developer
-- ----------------------------
DROP TABLE IF EXISTS `developer`;
CREATE TABLE `developer`  (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foundation_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54315 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for game
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game`  (
  `id` int NOT NULL,
  `developer_id` int NULL DEFAULT NULL,
  `date_release` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_232B318C64DD9267`(`developer_id` ASC) USING BTREE,
  CONSTRAINT `FK_232B318C64DD9267` FOREIGN KEY (`developer_id`) REFERENCES `developer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 294674 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for game_genre
-- ----------------------------
DROP TABLE IF EXISTS `game_genre`;
CREATE TABLE `game_genre`  (
  `game_id` int NOT NULL,
  `genre_id` int NOT NULL,
  PRIMARY KEY (`game_id`, `genre_id`) USING BTREE,
  INDEX `IDX_B1634A77E48FD905`(`game_id` ASC) USING BTREE,
  INDEX `IDX_B1634A774296D31F`(`genre_id` ASC) USING BTREE,
  CONSTRAINT `FK_B1634A774296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_B1634A77E48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for game_platform
-- ----------------------------
DROP TABLE IF EXISTS `game_platform`;
CREATE TABLE `game_platform`  (
  `game_id` int NOT NULL,
  `platform_id` int NOT NULL,
  PRIMARY KEY (`game_id`, `platform_id`) USING BTREE,
  INDEX `IDX_92162FEDE48FD905`(`game_id` ASC) USING BTREE,
  INDEX `IDX_92162FEDFFE6496F`(`platform_id` ASC) USING BTREE,
  CONSTRAINT `FK_92162FEDE48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_92162FEDFFE6496F` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for genre
-- ----------------------------
DROP TABLE IF EXISTS `genre`;
CREATE TABLE `genre`  (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for platform
-- ----------------------------
DROP TABLE IF EXISTS `platform`;
CREATE TABLE `platform`  (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 633 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for score
-- ----------------------------
DROP TABLE IF EXISTS `score`;
CREATE TABLE `score`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `score` decimal(4, 0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for score_game
-- ----------------------------
DROP TABLE IF EXISTS `score_game`;
CREATE TABLE `score_game`  (
  `score_id` int NOT NULL,
  `game_id` int NOT NULL,
  PRIMARY KEY (`score_id`, `game_id`) USING BTREE,
  INDEX `IDX_933B0FA12EB0A51`(`score_id` ASC) USING BTREE,
  INDEX `IDX_933B0FAE48FD905`(`game_id` ASC) USING BTREE,
  CONSTRAINT `FK_933B0FA12EB0A51` FOREIGN KEY (`score_id`) REFERENCES `score` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_933B0FAE48FD905` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for score_user
-- ----------------------------
DROP TABLE IF EXISTS `score_user`;
CREATE TABLE `score_user`  (
  `score_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`score_id`, `user_id`) USING BTREE,
  INDEX `IDX_A78B573F12EB0A51`(`score_id` ASC) USING BTREE,
  INDEX `IDX_A78B573FA76ED395`(`user_id` ASC) USING BTREE,
  CONSTRAINT `FK_A78B573F12EB0A51` FOREIGN KEY (`score_id`) REFERENCES `score` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_A78B573FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `UNIQ_IDENTIFIER_EMAIL`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
