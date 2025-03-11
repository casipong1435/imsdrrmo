/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : imsdrrmodb

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-03-11 11:12:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for affected_people
-- ----------------------------
DROP TABLE IF EXISTS `affected_people`;
CREATE TABLE `affected_people` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `incident_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `purok` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of affected_people
-- ----------------------------
INSERT INTO `affected_people` VALUES ('1', '1', 'Christopher Casipong', 'Purok 1', '2025-03-07 06:06:39', '2025-03-07 06:06:39');

-- ----------------------------
-- Table structure for alerts
-- ----------------------------
DROP TABLE IF EXISTS `alerts`;
CREATE TABLE `alerts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of alerts
-- ----------------------------
INSERT INTO `alerts` VALUES ('3', 'Fire Alert', '2025-03-07 07:19:55', '2025-03-07 07:19:55');
INSERT INTO `alerts` VALUES ('4', 'Flood Alert', '2025-03-07 07:24:23', '2025-03-07 07:24:23');
INSERT INTO `alerts` VALUES ('5', 'Earthquake Alert', '2025-03-07 07:33:01', '2025-03-07 07:33:01');

-- ----------------------------
-- Table structure for barangays
-- ----------------------------
DROP TABLE IF EXISTS `barangays`;
CREATE TABLE `barangays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barangay_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of barangays
-- ----------------------------
INSERT INTO `barangays` VALUES ('1', 'Aquino', null, null);
INSERT INTO `barangays` VALUES ('2', 'Balatacan', null, null);
INSERT INTO `barangays` VALUES ('3', 'Baluc', null, null);
INSERT INTO `barangays` VALUES ('4', 'Banglay', null, null);
INSERT INTO `barangays` VALUES ('5', 'Bintana', null, null);
INSERT INTO `barangays` VALUES ('6', 'Bocator', null, null);
INSERT INTO `barangays` VALUES ('7', 'Bongabong', null, null);
INSERT INTO `barangays` VALUES ('8', 'Caniangan', null, null);
INSERT INTO `barangays` VALUES ('9', 'Capalaran', null, null);
INSERT INTO `barangays` VALUES ('10', 'Catagan', null, null);
INSERT INTO `barangays` VALUES ('11', 'Barangay I - City Hall (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('12', 'Barangay II - Marilou Annex (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('13', 'Barangay III- Market Kalubian (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('14', 'Barangay IV - St. Michael (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('15', 'Barangay V - Malubog (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('16', 'Barangay VI - Lower Polao (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('17', 'Barangay VII - Upper Polao (Poblacion)', null, null);
INSERT INTO `barangays` VALUES ('18', 'Hoyohoy', null, null);
INSERT INTO `barangays` VALUES ('19', 'Isidro D. Tan (Dimalooc)', null, null);
INSERT INTO `barangays` VALUES ('20', 'Garang', null, null);
INSERT INTO `barangays` VALUES ('21', 'Guinabot', null, null);
INSERT INTO `barangays` VALUES ('22', 'Guinalaban', null, null);
INSERT INTO `barangays` VALUES ('23', 'Kausawagan', null, null);
INSERT INTO `barangays` VALUES ('24', 'Kimat', null, null);
INSERT INTO `barangays` VALUES ('25', 'Labuyo', null, null);
INSERT INTO `barangays` VALUES ('26', 'Lorenzo Tan', null, null);
INSERT INTO `barangays` VALUES ('27', 'Lumban', null, null);
INSERT INTO `barangays` VALUES ('28', 'Maloro', null, null);
INSERT INTO `barangays` VALUES ('29', 'Manga', null, null);
INSERT INTO `barangays` VALUES ('30', 'Mantic', null, null);
INSERT INTO `barangays` VALUES ('31', 'Maquilao', null, null);
INSERT INTO `barangays` VALUES ('32', 'Matugnao', null, null);
INSERT INTO `barangays` VALUES ('33', 'Migcanaway', null, null);
INSERT INTO `barangays` VALUES ('34', 'Minsubong', null, null);
INSERT INTO `barangays` VALUES ('35', 'Owayan', null, null);
INSERT INTO `barangays` VALUES ('36', 'Paiton', null, null);
INSERT INTO `barangays` VALUES ('37', 'Panalsalan', null, null);
INSERT INTO `barangays` VALUES ('38', 'Pangabuan', null, null);
INSERT INTO `barangays` VALUES ('39', 'Prenza', null, null);
INSERT INTO `barangays` VALUES ('40', 'Salimpuno', null, null);
INSERT INTO `barangays` VALUES ('41', 'San Antonio', null, null);
INSERT INTO `barangays` VALUES ('42', 'San Apolinario', null, null);
INSERT INTO `barangays` VALUES ('43', 'San Vicente', null, null);
INSERT INTO `barangays` VALUES ('44', 'Santa Cruz', null, null);
INSERT INTO `barangays` VALUES ('45', 'Santa Maria (Baga)', null, null);
INSERT INTO `barangays` VALUES ('46', 'Santo Niño', null, null);
INSERT INTO `barangays` VALUES ('47', 'Sicot', null, null);
INSERT INTO `barangays` VALUES ('48', 'Silanga', null, null);
INSERT INTO `barangays` VALUES ('49', 'Silangit', null, null);
INSERT INTO `barangays` VALUES ('50', 'Simasay', null, null);
INSERT INTO `barangays` VALUES ('51', 'Sumirap', null, null);
INSERT INTO `barangays` VALUES ('52', 'Taguite', null, null);
INSERT INTO `barangays` VALUES ('53', 'Tituron', null, null);
INSERT INTO `barangays` VALUES ('54', 'Tugas', null, null);
INSERT INTO `barangays` VALUES ('55', 'Villaba', null, null);

-- ----------------------------
-- Table structure for borrowed_supplies
-- ----------------------------
DROP TABLE IF EXISTS `borrowed_supplies`;
CREATE TABLE `borrowed_supplies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_id` bigint(20) unsigned NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `borrower` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of borrowed_supplies
-- ----------------------------

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for certificate_requests
-- ----------------------------
DROP TABLE IF EXISTS `certificate_requests`;
CREATE TABLE `certificate_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `incident` varchar(255) DEFAULT NULL,
  `incident_id` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_given` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of certificate_requests
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for incidents
-- ----------------------------
DROP TABLE IF EXISTS `incidents`;
CREATE TABLE `incidents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `informant` varchar(255) NOT NULL,
  `date_time` datetime NOT NULL,
  `incident_type_id` bigint(20) unsigned NOT NULL,
  `barangay_id` bigint(20) unsigned NOT NULL,
  `description` text NOT NULL,
  `purok` varchar(255) NOT NULL,
  `casualties` varchar(255) NOT NULL,
  `other_incident` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of incidents
-- ----------------------------
INSERT INTO `incidents` VALUES ('1', 'Brgy.Captain Hon. Jovie Espinosa', '2025-03-07 14:03:00', '6', '7', 'About 2:03:00 pm there were Fire happended at Purok 1, Bongabong, Misamis Occidental his house was burned by Fire resulted to loss of home and owned by Christopher Casipong', 'Purok 1', '0', null, '2025-03-07 06:06:39', '2025-03-07 06:08:49');

-- ----------------------------
-- Table structure for incident_images
-- ----------------------------
DROP TABLE IF EXISTS `incident_images`;
CREATE TABLE `incident_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `incident_id` bigint(20) unsigned NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of incident_images
-- ----------------------------

-- ----------------------------
-- Table structure for incident_types
-- ----------------------------
DROP TABLE IF EXISTS `incident_types`;
CREATE TABLE `incident_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of incident_types
-- ----------------------------
INSERT INTO `incident_types` VALUES ('1', 'Earthquake', null, null);
INSERT INTO `incident_types` VALUES ('2', 'Tsunami', null, null);
INSERT INTO `incident_types` VALUES ('3', 'Landslide', null, null);
INSERT INTO `incident_types` VALUES ('4', 'Flood', null, null);
INSERT INTO `incident_types` VALUES ('5', 'Tornado', null, null);
INSERT INTO `incident_types` VALUES ('6', 'Fire', null, null);
INSERT INTO `incident_types` VALUES ('7', 'Wildfire/Bushfire', null, null);
INSERT INTO `incident_types` VALUES ('8', 'Others', null, null);

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of logs
-- ----------------------------
INSERT INTO `logs` VALUES ('1', 'Added an account', 'Admin added lumaban21, a new account to the system.', '2025-03-07 06:01:47', '2025-03-07 06:01:47');
INSERT INTO `logs` VALUES ('2', 'Added an item', 'Admin added First Aid Kit to the system.', '2025-03-07 06:20:16', '2025-03-07 06:20:16');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('16', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('17', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('18', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('19', '2025_01_29_055318_create_logs_table', '1');
INSERT INTO `migrations` VALUES ('20', '2025_01_29_055356_create_supplies_table', '1');
INSERT INTO `migrations` VALUES ('21', '2025_01_29_055413_create_incidents_table', '1');
INSERT INTO `migrations` VALUES ('22', '2025_01_29_055439_create_barangays_table', '1');
INSERT INTO `migrations` VALUES ('23', '2025_01_29_055733_create_incident_types_table', '1');
INSERT INTO `migrations` VALUES ('24', '2025_01_29_063030_create_incident_images_table', '1');
INSERT INTO `migrations` VALUES ('25', '2025_02_06_144432_create_supply_activities_table', '1');
INSERT INTO `migrations` VALUES ('26', '2025_02_07_085415_create_affected_people_table', '1');
INSERT INTO `migrations` VALUES ('27', '2025_02_13_033922_create_certificate_requests_table', '1');
INSERT INTO `migrations` VALUES ('28', '2025_02_26_061845_create_notifications_table', '1');
INSERT INTO `migrations` VALUES ('29', '2025_03_01_115725_create_alerts_table', '1');
INSERT INTO `migrations` VALUES ('30', '2025_03_05_034007_create_borrowed_supplies_table', '1');

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES ('0342fa44-e542-4fa2-85ed-b632d9ea636f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:09.523603Z\"}', null, '2025-03-07 07:23:09', '2025-03-07 07:23:09');
INSERT INTO `notifications` VALUES ('239109dd-3382-4cdc-b610-29566fdb8cee', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Alert! Fire incident had happened in Purok 1, Bongabong, Tangub City on March 7, 2025 2:03 PM\",\"created_at\":\"2025-03-07T06:06:46.108388Z\"}', '2025-03-07 06:24:33', '2025-03-07 06:06:46', '2025-03-07 06:24:33');
INSERT INTO `notifications` VALUES ('28a38aaa-d689-47db-97f0-94904107ac48', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:20.801191Z\"}', null, '2025-03-07 07:23:20', '2025-03-07 07:23:20');
INSERT INTO `notifications` VALUES ('306e3d6f-6220-4c53-8d1e-43132bb1b41c', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:12:11.566342Z\"}', null, '2025-03-07 06:12:11', '2025-03-07 06:12:11');
INSERT INTO `notifications` VALUES ('3e1810ed-a4f6-4f04-88a9-dc6670a5badb', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:12:11.552890Z\"}', null, '2025-03-07 06:12:11', '2025-03-07 06:12:11');
INSERT INTO `notifications` VALUES ('418adb29-2e1e-45d5-9b87-30494fb4755e', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:24:21.583874Z\"}', null, '2025-03-07 07:24:21', '2025-03-07 07:24:21');
INSERT INTO `notifications` VALUES ('4267ff31-6cd6-4cec-83c1-d9acac8d7ae3', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"fasfs\",\"created_at\":\"2025-03-07T07:27:53.262404Z\"}', null, '2025-03-07 07:27:53', '2025-03-07 07:27:53');
INSERT INTO `notifications` VALUES ('58a3fd2d-953d-47da-aa27-fbe4fdd16248', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:11:05.967491Z\"}', null, '2025-03-07 06:11:05', '2025-03-07 06:11:05');
INSERT INTO `notifications` VALUES ('590209ca-95f3-4c6e-b761-52a0dd17946e', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Earthquake Alert\",\"created_at\":\"2025-03-07T07:32:59.135704Z\"}', null, '2025-03-07 07:32:59', '2025-03-07 07:32:59');
INSERT INTO `notifications` VALUES ('5e061b1a-fbe7-475b-b614-2b54d11c350f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Fire Alert\",\"created_at\":\"2025-03-07T07:19:53.879678Z\"}', null, '2025-03-07 07:19:53', '2025-03-07 07:19:53');
INSERT INTO `notifications` VALUES ('62597427-9733-4668-9ed4-e3ee4d84cb1e', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:20.799099Z\"}', null, '2025-03-07 07:23:20', '2025-03-07 07:23:20');
INSERT INTO `notifications` VALUES ('6b606b7d-0b43-4dea-a1cd-da778828eb01', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Earthquake Alert\",\"created_at\":\"2025-03-07T07:32:59.144828Z\"}', null, '2025-03-07 07:32:59', '2025-03-07 07:32:59');
INSERT INTO `notifications` VALUES ('6c0dec3f-bad7-4992-b2ad-0d08ad2f6d11', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:20.792277Z\"}', null, '2025-03-07 07:23:20', '2025-03-07 07:23:20');
INSERT INTO `notifications` VALUES ('789754ad-f1a7-4f06-993a-b7fc73b254f8', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:11:05.948901Z\"}', null, '2025-03-07 06:11:05', '2025-03-07 06:11:05');
INSERT INTO `notifications` VALUES ('7923269a-56bd-4d93-ada4-a5142933e787', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:12:11.563020Z\"}', null, '2025-03-07 06:12:11', '2025-03-07 06:12:11');
INSERT INTO `notifications` VALUES ('8088949d-5f1e-4ea7-88c7-9dabfb59f704', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:24:21.599500Z\"}', null, '2025-03-07 07:24:21', '2025-03-07 07:24:21');
INSERT INTO `notifications` VALUES ('833c9f2e-06bf-417a-b99a-52c189d251a0', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '1', '{\"message\":\"Alert! Fire incident had happened in Purok 1, Bongabong, Tangub City on March 7, 2025 2:03 PM\",\"created_at\":\"2025-03-07T06:06:45.919447Z\"}', null, '2025-03-07 06:06:46', '2025-03-07 06:06:46');
INSERT INTO `notifications` VALUES ('91968a4e-ea01-4e39-9cc0-3ce5a4cf0da7', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Earthquake Alert\",\"created_at\":\"2025-03-07T07:32:59.142705Z\"}', null, '2025-03-07 07:32:59', '2025-03-07 07:32:59');
INSERT INTO `notifications` VALUES ('99b6b99b-25c6-406f-b1b1-8551608049ed', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:09.532496Z\"}', null, '2025-03-07 07:23:09', '2025-03-07 07:23:09');
INSERT INTO `notifications` VALUES ('a05ed80f-5e39-4a00-bbd4-c2bfdf1ac3d1', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Alert! Fire incident had happened in Purok 1, Bongabong, Tangub City on March 7, 2025 2:03 PM\",\"created_at\":\"2025-03-07T06:06:46.103441Z\"}', null, '2025-03-07 06:06:46', '2025-03-07 06:06:46');
INSERT INTO `notifications` VALUES ('a5dd5e56-f96a-4e05-a9d7-7cb990de8169', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"fasfs\",\"created_at\":\"2025-03-07T07:27:53.269264Z\"}', null, '2025-03-07 07:27:53', '2025-03-07 07:27:53');
INSERT INTO `notifications` VALUES ('a724f024-f4f3-4e58-887e-96294326a27d', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:11:05.963627Z\"}', null, '2025-03-07 06:11:05', '2025-03-07 06:11:05');
INSERT INTO `notifications` VALUES ('a72f370a-a959-4dc6-aefb-02e880972caa', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:17:58.437243Z\"}', null, '2025-03-07 06:17:58', '2025-03-07 06:17:58');
INSERT INTO `notifications` VALUES ('b53d39ff-0a10-4360-b1d6-599f92e51c92', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:24:21.589806Z\"}', null, '2025-03-07 07:24:21', '2025-03-07 07:24:21');
INSERT INTO `notifications` VALUES ('bdf8a79c-3405-45bd-8318-15c1e1707549', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"fasfs\",\"created_at\":\"2025-03-07T07:27:53.271334Z\"}', null, '2025-03-07 07:27:53', '2025-03-07 07:27:53');
INSERT INTO `notifications` VALUES ('d36a39d0-b44b-4878-a94c-9ee00802770f', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '2', '{\"message\":\"Fire Alert\",\"created_at\":\"2025-03-07T07:19:53.869447Z\"}', null, '2025-03-07 07:19:53', '2025-03-07 07:19:53');
INSERT INTO `notifications` VALUES ('f2397ba8-4059-4792-86a6-689a08b83f69', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '4', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:17:58.452480Z\"}', null, '2025-03-07 06:17:58', '2025-03-07 06:17:58');
INSERT INTO `notifications` VALUES ('f7a71aeb-d0f8-402a-83e6-6a9949d23835', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Fire Alert at Bongabong Today\",\"created_at\":\"2025-03-07T06:17:58.448235Z\"}', null, '2025-03-07 06:17:58', '2025-03-07 06:17:58');
INSERT INTO `notifications` VALUES ('fce333be-3cdd-4348-bb21-04defa9756fa', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Flood Alert\",\"created_at\":\"2025-03-07T07:23:09.530003Z\"}', null, '2025-03-07 07:23:09', '2025-03-07 07:23:09');
INSERT INTO `notifications` VALUES ('fde1da2a-8ae5-4275-a45a-aff676fefa97', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', '3', '{\"message\":\"Fire Alert\",\"created_at\":\"2025-03-07T07:19:53.877022Z\"}', null, '2025-03-07 07:19:53', '2025-03-07 07:19:53');

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `mobile_number` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mobile_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('0iPB8cyoyv1xIqHPZb7gr8F8QWeEemDQKkXKBGCP', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDVFU05jR3E1TU9UTTdvUGJJalBZaXRDWGZLc3dDeVQ2cTFIdWM2ZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3JlY29yZC9zdXBwbHkiO319', '1741339060');

-- ----------------------------
-- Table structure for supplies
-- ----------------------------
DROP TABLE IF EXISTS `supplies`;
CREATE TABLE `supplies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of supplies
-- ----------------------------
INSERT INTO `supplies` VALUES ('1', 'First Aid Kit', '50', 'pack', 'Use for Rescue', '1', '1', '2025-03-07 06:20:16', '2025-03-07 08:45:58');

-- ----------------------------
-- Table structure for supply_activities
-- ----------------------------
DROP TABLE IF EXISTS `supply_activities`;
CREATE TABLE `supply_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of supply_activities
-- ----------------------------
INSERT INTO `supply_activities` VALUES ('1', 'First Aid Kit', '+ 50', '2025-03-07 06:20:38', '2025-03-07 06:20:38');
INSERT INTO `supply_activities` VALUES ('2', 'First Aid Kit', '- 10', '2025-03-07 06:21:03', '2025-03-07 06:21:03');
INSERT INTO `supply_activities` VALUES ('3', 'First Aid Kit', '+ 6', '2025-03-07 06:21:24', '2025-03-07 06:21:24');
INSERT INTO `supply_activities` VALUES ('4', 'First Aid Kit', '+ 4', '2025-03-07 06:21:53', '2025-03-07 06:21:53');
INSERT INTO `supply_activities` VALUES ('5', 'First Aid Kit', '- 2', '2025-03-07 08:45:55', '2025-03-07 08:45:55');
INSERT INTO `supply_activities` VALUES ('6', 'First Aid Kit', '+ 2', '2025-03-07 08:45:58', '2025-03-07 08:45:58');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `barangay_id` bigint(20) unsigned DEFAULT NULL,
  `purok` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `role` tinyint(4) NOT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `mobile_number_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_mobile_number_unique` (`mobile_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Admin', null, null, 'admin', null, null, null, null, '1', null, null, '$2y$12$dTxoFNxXo7ItQJBjE54ece2yNdehfJP.hAqAEGR1OBgxzC2Kwh6/6', null, '2025-03-05 14:43:37', '2025-03-05 14:43:37');
INSERT INTO `users` VALUES ('2', 'Christopher', null, 'Casipong', 'casipong143', 'male', '12', 'Purok 2', '2001-04-12', '2', '9453495262', null, '$2y$12$XMRltk9RarjL3sPsFJh68efdu2s1qT8AwOOH7M7BZE7BzTaQjP/7C', null, '2025-03-05 14:47:02', '2025-03-05 14:47:02');
INSERT INTO `users` VALUES ('3', 'Laren', null, 'Dula', 'laren12', 'male', '11', 'Purok 2', '2002-04-12', '2', '9683022435', null, '$2y$12$6T/L76Danm4DK.5A6eGdSefI6dNcBpINc8hq452JgH6byWcxQvnhu', null, '2025-03-07 05:59:23', '2025-03-07 05:59:23');
INSERT INTO `users` VALUES ('4', 'Mark', null, 'Lumaban', 'lumaban21', 'male', '19', 'Purok 2', '1999-01-01', '0', '9683092134', null, '$2y$12$JvXQKkQr0Hvi/fJT297nBuXrfHvYMpseCz2uB8g/CWo8HSVZzOgx2', null, '2025-03-07 06:01:47', '2025-03-07 06:01:47');
