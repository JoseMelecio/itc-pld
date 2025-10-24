/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `aliases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aliases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_list_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aliases_person_list_id_foreign` (`person_list_id`),
  CONSTRAINT `aliases_person_list_id_foreign` FOREIGN KEY (`person_list_id`) REFERENCES `person_lists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `birth_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `birth_dates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int DEFAULT NULL,
  `final_year` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `day` int DEFAULT NULL,
  `person_list_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `birth_dates_person_list_id_foreign` (`person_list_id`),
  CONSTRAINT `birth_dates_person_list_id_foreign` FOREIGN KEY (`person_list_id`) REFERENCES `person_lists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `birth_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `birth_places` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_list_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `birth_places_person_list_id_foreign` (`person_list_id`),
  CONSTRAINT `birth_places_person_list_id_foreign` FOREIGN KEY (`person_list_id`) REFERENCES `person_lists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation_message` json DEFAULT NULL,
  `data_select` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secondary_document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `person_list_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_person_list_id_foreign` (`person_list_id`),
  CONSTRAINT `documents_person_list_id_foreign` FOREIGN KEY (`person_list_id`) REFERENCES `person_lists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `template_clients_config` json DEFAULT NULL,
  `template_operations_config` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebr_configurations_user_id_foreign` (`user_id`),
  CONSTRAINT `ebr_configurations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_matrix_qualitative_mitigants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_matrix_qualitative_mitigants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `control_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `control_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodicity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodicity_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `effectiveness` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `final_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_matrix_qualitative_risks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_matrix_qualitative_risks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `risk_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `probability` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `final_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `basis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FFFFFF',
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_risk_element_indicator_related`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_risk_element_indicator_related` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ebr_id` bigint unsigned NOT NULL,
  `ebr_risk_element_indicator_id` bigint unsigned NOT NULL,
  `characteristic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `risk_indicator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `related_clients` int DEFAULT NULL,
  `related_operations` int DEFAULT NULL,
  `weight_range_impact` decimal(8,2) NOT NULL,
  `frequency_range_impact` decimal(8,2) NOT NULL,
  `characteristic_concentration` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebr_risk_element_indicator_related_ebr_id_foreign` (`ebr_id`),
  KEY `ebr_risk_element_indicator_id_fk` (`ebr_risk_element_indicator_id`),
  CONSTRAINT `ebr_risk_element_indicator_id_fk` FOREIGN KEY (`ebr_risk_element_indicator_id`) REFERENCES `ebr_risk_element_indicators` (`id`),
  CONSTRAINT `ebr_risk_element_indicator_related_ebr_id_foreign` FOREIGN KEY (`ebr_id`) REFERENCES `ebrs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_risk_element_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_risk_element_indicators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `characteristic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `report_config` json DEFAULT NULL,
  `risk_indicator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_risk_element_related`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_risk_element_related` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ebr_id` bigint unsigned NOT NULL,
  `ebr_risk_element_id` bigint unsigned NOT NULL,
  `element` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_mxn` decimal(15,2) NOT NULL,
  `total_clients` int NOT NULL,
  `total_operations` int NOT NULL,
  `weight_range_impact` decimal(8,2) NOT NULL,
  `frequency_range_impact` decimal(8,2) NOT NULL,
  `risk_inherent_concentration` decimal(8,2) NOT NULL,
  `risk_level_features` decimal(8,2) NOT NULL,
  `risk_level_integrated` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebr_risk_element_related_ebr_id_foreign` (`ebr_id`),
  KEY `ebr_risk_element_related_ebr_risk_element_id_foreign` (`ebr_risk_element_id`),
  CONSTRAINT `ebr_risk_element_related_ebr_id_foreign` FOREIGN KEY (`ebr_id`) REFERENCES `ebrs` (`id`),
  CONSTRAINT `ebr_risk_element_related_ebr_risk_element_id_foreign` FOREIGN KEY (`ebr_risk_element_id`) REFERENCES `ebr_risk_elements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_risk_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_risk_elements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `risk_element` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lateral_header` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_header` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_config` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_risk_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_risk_zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `risk_zone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `incidence_of_crime` int NOT NULL,
  `percentage_1` decimal(8,2) NOT NULL DEFAULT '0.00',
  `percentage_2` decimal(8,2) NOT NULL DEFAULT '0.00',
  `zone` int NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebr_types_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebr_types_users` (
  `user_id` bigint unsigned NOT NULL,
  `ebr_type_id` bigint unsigned NOT NULL,
  KEY `ebr_types_users_user_id_foreign` (`user_id`),
  KEY `ebr_types_users_ebr_type_id_foreign` (`ebr_type_id`),
  CONSTRAINT `ebr_types_users_ebr_type_id_foreign` FOREIGN KEY (`ebr_type_id`) REFERENCES `ebr_types` (`id`),
  CONSTRAINT `ebr_types_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ebrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ebrs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `file_name_clients` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name_operations` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('processing','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'processing',
  `total_operation_amount` decimal(15,2) DEFAULT NULL,
  `total_clients` bigint DEFAULT NULL,
  `total_operations` bigint DEFAULT NULL,
  `concentration` int DEFAULT NULL,
  `present_features` int DEFAULT NULL,
  `inherent_entity_risk` int DEFAULT NULL,
  `maximum_risk_level` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ebrs_user_id_foreign` (`user_id`),
  CONSTRAINT `ebrs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nationalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nationalities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_list_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nationalities_person_list_id_foreign` (`person_list_id`),
  CONSTRAINT `nationalities_person_list_id_foreign` FOREIGN KEY (`person_list_id`) REFERENCES `person_lists` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heading` tinyint(1) NOT NULL DEFAULT '0',
  `menu_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_to_show` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_tenant_id_unique` (`name`,`guard_name`),
  KEY `permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `person_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person_lists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `un_list_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci,
  `second_name` text COLLATE utf8mb4_unicode_ci,
  `third_name` text COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name_from_import` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `route_param` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spanish_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allow_massive` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_addresses` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','foreign') COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settlement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_addresses_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_addresses_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_contacts` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_contacts_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_contacts_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `p_l_d_notice_id` bigint unsigned NOT NULL,
  `custom_field_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_custom_fields_p_l_d_notice_id_foreign` (`p_l_d_notice_id`),
  KEY `pld_notice_custom_fields_custom_field_id_foreign` (`custom_field_id`),
  CONSTRAINT `pld_notice_custom_fields_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`),
  CONSTRAINT `pld_notice_custom_fields_p_l_d_notice_id_foreign` FOREIGN KEY (`p_l_d_notice_id`) REFERENCES `pld_notice` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_estates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_estates` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estate_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settlement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `real_folio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_estates_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_estates_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_financial_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_financial_operations` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monetary_instrument` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_financial_operations_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_financial_operations_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_massives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_massives` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `pld_notice_id` bigint unsigned NOT NULL,
  `file_uploaded` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `xml_generated` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `errors` longtext COLLATE utf8mb4_unicode_ci,
  `form_data` json DEFAULT NULL,
  `status` enum('processing','done','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_massives_user_id_foreign` (`user_id`),
  KEY `pld_notice_massives_pld_notice_id_foreign` (`pld_notice_id`),
  CONSTRAINT `pld_notice_massives_pld_notice_id_foreign` FOREIGN KEY (`pld_notice_id`) REFERENCES `pld_notice` (`id`),
  CONSTRAINT `pld_notice_massives_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_notices` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_id` bigint unsigned NOT NULL,
  `pld_notice_massive_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modification_folio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modification_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alert_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alert_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_notices_pld_notice_id_foreign` (`pld_notice_id`),
  KEY `pld_notice_notices_pld_notice_massive_id_foreign` (`pld_notice_massive_id`),
  CONSTRAINT `pld_notice_notices_pld_notice_id_foreign` FOREIGN KEY (`pld_notice_id`) REFERENCES `pld_notice` (`id`),
  CONSTRAINT `pld_notice_notices_pld_notice_massive_id_foreign` FOREIGN KEY (`pld_notice_massive_id`) REFERENCES `pld_notice_massives` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_people` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_notice_type` enum('object','beneficiary','representative') COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_type` enum('individual','legal','trust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_or_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paternal_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maternal_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_or_constitution_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_activity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trust_identification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_people_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_people_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pld_notice_unique_data_people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pld_notice_unique_data_people` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pld_notice_notice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reported_operations` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pld_notice_unique_data_people_pld_notice_notice_id_foreign` (`pld_notice_notice_id`),
  CONSTRAINT `pld_notice_unique_data_people_pld_notice_notice_id_foreign` FOREIGN KEY (`pld_notice_notice_id`) REFERENCES `pld_notice_notices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('create','delete','update') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'create',
  `content` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `system_logs_user_id_foreign` (`user_id`),
  KEY `system_logs_created_at_index` (`created_at`),
  CONSTRAINT `system_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `erase_cache` tinyint(1) NOT NULL DEFAULT '0',
  `has_default_password` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','disabled','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `multi_subject` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2024_11_23_213857_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2024_11_24_055441_add_fields_to_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2024_11_29_031248_create_pld_notice_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2024_12_02_220346_create_person_lists_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_12_02_221253_create_birth_places_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_12_03_203835_create_nationalities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_12_03_205101_create_birth_dates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_12_03_205814_create_documents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2024_12_03_210132_create_aliases_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_02_01_200641_add_user_type_field_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_02_15_230754_add_custom_field_notice_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_03_01_233252_add_soft_deletes_to_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_03_08_232403_create_system_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_03_18_212319_create_tenants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_03_22_133353_add_tenet_id_to_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_03_30_005041_create_ebrs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_04_26_042201_create_ebr_template_compositions_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_04_27_002638_create_ebr_clients_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_04_27_034853_create_ebr_operations_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_06_21_173556_create_ebr_types_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_06_21_180110_add_ebr_type_field_to_ebrs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_06_21_191638_add_ebr_types_users_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_06_22_013640_create_ebr_risk_elements_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_06_22_070759_add_ebr_risk_element_related_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_07_04_212229_create_ebr_risk_zones_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_07_06_001917_create_ebr_qualitative_risks_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_07_07_223337_create_ebr_matrix_qualitative_mitigants_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_07_29_232912_remove_ebr_client_id_foreign_contraind_on_ebr_operation_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_07_30_220546_create_ebr_risk_element_indicators',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_08_02_202301_create_ebr_risk_element_indicator_related',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_08_11_220223_add_report_config_field_to_ebr_risk_elements',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_08_13_222810_create_ebr_configurations_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_08_14_003554_delete_ebr_template_compositions_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_08_16_175830_remove_ebr_type_id_filed_from_ebrs_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_08_16_200235_delete_ebr_clients_and_operations_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_09_01_205944_remove_order_field_from_ebr_risk_elements_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_09_10_200011_add_multi_subject_field_in_users_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_09_12_103856_add_created_at_index_in_system_log_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_09_16_152816_remove_ebr_risk_element_id_field_from_ebr_risk_element_indicators_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_09_16_154402_add_report_config_to_ebr_risk_element_indicators_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_09_26_205330_create_pld_notice_massives_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_10_02_233929_add_allow_massive_filed_in_pld_notice_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_10_08_185354_create_pld_notice_notices_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_10_08_190358_create_pld_notice_people_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_10_08_192540_create_pld_notice_addresses_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_10_08_193300_create_pld_notice_contacts_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_10_08_194309_create_pld_notice_unique_data_people_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_10_08_202904_create_pld_notice_financial_operations_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_10_08_220425_create_pld_notice_estates_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_10_18_093825_remove_tenants',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_10_21_204140_add_has_default_password_field_in_users_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_10_23_195056_add_erease_cache_field_to_users_table',8);
