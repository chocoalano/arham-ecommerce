-- MySQL dump 10.13  Distrib 8.0.27, for macos11 (arm64)
--
-- Host: 127.0.0.1    Database: arham_ecommerce
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rajaongkir_province_id` int unsigned DEFAULT NULL,
  `province_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rajaongkir_city_id` int unsigned DEFAULT NULL,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rajaongkir_subdistrict_id` int unsigned DEFAULT NULL,
  `subdistrict_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `is_default_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_billing` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addr_geo_idx` (`customer_id`,`rajaongkir_city_id`,`rajaongkir_subdistrict_id`),
  CONSTRAINT `addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,1,'Office','Alyce Heathcote','+1.440.246.3213','98930 Malinda Groves Apt. 257','Apt. 806','80157-7795',2,'Massachusetts',496,'VonRuedenview',722,'port',-1.9256980,134.9956910,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(2,1,'Office','Chasity Lang','+1-325-205-2240','8235 Amara Point Suite 116','Jl. Prabu Kian Santang No.169A, RT.001/RW.004,','11360',20,'District of Columbia',252,'New Kaley',1393,'view',0.2760200,99.8727140,0,1,'2025-11-02 20:24:33','2025-11-03 04:35:19',NULL),(3,2,'Office','Harmony Waelchi','1-732-515-5850','5020 Zemlak Plains Suite 996','Suite 315','24113',8,'Iowa',66,'South Haskellville',1660,'side',-10.5308260,100.6676720,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(4,2,'Home','Darion Reichert','1-380-200-2132','2743 Watsica Alley','Suite 394','84814-1968',22,'Rhode Island',214,'East Elmer',840,'borough',0.5618260,101.5762100,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(5,3,'Office','Florida Durgan','+12105514401','712 Bartoletti Corners','Suite 800','49975',4,'New Jersey',391,'Lefflermouth',707,'town',0.4269290,109.3606030,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(6,3,'Office','Sam Monahan','+1.458.888.0164','7799 Kshlerin Fall Apt. 594',NULL,'19880',1,'North Dakota',7,'South Bridiefurt',1079,'view',-6.3608550,95.3450600,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(7,4,'Home','Efrain Durgan','903-415-5929','819 Wilfredo Island','Apt. 183','65085-9303',28,'Vermont',49,'Port Haydenstad',1560,'haven',-0.4496570,116.6526380,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(8,4,'Home','Elinore Corwin','(334) 342-2456','961 Tromp Plain Suite 161',NULL,'10168',22,'Utah',414,'Alessandrachester',746,'chester',-0.9524650,95.5255450,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(9,5,'Office','Raphaelle Gerlach','(640) 213-7858','252 Kuhlman Isle Apt. 965',NULL,'81299-0615',2,'North Dakota',180,'Port Zackery',633,'land',-2.6489180,133.4445740,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(10,5,'Office','Tyree Spencer','+1-872-601-1061','5594 Kianna Cliffs',NULL,'29599',1,'Idaho',148,'Nakiashire',1530,'furt',0.7045800,139.3216790,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(11,6,'Office','Miss Octavia McClure','405.441.4349','531 Jason Landing','Suite 441','96881',5,'New Jersey',401,'Heathcoteton',1937,'view',5.3686890,112.1727950,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(12,6,'Home','Darion Wolf III','+1-631-357-5381','79764 Hammes Extensions','Apt. 716','81916',9,'South Dakota',329,'Spinkabury',743,'furt',1.9181400,111.3660350,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(13,7,'Home','Moshe Cummerata','248.817.8497','88780 Annalise Village Suite 760',NULL,'64456',22,'Missouri',60,'Veummouth',1073,'stad',-4.6430470,95.2798570,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(14,7,'Office','Skye Rippin DDS','650.905.4675','377 Carmel Crossroad',NULL,'95649',17,'Colorado',10,'Lake Carolannestad',938,'town',-5.7262310,107.4764810,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(15,8,'Office','Mr. Mekhi Frami Sr.','+13017318921','39579 Swift Causeway Apt. 251','Apt. 939','32230',8,'New York',245,'Doyleberg',836,'borough',-9.9620530,135.0915530,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(16,8,'Home','Coleman Bode','+1-689-587-8162','290 Naomie Hollow',NULL,'77874',30,'Massachusetts',362,'Reynoldsville',1513,'bury',-3.8543320,120.6640970,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(17,9,'Office','Stefanie Cartwright','361.447.8785','7063 Maryjane Isle Apt. 943',NULL,'73021-4516',9,'South Carolina',178,'Lake Lucienneshire',811,'fort',4.0616140,131.3020070,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(18,9,'Home','May Goyette','(419) 302-5624','972 Christopher Plaza',NULL,'89369',12,'District of Columbia',417,'Humbertotown',1405,'stad',-2.0876470,134.7128000,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(19,10,'Home','Mrs. Tara Krajcik','+13616303814','42358 Kreiger Roads Apt. 642','Apt. 749','80476-5329',32,'Florida',232,'Port Daisha',552,'borough',5.4023990,105.2966770,1,0,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL),(20,10,'Home','Prof. Verona Krajcik DVM','+19519404244','228 Pearlie Roads Apt. 805',NULL,'49813',3,'New Hampshire',16,'North Liliana',1185,'burgh',-6.2640870,102.7274800,0,1,'2025-11-02 20:24:33','2025-11-02 20:24:33',NULL);
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_article_category`
--

DROP TABLE IF EXISTS `article_article_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article_article_category` (
  `article_id` bigint unsigned NOT NULL,
  `article_category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`article_category_id`),
  KEY `article_article_category_article_category_id_foreign` (`article_category_id`),
  CONSTRAINT `article_article_category_article_category_id_foreign` FOREIGN KEY (`article_category_id`) REFERENCES `article_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_article_category_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_article_category`
--

LOCK TABLES `article_article_category` WRITE;
/*!40000 ALTER TABLE `article_article_category` DISABLE KEYS */;
INSERT INTO `article_article_category` VALUES (1,1),(2,1),(4,1),(8,1),(10,1),(2,3),(5,3),(9,3),(6,4),(3,5),(1,6),(7,6),(3,7),(4,7),(9,7),(5,8),(7,8),(10,8);
/*!40000 ALTER TABLE `article_article_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_categories_slug_unique` (`slug`),
  KEY `article_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `article_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `article_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_categories`
--

LOCK TABLES `article_categories` WRITE;
/*!40000 ALTER TABLE `article_categories` DISABLE KEYS */;
INSERT INTO `article_categories` VALUES (1,NULL,'Voluptates vel','voluptates-vel-whIb',NULL,0,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(2,NULL,'Occaecati sunt','occaecati-sunt-pGRx',NULL,10,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(3,NULL,'Vero recusandae','vero-recusandae-0qLe',NULL,4,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(4,1,'Dolore velit','dolore-velit-nmAX','Dolor fugit possimus dicta maiores corporis.',9,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(5,1,'Quis velit','quis-velit-m0xj','Eveniet animi officia dolorem porro deleniti.',4,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(6,2,'Occaecati rerum','occaecati-rerum-umyb','Sit molestiae accusantium ratione esse perferendis consequatur ipsa assumenda.',8,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(7,2,'Cumque sapiente','cumque-sapiente-KVHo','Repellat aut maxime soluta facilis animi.',16,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(8,3,'Quae omnis','quae-omnis-nWPp',NULL,18,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL);
/*!40000 ALTER TABLE `article_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_tag`
--

DROP TABLE IF EXISTS `article_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article_tag` (
  `article_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `article_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `article_tag_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_tag`
--

LOCK TABLES `article_tag` WRITE;
/*!40000 ALTER TABLE `article_tag` DISABLE KEYS */;
INSERT INTO `article_tag` VALUES (1,1),(4,1),(5,1),(8,1),(9,1),(3,2),(6,2),(3,3),(6,3),(7,3),(9,3),(3,4),(7,4),(2,5),(8,5),(9,5),(10,5),(1,6),(2,7),(2,8),(8,8);
/*!40000 ALTER TABLE `article_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','published','scheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reading_time` int unsigned DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `published_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_author_id_foreign` (`author_id`),
  KEY `articles_status_published_at_index` (`status`,`published_at`),
  CONSTRAINT `articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,NULL,'Suscipit id enim minus inventore.','suscipit-id-enim-minus-inventore-eaVDA',NULL,'Nihil sed aut soluta non assumenda dolore. Eligendi et laborum voluptatibus sed mollitia doloribus.\n\nEum est optio est quo. Ipsum soluta suscipit fuga minus quod in. Doloremque aperiam repellat quisquam. Quas nostrum eos vel in.\n\nFugit commodi excepturi autem repudiandae. Enim et aliquid similique ea voluptatum et soluta. Et culpa aut id maxime.\n\nDignissimos non ut occaecati facere consequatur saepe. Excepturi aspernatur occaecati quia voluptas. Quas corrupti magni autem facilis est ipsum quia. Ut inventore officia dolorem expedita.\n\nIllo assumenda debitis quibusdam possimus voluptatem nihil. Error nihil temporibus totam est possimus. Repellat adipisci dolorem dolores dignissimos. Mollitia earum quam natus debitis cum. Aut blanditiis est omnis doloribus qui.','published',8,NULL,NULL,NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(2,NULL,'Consequuntur dolor nesciunt sint hic.','consequuntur-dolor-nesciunt-sint-hic-BCq4q','Aspernatur architecto voluptas quidem cumque ea atque sed et fugit blanditiis et eos voluptates dignissimos quo recusandae.','Non dolor saepe adipisci repellendus. Et ut quia repellendus nam minima veritatis. Et excepturi dolores quos totam.\n\nSunt exercitationem voluptas dicta nihil quisquam qui nulla. Et cupiditate suscipit itaque enim explicabo sed. Temporibus illum neque quo culpa reprehenderit corporis blanditiis. Sit fugiat eveniet beatae enim voluptas.\n\nAtque animi voluptates fuga autem sed officia. Nesciunt exercitationem sint recusandae quia id sit necessitatibus. Ut illo dicta accusamus sit. Saepe eveniet aut voluptatem aspernatur sit repellendus eos vitae.\n\nItaque enim error laborum et. Quis culpa aliquid laborum eos asperiores.\n\nQuisquam qui fuga alias. Nemo libero non atque. Necessitatibus odio pariatur magnam tempora unde omnis veniam rerum. Repellat natus iure sint nesciunt nihil.','published',8,'https://via.placeholder.com/1200x700.png/002299?text=business+laboriosam','Eligendi dolores adipisci nesciunt.',NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(3,NULL,'Sit quo ipsam temporibus sunt.','sit-quo-ipsam-temporibus-sunt-yPLB3','Repellendus tempora consectetur aut exercitationem similique explicabo quas non in et repellat sunt assumenda non quia sunt earum voluptatum aperiam perspiciatis ut.','Adipisci veniam aut qui aspernatur illo officiis. Provident porro ipsa ex illum odio. Rerum qui esse et omnis. Sint eos modi aspernatur voluptatibus et laboriosam. Itaque minima commodi quis ut sed minima eligendi.\n\nAmet quae quod voluptatem officiis ducimus animi aut autem. Vel sit aut odit est soluta saepe quasi quia. Repellendus quis ipsum ducimus voluptatem. Quia nemo delectus in suscipit hic. Fuga dicta quas quod commodi delectus.\n\nOfficia aliquam iusto deserunt reiciendis quia beatae. Ratione corrupti libero aut cum harum aut maxime. Reprehenderit aliquam ut cum repellat animi saepe.\n\nDistinctio natus perferendis ut reprehenderit. Ut hic dolores nisi magni voluptas accusantium. Maxime ducimus provident fugiat.\n\nQui blanditiis velit ut consectetur. Occaecati eos itaque soluta est non quis vero. Nobis reprehenderit occaecati perspiciatis adipisci. Ex voluptas et ut mollitia sunt enim.','published',6,NULL,'Quo nostrum aspernatur harum.','Est iure est sed consectetur voluptatem molestias assumenda quia eveniet sequi et reprehenderit qui.','2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(4,NULL,'Sint ipsa ut voluptas iusto.','sint-ipsa-ut-voluptas-iusto-GVXQF',NULL,'Totam eum iure ducimus voluptatem numquam repellat. Neque odio deleniti ullam eius voluptatem vel fuga. Ipsa animi dolor voluptatibus placeat numquam animi.\n\nQuas iste ut qui sequi minus necessitatibus. A dicta ipsa qui id dolores non. Dignissimos error voluptas quisquam dolorem voluptas voluptatem pariatur sint.\n\nLabore sed sint placeat et eum non. Non nobis quos sed qui ut. Ab pariatur culpa ratione enim recusandae voluptas et. Dolorem odio dignissimos aliquid magni dolor cumque sint voluptatem.\n\nRerum reiciendis nisi id occaecati. Id et dolorum iusto labore. Qui praesentium nemo aut blanditiis sed autem facere. Expedita autem error ab aliquam sit non enim.\n\nQui eius vel unde voluptatibus. Eum quis ut hic modi. Est velit quisquam cum eum qui ipsam. Ducimus possimus non at repellendus rerum ullam itaque. Corporis itaque eos odio architecto.','published',5,'https://via.placeholder.com/1200x700.png/00dd22?text=business+iste','Consequatur neque delectus autem.','Aspernatur soluta laborum non nihil a eum natus repellat laborum et praesentium.','2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(5,NULL,'Aut exercitationem doloribus quos rerum voluptatem saepe natus.','aut-exercitationem-doloribus-quos-rerum-voluptatem-saepe-natus-X71io',NULL,'Recusandae est eveniet quod qui possimus rerum. Ut in ut dolorum velit sed aut maiores sunt. Harum occaecati sit quos.\n\nFacere numquam sed ea qui. Vitae qui nesciunt officiis quod sit reprehenderit. Reiciendis error consequuntur repellendus fugiat est.\n\nFacere quibusdam nemo eum. Magni quaerat perspiciatis possimus ut aliquid quis facere illum. Eum perferendis iste inventore distinctio enim illum numquam. Saepe dolorem ipsam voluptatem enim deleniti amet iure.\n\nAutem aliquam sit nisi veritatis dignissimos. Porro veritatis quod rem corrupti eum maiores. Temporibus est ullam modi eos.\n\nEt vitae natus eaque ea eum quia totam. Inventore aut totam animi. Dolorum quod aspernatur dolores. Fugit dignissimos tenetur deleniti fugit.','published',2,NULL,NULL,NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(6,NULL,'Esse aut unde tempore rem reiciendis.','esse-aut-unde-tempore-rem-reiciendis-8sSu5',NULL,'Aliquam asperiores quia numquam nemo. Consequatur et nihil voluptatem quod consequatur. Aut neque officia impedit.\n\nEa et quos quod. Est aut neque aperiam et nam eaque. A nam consequatur atque eaque ut quo.\n\nVoluptas enim nulla necessitatibus doloribus aliquid nihil. Cupiditate fugit perferendis ipsum et. Provident vel velit aut perferendis. Animi alias eum mollitia omnis est.\n\nLabore suscipit mollitia facere saepe natus. Esse assumenda ad voluptas numquam. Voluptas rem ea aut atque.\n\nAliquam molestias voluptatem eius sint quasi aliquam. Recusandae doloribus delectus sit est. Animi quis dolore quia id dicta fugit mollitia. Repudiandae autem delectus delectus reiciendis.','published',2,'https://via.placeholder.com/1200x700.png/000011?text=business+officiis','Ea est optio ipsa laboriosam.',NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(7,NULL,'Qui et est recusandae et saepe.','qui-et-est-recusandae-et-saepe-q7cDV','Non eum quis eaque optio quam quia beatae rerum doloribus commodi ea.','A ad sit occaecati sunt ipsa tempora. Velit commodi rerum perferendis voluptates. Dolore laudantium velit mollitia in velit iste nulla. Deleniti nihil voluptatum quo ipsam temporibus est.\n\nDolores atque architecto quod consequatur. Accusamus sunt qui reprehenderit et illum id.\n\nDoloremque autem temporibus dignissimos est consequuntur error nobis rem. Autem doloremque quia sed blanditiis tempora quae dolorem.\n\nId quisquam inventore atque molestiae ipsum id. Aut et quasi accusantium praesentium natus. Nisi enim excepturi maiores voluptatum facilis.\n\nTotam unde aut provident quidem voluptate eligendi. Accusamus aut nihil tempora provident iste et. Et officia veniam praesentium corrupti. Est tempora iure officia cupiditate porro dolores quis. Deleniti sed iste fugit.','published',2,NULL,'Repudiandae velit ducimus optio.',NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(8,NULL,'Sed ab est necessitatibus.','sed-ab-est-necessitatibus-SqitA',NULL,'Eum neque quam maiores. Quis ut ipsum quisquam amet est itaque dolorem. Consequatur ut qui magni.\n\nProvident accusamus ut aut at. Voluptatum mollitia labore voluptatem quidem placeat. Praesentium ratione id laborum voluptatem quae sint nobis.\n\nNon quia neque nihil. Ut non eaque iure temporibus.\n\nIpsam distinctio in et pariatur. Tenetur quos aut et et minima. Blanditiis quod ab vitae quia. Voluptatem omnis vel fugiat laborum omnis soluta tempora nostrum.\n\nDucimus temporibus consequatur natus. Eos quos quos ea. Voluptatum ut quo nisi unde illo maiores quae.','published',6,'https://via.placeholder.com/1200x700.png/003366?text=business+nisi',NULL,'Ea ex pariatur accusantium maxime quos quisquam.','2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(9,NULL,'Porro deserunt temporibus molestiae.','porro-deserunt-temporibus-molestiae-wJNGV','Molestias repellat sequi et odio dolor adipisci aut rerum exercitationem totam voluptatum reprehenderit explicabo repellendus minus reprehenderit.','Numquam natus debitis quos qui temporibus totam eum. Accusamus a rerum aliquam quos autem. Ullam voluptates quasi ab.\n\nVitae exercitationem ea saepe dolorum qui recusandae ratione. Repudiandae eveniet qui incidunt nemo dicta. Voluptas provident nostrum similique.\n\nQuis iste numquam id vitae ut amet. Expedita ipsa voluptatibus est mollitia ducimus harum.\n\nEt nemo animi doloremque ipsum perferendis ab eligendi. Doloribus ratione eius tempore voluptatum harum. Consequatur facilis officia unde ad dolorum. Quidem corrupti incidunt id in non facere nostrum commodi.\n\nFuga ipsa modi quis enim amet a. Ut nesciunt atque fuga ut vel officiis. Voluptate hic ullam velit quia praesentium deleniti assumenda aut. Est cumque laudantium dolores possimus quia.','published',5,NULL,'Ut sed.',NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(10,NULL,'Aperiam saepe illum at iusto.','aperiam-saepe-illum-at-iusto-HfFee','Officia cum voluptate velit excepturi temporibus voluptates voluptate placeat quidem aspernatur quaerat doloribus possimus laboriosam animi unde pariatur velit consectetur et.','Omnis aspernatur in dolore blanditiis. Doloribus aut nam provident iure beatae voluptatem aut.\n\nEt voluptatum nesciunt atque rerum perspiciatis totam voluptatem. Et tenetur placeat distinctio suscipit.\n\nAut ipsum quia dolorem aut. Amet fugiat odit asperiores est ea sit non. Totam vitae ut veritatis dolorem culpa. Laborum accusantium voluptas id dolores aliquid dolor.\n\nQuidem corporis voluptatem sit. Qui et sed voluptatem magnam eligendi. Aspernatur odio fuga modi illo quasi vel rerum. Aspernatur et saepe voluptatum ratione neque ab.\n\nOdio harum dolor maiores commodi. Fuga aut voluptatem aspernatur consequatur omnis ipsum autem. At consequatur tenetur nihil qui itaque deserunt.','published',5,NULL,NULL,NULL,'2025-11-02 20:24:34',NULL,0,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34',NULL);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Johnson-Roberts','johnson-roberts-yxNL',NULL,'https://via.placeholder.com/200x200.png/00cc33?text=brand+ex',1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(2,'Lynch, Spencer and Ankunding','lynch-spencer-and-ankunding-w2Uc',NULL,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(3,'Ortiz, Senger and Bogisich','ortiz-senger-and-bogisich-KuGS','Consequatur quasi vel sunt ex incidunt.','https://via.placeholder.com/200x200.png/00bb11?text=brand+omnis',1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(4,'Ruecker Ltd','ruecker-ltd-NXMO',NULL,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(5,'Cassin, McClure and Pacocha','cassin-mcclure-and-pacocha-e9Zv',NULL,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(6,'Breitenberg, Conroy and Bernhard','breitenberg-conroy-and-bernhard-aTed','Modi quia ut sint non.',NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL);
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

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

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:1;',1762212948),('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1762212948;',1762212948);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

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

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `purchasable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchasable_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_gram` int unsigned NOT NULL DEFAULT '0',
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(16,2) NOT NULL DEFAULT '0.00',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_purchasable_type_purchasable_id_index` (`purchasable_type`,`purchasable_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (3,2,'App\\Models\\ProductVariant',8,'JGS3K5MVF4G4','XL / Taro',503,2,117285.57,234571.14,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,2,'App\\Models\\Product',1,'E1TWRZVBEH','Similique rerum voluptates',811,3,294255.61,882766.83,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,2,'App\\Models\\Product',3,'RWPUFLMU7B','Omnis eius adipisci',224,2,491081.44,982162.88,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,3,'App\\Models\\Product',9,'LCDOTSTCQT','Mollitia provident reiciendis',863,3,223147.24,669441.72,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,3,'App\\Models\\ProductVariant',2,'ITGG1AK0A1SR','S / Taro',483,2,158868.91,317737.82,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,3,'App\\Models\\ProductVariant',33,'YLQZKIRH9NHQ','XL / Original',238,1,85635.82,85635.82,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,3,'App\\Models\\ProductVariant',2,'ITGG1AK0A1SR','S / Taro',483,3,158868.91,476606.73,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,4,'App\\Models\\ProductVariant',68,'OCO1JDKDRBNQ','L / Matcha',674,2,261922.16,523844.32,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(11,4,'App\\Models\\Product',7,'JS1RRBVA6A','Animi architecto est',371,2,471128.77,942257.54,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(12,4,'App\\Models\\ProductVariant',40,'UINHIIWNAISX','XL / Original',287,3,131434.30,394302.90,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(13,4,'App\\Models\\Product',10,'VFVAD1EGTZ','Impedit hic in',1182,3,250900.20,752700.60,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(14,5,'App\\Models\\ProductVariant',57,'R5AE2R3CDC60','XL / Matcha',541,2,168252.64,336505.28,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(15,5,'App\\Models\\ProductVariant',12,'BZ9GNEOL8XTR','XL / Chocolate',647,2,95054.79,190109.58,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(16,5,'App\\Models\\ProductVariant',21,'DABLDSRP6BRV','M / Matcha',193,3,55303.01,165909.03,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(17,6,'App\\Models\\Product',34,'KM3TPPWLUM','Excepturi hic qui',321,2,29379.83,58759.66,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(18,7,'App\\Models\\Product',26,'EBUSSXQTRH','Voluptas commodi neque',1051,3,275909.64,827728.92,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(19,7,'App\\Models\\ProductVariant',19,'FOIROPKN0GXZ','XL / Matcha',754,1,161828.56,161828.56,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(20,7,'App\\Models\\ProductVariant',8,'JGS3K5MVF4G4','XL / Taro',503,3,117285.57,351856.71,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(21,8,'App\\Models\\Product',36,'TGMGDELWBW','Minima minus sunt',598,2,246394.34,492788.68,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(22,9,'App\\Models\\ProductVariant',55,'8PQ7EZ39IMBW','S / Matcha',661,1,25139.77,25139.77,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(23,9,'App\\Models\\Product',32,'7DM5XTVHA4','Temporibus et ea',365,2,368932.66,737865.32,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(24,9,'App\\Models\\Product',13,'PLINGPUDKM','Debitis porro magni',358,2,332144.57,664289.14,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(25,9,'App\\Models\\ProductVariant',15,'TJKQBZLAOXXA','S / Chocolate',321,1,233577.40,233577.40,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(26,10,'App\\Models\\ProductVariant',54,'MPINHRJJYM6S','L / Original',463,2,13247.59,26495.18,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(27,10,'App\\Models\\ProductVariant',17,'X5M1ZHFSEZ54','L / Taro',137,2,63401.48,126802.96,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(28,10,'App\\Models\\Product',23,'1N8PUPOEQB','Quasi corrupti temporibus',1117,1,93320.70,93320.70,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(29,10,'App\\Models\\ProductVariant',51,'8EEN5LVHXUNT','XL / Chocolate',456,1,153321.30,153321.30,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `address_id` bigint unsigned DEFAULT NULL,
  `voucher_id` bigint unsigned DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_address_id_foreign` (`address_id`),
  KEY `carts_voucher_id_foreign` (`voucher_id`),
  KEY `carts_customer_id_session_id_index` (`customer_id`,`session_id`),
  CONSTRAINT `carts_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `carts_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,1,NULL,'IDR',2,4,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,NULL,'IDR',4,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,3,NULL,'IDR',5,4,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,4,NULL,'IDR',8,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,5,NULL,'IDR',9,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,6,NULL,'IDR',12,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,7,NULL,'IDR',14,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,8,NULL,'IDR',15,4,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,9,NULL,'IDR',18,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,10,NULL,'IDR',20,NULL,'2025-11-09 20:24:33',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `article_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','spam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_article_id_foreign` (`article_id`),
  KEY `comments_customer_id_foreign` (`customer_id`),
  KEY `comments_parent_id_foreign` (`parent_id`),
  KEY `comments_status_index` (`status`),
  CONSTRAINT `comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,9,NULL,'Dovie McGlynn',NULL,'In odit omnis sapiente eveniet non voluptas eaque suscipit ab et et eum id.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(2,1,3,NULL,NULL,'dkessler@example.com','Totam sed ut et ducimus rerum fugiat quis possimus incidunt similique eaque vel officia omnis sint hic voluptatem totam ut magnam est sint voluptas.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(3,1,8,NULL,'Trent Koelpin',NULL,'Iste consequatur aut itaque et accusamus qui nobis distinctio illum tenetur illo beatae minima dolore numquam laboriosam temporibus deleniti et.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(4,2,2,NULL,'Kristoffer Kovacek','ydenesik@example.org','Impedit aut saepe sit quas dolore exercitationem in doloribus dolorem temporibus aspernatur modi laboriosam aut incidunt praesentium sunt voluptatem modi perspiciatis nulla doloribus ea odit harum.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(5,2,3,NULL,NULL,'amalia.rolfson@example.net','Et dolores cumque saepe aliquam eos ab iure inventore est veniam qui aperiam aperiam rerum ut placeat magnam ipsa esse sint esse explicabo doloribus accusamus consequatur aspernatur deleniti.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(6,3,10,NULL,'Miss Tess Marvin III',NULL,'Veniam mollitia quisquam nemo quas autem consectetur consectetur non est ut et ut repellendus non rem eos veniam a.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(7,3,5,NULL,'Lulu Carroll',NULL,'Corrupti repellendus culpa et iste ullam eveniet provident non aut tempora perferendis ad architecto ex assumenda in id et quis consequatur et soluta labore labore minima consequuntur ut.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(8,3,7,NULL,NULL,'raynor.lauriane@example.net','Eum enim atque modi et libero et iste maiores totam autem rerum illum quia dicta nisi et aliquid ducimus qui veritatis fugiat repudiandae modi maiores eum molestiae.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(9,4,5,NULL,NULL,NULL,'Ad nostrum sit quos aliquid reiciendis architecto architecto dolorem labore voluptatem asperiores ullam in dolor voluptas repellendus animi omnis laborum ut.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(10,4,1,NULL,'Alexander Littel DDS','lew.kling@example.com','Aut consequatur iusto sit itaque expedita enim omnis praesentium consequatur voluptate reiciendis voluptas atque quia et aut quo assumenda.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(11,4,1,NULL,NULL,'bernhard.boehm@example.com','Illo quo eum illum ex eos similique sequi fugiat ut dolorem ad repellat voluptates.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(12,5,1,NULL,NULL,NULL,'Sunt aut qui exercitationem doloremque est eaque eligendi numquam voluptatem porro aut autem atque nesciunt corporis assumenda nobis numquam quis neque.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(13,5,3,NULL,'Kenyon Marvin',NULL,'Odio ut aperiam ut quod illum ducimus doloremque doloribus incidunt explicabo exercitationem molestias qui iure consequatur atque temporibus sit ut fugit harum temporibus consequatur id recusandae.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(14,5,3,NULL,NULL,'domenica.parisian@example.com','Adipisci doloribus iure placeat hic error pariatur voluptatem rerum molestias doloribus voluptatem delectus quisquam maiores enim possimus et corrupti quod veniam et velit.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(15,6,4,NULL,NULL,NULL,'Rerum quidem ut ipsam autem sapiente fugiat et inventore voluptatem hic aspernatur blanditiis dignissimos sed alias vel adipisci expedita.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(16,7,4,NULL,'Julio Stiedemann DVM',NULL,'Excepturi velit perspiciatis non laboriosam est sit labore consequatur placeat qui saepe placeat laborum aut tempore ea minus ullam veritatis perferendis ut vel impedit voluptas in iste facere.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(17,8,9,NULL,'Prince Wyman','sstracke@example.net','Quia et autem laboriosam ea fugit dolores qui ipsam ut vel perferendis quae eum aliquid numquam sequi omnis saepe quis earum provident.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(18,9,1,NULL,NULL,NULL,'Qui tempore nihil nulla dicta corrupti alias debitis iste enim veniam vitae et qui quae eveniet.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(19,9,7,NULL,'Jeramy Russel',NULL,'Ut ut non sed voluptas quidem qui pariatur sequi soluta eligendi tempora libero ut id molestiae corrupti fugiat suscipit aut quidem tempore inventore.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(20,9,6,NULL,'Elliot Kohler MD',NULL,'Vero repellat delectus accusamus cumque quo nihil in incidunt illo eos quidem nihil.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(21,10,1,NULL,NULL,NULL,'Voluptas et suscipit quia perferendis ut in asperiores qui eius ut atque nesciunt occaecati cum.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(22,10,1,NULL,'Dr. Dale Kiehn',NULL,'Distinctio id quibusdam molestiae ratione veritatis culpa fuga dolor in nihil et nobis dolorem quae et beatae qui velit eligendi cum qui ut ratione ducimus omnis aut repudiandae.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL),(23,10,1,NULL,NULL,'pkreiger@example.net','Omnis sit veniam tempora distinctio maxime labore dolore sequi deleniti id corrupti harum blanditiis dolore qui.','approved','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Lawson Weissnat','emmitt.batz@example.net','2025-11-02 20:24:31','$2y$12$zIM0eBRIrMswk96RFFMafu/GymDjerLqiyamTkGRRArqmb8bh3Ira','y9NfMZiyKc','2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,'Elenor Haag IV','estell.ryan@example.net','2025-11-02 20:24:31','$2y$12$VbCdV/4PZKCsNc/a/qbVvuPa4oYKd6JXLt8O3v1chlMTzw6h5pw9O','es3cv25ZTb','2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,'Javonte Heller','xrowe@example.net','2025-11-02 20:24:32','$2y$12$rABqQkhDk5PHf07N.qLl0.2jOspCLcY8BlEcwmNUb3mx9dwxczTeq','AKyXlPZmHX','2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,'Tamia Hermann','runte.desiree@example.com','2025-11-02 20:24:32','$2y$12$xDxwP3d2tMD87KnLEDwT0O9R/5JZLZUy/JV9kG.ijBI3iOeDVPAde','Rp5UnthJ4K','2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,'Genoveva Hagenes','lafayette.weimann@example.com','2025-11-02 20:24:32','$2y$12$SlZR7GTT/qTiGm/K9RFfzeDB8f2mz4v29glp1xeiXuVh2eLzhBJ4W','bGfILMsNPz','2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,'Karli Frami','kdurgan@example.com','2025-11-02 20:24:32','$2y$12$cFMgROydEGT3y549nJPtzeeRO70voTYuRcoRe5q5q/YQPlGlyttym','TESYaDN0Dc','2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,'Joana Herman','pacocha.karianne@example.org','2025-11-02 20:24:32','$2y$12$ZnwPkjDp2bS3PZmpRi4.Dej.VRbrNMLCBtdS3HIImKFyNWXcb/N72','MzNZqxFBQw','2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,'Johnson O\'Kon','fvolkman@example.com','2025-11-02 20:24:33','$2y$12$emD7ffgZIpc8yqCq5jMslu3tOvvMhz2FNk9lFLeh.ECyDJHQg1Ytq','93qGVXlad2','2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,'Dr. Darwin Bartell I','lgibson@example.com','2025-11-02 20:24:33','$2y$12$uKcZNaftX21jKvv3B1Paa.SVJvuYHryFmTZ4FlG5JuZ33zv.x2rqq','IQ5MDkEGS3','2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,'Andres Grady','parisian.desiree@example.org','2025-11-02 20:24:33','$2y$12$vUDw3Vw3oL4jmdbzxuei5.y7Cj3biqdCQYYlmYs.gS8a3LjEOCMR6','lMCnQV4QOG','2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

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

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (17,'0001_01_01_000000_create_users_table',1),(18,'0001_01_01_000001_create_cache_table',1),(19,'0001_01_01_000002_create_jobs_table copy',1),(20,'0001_01_01_000003_create_ecommerce_table',1),(21,'2025_11_03_144517_create_pages_table',2),(22,'2025_11_03_155154_create_newsletters_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletters_email_unique` (`email`),
  KEY `newsletters_email_index` (`email`),
  KEY `newsletters_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
INSERT INTO `newsletters` VALUES (1,'test@example.com','active','2025-11-03 09:01:01',NULL,'2025-11-03 09:01:01','2025-11-03 09:01:01');
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `purchasable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchasable_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_gram` int unsigned NOT NULL DEFAULT '0',
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(16,2) NOT NULL DEFAULT '0.00',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_purchasable_type_purchasable_id_index` (`purchasable_type`,`purchasable_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,'App\\Models\\Product',9,'LCDOTSTCQT','Mollitia provident reiciendis',863,3,223147.24,669441.72,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,1,'App\\Models\\ProductVariant',2,'ITGG1AK0A1SR','S / Taro',483,2,158868.91,317737.82,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,1,'App\\Models\\ProductVariant',33,'YLQZKIRH9NHQ','XL / Original',238,1,85635.82,85635.82,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,1,'App\\Models\\ProductVariant',2,'ITGG1AK0A1SR','S / Taro',483,3,158868.91,476606.73,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,2,'App\\Models\\ProductVariant',54,'MPINHRJJYM6S','L / Original',463,2,13247.59,26495.18,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,2,'App\\Models\\ProductVariant',17,'X5M1ZHFSEZ54','L / Taro',137,2,63401.48,126802.96,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,2,'App\\Models\\Product',23,'1N8PUPOEQB','Quasi corrupti temporibus',1117,1,93320.70,93320.70,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,2,'App\\Models\\ProductVariant',51,'8EEN5LVHXUNT','XL / Chocolate',456,1,153321.30,153321.30,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,3,'App\\Models\\Product',9,'LCDOTSTCQT','Mollitia provident reiciendis',863,1,223147.24,223147.24,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,3,'App\\Models\\ProductVariant',62,'RM3KBLAE2WTR','S / Chocolate',641,1,246016.44,246016.44,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(11,4,'App\\Models\\ProductVariant',55,'8PQ7EZ39IMBW','S / Matcha',661,1,25139.77,25139.77,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(12,4,'App\\Models\\Product',32,'7DM5XTVHA4','Temporibus et ea',365,2,368932.66,737865.32,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(13,4,'App\\Models\\Product',13,'PLINGPUDKM','Debitis porro magni',358,2,332144.57,664289.14,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(14,4,'App\\Models\\ProductVariant',15,'TJKQBZLAOXXA','S / Chocolate',321,1,233577.40,233577.40,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(15,5,'App\\Models\\Product',34,'KM3TPPWLUM','Excepturi hic qui',321,2,29379.83,58759.66,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(16,6,'App\\Models\\Product',36,'TGMGDELWBW','Minima minus sunt',598,2,246394.34,492788.68,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(17,7,'App\\Models\\ProductVariant',57,'R5AE2R3CDC60','XL / Matcha',541,2,168252.64,336505.28,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(18,7,'App\\Models\\ProductVariant',12,'BZ9GNEOL8XTR','XL / Chocolate',647,2,95054.79,190109.58,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(19,7,'App\\Models\\ProductVariant',21,'DABLDSRP6BRV','M / Matcha',193,3,55303.01,165909.03,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(68,16,'App\\Models\\Product',9,'LCDOTSTCQT','Mollitia provident reiciendis',863,1,223147.24,223147.24,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(69,16,'App\\Models\\ProductVariant',62,'RM3KBLAE2WTR','S / Chocolate',641,1,246016.44,246016.44,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(70,16,'App\\Models\\ProductVariant',1,'HZWPHSPRJZF6','Similique rerum voluptates - M / Original',431,1,176765.20,176765.20,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(71,16,'App\\Models\\ProductVariant',55,'8PQ7EZ39IMBW','Temporibus et ea - S / Matcha',661,1,25139.77,25139.77,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(72,16,'App\\Models\\ProductVariant',17,'X5M1ZHFSEZ54','Mollitia provident reiciendis - L / Taro',137,1,63401.48,63401.48,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(73,16,'App\\Models\\ProductVariant',2,'ITGG1AK0A1SR','Similique rerum voluptates - S / Taro',483,1,158868.91,158868.91,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(74,17,'App\\Models\\ProductVariant',56,'Q2GKN2OLHA7D','Excepturi hic qui - L / Matcha',727,1,111699.26,111699.26,NULL,'2025-11-03 07:29:02','2025-11-03 07:29:02'),(75,18,'App\\Models\\ProductVariant',54,'MPINHRJJYM6S','Omnis eius molestiae - L / Original',463,1,13247.59,13247.59,NULL,'2025-11-03 07:36:02','2025-11-03 07:36:02'),(76,19,'App\\Models\\ProductVariant',56,'Q2GKN2OLHA7D','Excepturi hic qui - L / Matcha',727,1,111699.26,111699.26,NULL,'2025-11-03 07:38:44','2025-11-03 07:38:44');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `voucher_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_id` bigint unsigned DEFAULT NULL,
  `billing_address_snapshot` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address_id` bigint unsigned DEFAULT NULL,
  `shipping_address_snapshot` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `subtotal` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `shipping_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(16,2) NOT NULL DEFAULT '0.00',
  `shipping_courier` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(16,2) DEFAULT NULL,
  `shipping_etd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_total_gram` int unsigned NOT NULL DEFAULT '0',
  `status` enum('pending','awaiting_payment','paid','processing','shipped','completed','cancelled','refunded','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `placed_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_voucher_id_foreign` (`voucher_id`),
  KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ISSR-212071',3,4,'Prof. Anne Hintz','west.hailey@example.com','+1-864-871-8149',5,'Snapshot billing address at ordering time',5,'Snapshot shipping address at ordering time','IDR',1549422.09,23844.00,0.00,42814.57,1568392.66,'tiki','OKE',42814.57,'1-2 HARI',5242,'processing','2025-11-02 20:24:33','2025-11-02 20:24:33',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,'FXX6-018666',10,NULL,'Jaquelin Ratke','litzy06@example.com','848-734-8518',20,'Snapshot billing address at ordering time',20,'Snapshot shipping address at ordering time','IDR',399940.14,0.00,0.00,12772.91,412713.05,'jne','BEST',12772.91,'2-3 HARI',2773,'processing','2025-11-02 20:24:33','2025-11-02 20:24:33',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,'PLET-978168',1,4,'Corrine Cartwright','norwood.buckridge@example.net','1-641-989-8157',2,'Snapshot billing address at ordering time',2,'Snapshot shipping address at ordering time','IDR',469163.68,23844.00,0.00,32870.45,478190.13,'pos','YES',32870.45,'1-2 HARI',1504,'processing','2025-11-02 20:24:33','2025-11-02 20:24:33',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,'V9J2-436481',9,NULL,'Esteban Von','beth.funk@example.com','+18148357914',18,'Snapshot billing address at ordering time',18,'Snapshot shipping address at ordering time','IDR',1660871.63,0.00,0.00,22950.02,1683821.65,'jne','YES',22950.02,'3-5 HARI',2428,'processing','2025-11-02 20:24:33','2025-11-02 20:24:33',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,'DG4O-082711',6,NULL,'Natalia Swaniawski Sr.','fred.hintz@example.org','854-206-0656',12,'Snapshot billing address at ordering time',12,'Snapshot shipping address at ordering time','IDR',58759.66,0.00,0.00,40994.60,99754.26,'jne','BEST',40994.60,'2-3 HARI',642,'processing','2025-11-02 20:24:33','2025-11-02 20:24:33',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,'3HLW-854131',8,4,'Reina Hagenes','urban11@example.net','1-585-330-1153',15,'Snapshot billing address at ordering time',15,'Snapshot shipping address at ordering time','IDR',492788.68,23844.00,0.00,38050.66,506995.34,'jne','BEST',38050.66,'2-3 HARI',1196,'processing','2025-11-02 20:24:33','2025-11-02 20:24:34',NULL,'web',NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:34'),(7,'THCS-918444',5,NULL,'Ariane Olson','kaley.jaskolski@example.org','+18207293939',9,'Snapshot billing address at ordering time',9,'Snapshot shipping address at ordering time','IDR',692523.89,0.00,0.00,14939.82,707463.71,'pos','OKE',14939.82,'1-2 HARI',2955,'processing','2025-11-02 20:24:34','2025-11-02 20:24:34',NULL,'web',NULL,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(16,'INV20251103113519XA7Q',1,NULL,'Chasity Lang',NULL,'+1-325-205-2240',NULL,'8235 Amara Point Suite 116',NULL,'Jl. Prabu Kian Santang No.169A, RT.001/RW.004,','IDR',893339.04,0.00,0.00,0.00,902228.13,NULL,NULL,8889.09,NULL,0,'pending',NULL,NULL,NULL,'web',NULL,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(17,'INV20251103142902L2V0',1,NULL,'Chasity Lang',NULL,'+1-325-205-2240',NULL,'8235 Amara Point Suite 116',NULL,'Jl. Prabu Kian Santang No.169A, RT.001/RW.004,','IDR',111699.26,0.00,0.00,0.00,120588.35,NULL,NULL,8889.09,NULL,0,'pending',NULL,NULL,NULL,'web',NULL,NULL,'2025-11-03 07:29:02','2025-11-03 07:29:02'),(18,'INV20251103143602PCIG',1,NULL,'Chasity Lang',NULL,'+1-325-205-2240',NULL,'8235 Amara Point Suite 116',NULL,'Jl. Prabu Kian Santang No.169A, RT.001/RW.004,','IDR',13247.59,0.00,0.00,0.00,22136.68,NULL,NULL,8889.09,NULL,0,'pending',NULL,NULL,NULL,'web',NULL,NULL,'2025-11-03 07:36:02','2025-11-03 07:36:02'),(19,'INV20251103143844CKU3',1,NULL,'Chasity Lang',NULL,'+1-325-205-2240',NULL,'8235 Amara Point Suite 116',NULL,'Jl. Prabu Kian Santang No.169A, RT.001/RW.004,','IDR',111699.26,0.00,0.00,0.00,120588.35,NULL,NULL,8889.09,NULL,0,'pending',NULL,NULL,NULL,'web',NULL,NULL,'2025-11-03 07:38:44','2025-11-03 07:38:44');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sections` json DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `show_in_footer` tinyint(1) NOT NULL DEFAULT '0',
  `footer_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_slug_index` (`slug`),
  KEY `pages_is_active_show_in_footer_index` (`is_active`,`show_in_footer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'About Us','about','ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.','{\"goal\": {\"title\": \"OUR GOAL\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend\"}, \"hero\": {\"image\": \"images/banners/about-banner.webp\", \"title\": \"WELCOME TO ARHAM E-COMMERCE.\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.\"}, \"award\": {\"title\": \"WIN BEST ONLINE SHOP AT 2024\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.\"}, \"vision\": {\"title\": \"OUR VISSION\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend\"}, \"banners\": [{\"link\": \"/catalog\", \"image\": \"images/banners/home3-banner1.webp\"}, {\"link\": \"/catalog\", \"image\": \"images/banners/home3-banner2.webp\"}, {\"link\": \"/catalog\", \"image\": \"images/banners/home3-banner3.webp\"}], \"mission\": {\"title\": \"OUR MISSION\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend\"}, \"why_choose\": {\"title\": \"YOU CAN CHOOSE US BECAUSE WE ALWAYS PROVIDE IMPORTANCE...\", \"banner\": \"images/banners/home3-banner8.webp\", \"features\": [{\"title\": \"FAST DELIVERY\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"QUALITY PRODUCT\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"SECURE PAYMENT\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"MONEY BACK GUARNTEE\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"EASY ORDER TRACKING\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"FREE RETURN\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}, {\"title\": \"24/7 SUPPORT\", \"description\": \"ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing\"}], \"description\": \"ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born will give you a complete account of the system, and expound the actual\"}}','{\"keywords\": \"about, arham, e-commerce, online shop, mission, vision\", \"description\": \"Learn more about Arham E-Commerce - Your trusted online shopping destination\"}','about',1,1,1,'2025-11-03 07:51:04','2025-11-03 07:51:04'),(2,'Privacy Policy','privacy-policy','<h3>Privacy Policy</h3><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h4>Information We Collect</h4><p>We collect information that you provide directly to us when you create an account, make a purchase, or contact us.</p>',NULL,'{\"keywords\": \"privacy, policy, data protection, security\", \"description\": \"Read our privacy policy to understand how we protect your data\"}','default',1,1,2,'2025-11-03 07:51:04','2025-11-03 07:51:04'),(3,'Terms & Conditions','terms-conditions','<h3>Terms & Conditions</h3><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h4>Use License</h4><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p>',NULL,'{\"keywords\": \"terms, conditions, agreement, rules\", \"description\": \"Read our terms and conditions before using our services\"}','default',1,1,3,'2025-11-03 07:51:04','2025-11-03 07:51:04'),(4,'FAQ','faq','<h3>Frequently Asked Questions</h3><p>Find answers to common questions about our products and services.</p>','{\"faqs\": [{\"answer\": \"Simply browse our catalog, add items to your cart, and proceed to checkout.\", \"question\": \"How do I place an order?\"}, {\"answer\": \"We accept credit cards, bank transfers, and various e-wallet payments through Midtrans.\", \"question\": \"What payment methods do you accept?\"}, {\"answer\": \"Shipping typically takes 2-5 business days depending on your location.\", \"question\": \"How long does shipping take?\"}, {\"answer\": \"Yes, we offer a 7-day return policy for most products. See our return policy for details.\", \"question\": \"Can I return a product?\"}]}','{\"keywords\": \"faq, questions, help, support\", \"description\": \"Frequently asked questions about Arham E-Commerce\"}','default',1,1,4,'2025-11-03 07:51:04','2025-11-03 07:51:04'),(5,'Contact Us','contact','<h3>Get In Touch</h3><p>Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.</p>','{\"contact_info\": {\"email\": \"info@arham-ecommerce.com\", \"hours\": \"Monday - Friday: 9:00 AM - 6:00 PM\", \"phone\": \"+62 812-3456-7890\", \"address\": \"Jl. Example No. 123, Jakarta, Indonesia\"}}','{\"keywords\": \"contact, support, help, customer service\", \"description\": \"Contact Arham E-Commerce for any questions or support\"}','default',1,1,5,'2025-11-03 07:51:04','2025-11-03 07:51:04');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_logs`
--

DROP TABLE IF EXISTS `payment_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification',
  `headers` json DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occurred_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_logs_payment_id_foreign` (`payment_id`),
  KEY `payment_logs_order_id_foreign` (`order_id`),
  CONSTRAINT `payment_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_logs_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_logs`
--

LOCK TABLES `payment_logs` WRITE;
/*!40000 ALTER TABLE `payment_logs` DISABLE KEYS */;
INSERT INTO `payment_logs` VALUES (1,1,1,'notification','{\"X-Signature\": \"c953cedde467360e9abc78f509d4a56d72465001fcf370cc4c7ae66340a834ce\"}','{\"status\": \"ok\"}','29.110.85.221','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,2,'notification','{\"X-Signature\": \"30d5ff24f4d37875c1b48ca8d0ae6db7932753f1f73aafc1071aa444b7b59509\"}','{\"status\": \"ok\"}','171.26.131.210','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,3,3,'notification','{\"X-Signature\": \"c64f70f80ee4c96c020b6f963d417106b9f50b6e30560c8821ce233e44ed3784\"}','{\"status\": \"ok\"}','121.25.100.198','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,4,4,'notification','{\"X-Signature\": \"b5137cab1eca17cbe47fc8a5bfc5712b43f148b99836570fe969b58297f959d8\"}','{\"status\": \"ok\"}','235.27.115.165','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,5,5,'notification','{\"X-Signature\": \"99a250f173bca438779aac48ba9d9ff19362d22331dc3686823117c532661e2f\"}','{\"status\": \"ok\"}','96.36.251.21','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,6,6,'notification','{\"X-Signature\": \"f378bb1e45d30471035895b6a21617fac2fda49fdd5d9fb364eb42d5ad7ac896\"}','{\"status\": \"ok\"}','81.62.99.155','2025-11-02 20:24:34','2025-11-02 20:24:34','2025-11-02 20:24:34'),(7,7,7,'notification','{\"X-Signature\": \"6afa91538e423e89cc6e855e86b5b53940182cf75b3d0061c48769f2c6b3f772\"}','{\"status\": \"ok\"}','32.89.83.63','2025-11-02 20:24:34','2025-11-02 20:24:34','2025-11-02 20:24:34'),(12,12,NULL,'notification',NULL,'{\"method\": \"manual_transfer\"}',NULL,'2025-11-03 11:35:19','2025-11-03 04:35:19','2025-11-03 04:35:19'),(13,13,NULL,'notification',NULL,'{\"method\": \"manual_transfer\"}',NULL,'2025-11-03 14:29:02','2025-11-03 07:29:02','2025-11-03 07:29:02'),(14,14,NULL,'notification',NULL,'{\"method\": \"midtrans\"}',NULL,'2025-11-03 14:36:02','2025-11-03 07:36:02','2025-11-03 07:36:02'),(15,15,NULL,'notification',NULL,'{\"method\": \"midtrans\"}',NULL,'2025-11-03 14:38:44','2025-11-03 07:38:44','2025-11-03 07:38:44');
/*!40000 ALTER TABLE `payment_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `midtrans_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_status` enum('authorize','capture','settlement','pending','deny','cancel','expire','failure','refund','partial_refund','chargeback','partial_chargeback') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fraud_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_amount` decimal(16,2) DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `transaction_time` timestamp NULL DEFAULT NULL,
  `settlement_time` timestamp NULL DEFAULT NULL,
  `expiry_time` timestamp NULL DEFAULT NULL,
  `va_numbers` json DEFAULT NULL,
  `permata_va_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biller_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `masked_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actions` json DEFAULT NULL,
  `raw_response` json DEFAULT NULL,
  `refund_amount` decimal(16,2) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_customer_id_foreign` (`customer_id`),
  KEY `payments_midtrans_transaction_id_index` (`midtrans_transaction_id`),
  KEY `payments_order_id_ref_index` (`order_id_ref`),
  KEY `payments_transaction_status_index` (`transaction_status`),
  CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,3,'midtrans','e0929845-3022-3e6c-8ea0-f0ff6a802d91','DJ3KJ009733','settlement','qris',NULL,1568392.66,'IDR','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-03 20:24:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0LlbAp8VySBO7xDPFEz9bh8wpgUASZ0L',NULL,NULL,NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,10,'midtrans','28f738eb-43e4-35d2-8c84-a0c5364bdfb9','USOLE696051','settlement','qris',NULL,412713.05,'IDR','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-03 20:24:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'99RasU8wlBPgfxVn7JY0yGfN6FjN4e0e',NULL,NULL,NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,3,1,'midtrans','592bfbde-be8a-39ca-bd7f-64821df7e3a8','TKXEO298936','settlement','credit_card',NULL,478190.13,'IDR','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-03 20:24:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2R5zsi8A46JZ5TiAaSATqcUUZdFSkrqS',NULL,NULL,NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,4,9,'midtrans','9d0341e5-d2d7-3936-b714-35440dcf781e','ATI55856030','settlement','credit_card',NULL,1683821.65,'IDR','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-03 20:24:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'QMe6iPgcsN0o5KPOdABufT9HRLFiWQ70',NULL,NULL,NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,5,6,'midtrans','2684717b-6516-3110-8707-09ec7f1588b0','VWL9D330737','settlement','qris',NULL,99754.26,'IDR','2025-11-02 20:24:33','2025-11-02 20:24:33','2025-11-03 20:24:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'37krxmnwmApUhpbLM7eBFQYlA9pHAdec',NULL,NULL,NULL,NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,6,8,'midtrans','b0d79f0d-b003-3e3c-90a8-c7ccded20dd3','Q6OSU362175','settlement','qris',NULL,506995.34,'IDR','2025-11-02 20:24:34','2025-11-02 20:24:34','2025-11-03 20:24:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'7pGw7qdDxhfJ8lv6bzgMwTVGOjq2suYG',NULL,NULL,NULL,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(7,7,5,'midtrans','c0c6a77a-bcb6-359a-b576-9a61bbefe801','DRWRQ428679','settlement','bank_transfer',NULL,707463.71,'IDR','2025-11-02 20:24:34','2025-11-02 20:24:34','2025-11-03 20:24:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'OBzmoWWiz6L08PC2tZOCwBw9ajYiqeRe',NULL,NULL,NULL,NULL,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(12,16,NULL,'midtrans',NULL,NULL,NULL,NULL,NULL,902228.13,'IDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(13,17,1,'midtrans',NULL,NULL,'pending',NULL,NULL,120588.35,'IDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-03 07:29:02','2025-11-03 07:29:02'),(14,18,1,'midtrans','INV20251103143602PCIG','INV20251103143602PCIG','pending',NULL,NULL,22136.68,'IDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-03 07:36:02','2025-11-03 07:36:02'),(15,19,1,'midtrans','INV20251103143844CKU3','INV20251103143844CKU3','pending',NULL,NULL,120588.35,'IDR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-03 07:38:44','2025-11-03 07:38:44');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`),
  KEY `product_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,NULL,'Provident tempora','provident-tempora-QuDU',NULL,NULL,1,14,'{\"seo\": [\"dolores\", \"quibusdam\", \"quos\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(2,NULL,'Saepe harum','saepe-harum-55JM','Quisquam explicabo vero nemo animi exercitationem at ducimus quam. Tempore soluta labore est voluptas quibusdam. Et ut eos tenetur enim ea necessitatibus fugiat.',NULL,1,5,'{\"seo\": [\"ad\", \"qui\", \"et\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(3,NULL,'Distinctio sit','distinctio-sit-AHi3',NULL,NULL,1,5,'{\"seo\": [\"dolore\", \"optio\", \"in\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(4,NULL,'Quia ad','quia-ad-LyYl',NULL,'https://via.placeholder.com/800x600.png/004466?text=food+ipsa',1,12,'{\"seo\": [\"molestias\", \"ab\", \"sunt\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(5,NULL,'Repellat sequi','repellat-sequi-lKvn',NULL,'https://via.placeholder.com/800x600.png/00eecc?text=food+labore',1,14,'{\"seo\": [\"qui\", \"dolores\", \"vel\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(6,1,'Qui enim','qui-enim-qQEu','Aut asperiores nulla qui distinctio enim nam. Aut maxime explicabo aspernatur voluptate rerum eligendi iste. Modi accusantium id rem. Maxime non nulla omnis distinctio ut.',NULL,1,20,'{\"seo\": [\"maiores\", \"fuga\", \"inventore\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(7,1,'Voluptas ea','voluptas-ea-VAgE',NULL,'https://via.placeholder.com/800x600.png/00bb66?text=food+fugiat',1,2,'{\"seo\": [\"accusantium\", \"non\", \"delectus\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(8,2,'Ad voluptas','ad-voluptas-VrzO','Aliquam consequuntur cum molestiae facilis aperiam necessitatibus. Est fuga quisquam et repellendus harum placeat suscipit. Saepe beatae ea exercitationem quas nemo sit qui.','https://via.placeholder.com/800x600.png/007799?text=food+eligendi',1,15,'{\"seo\": [\"odio\", \"doloribus\", \"sed\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(9,2,'Dolorum ea','dolorum-ea-6jYd',NULL,'https://via.placeholder.com/800x600.png/00ff55?text=food+voluptatibus',1,18,'{\"seo\": [\"aut\", \"atque\", \"deleniti\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(10,2,'Ipsam et','ipsam-et-NN2Z','Vel deleniti nam velit eveniet numquam fuga rem. Quia et veritatis rem dolorem libero itaque sequi. Voluptatum qui expedita similique et distinctio eum.','https://via.placeholder.com/800x600.png/00aabb?text=food+et',1,6,'{\"seo\": [\"et\", \"voluptas\", \"eos\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(11,2,'Aliquam quidem','aliquam-quidem-MnTY','Culpa at et sit. Ducimus esse eos quia dolore sint. Sint est ut tempora in. Aliquam eius aut ut sit autem sint unde sit.','https://via.placeholder.com/800x600.png/0011aa?text=food+veritatis',1,14,'{\"seo\": [\"culpa\", \"voluptas\", \"facere\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(12,3,'Repellendus incidunt','repellendus-incidunt-tdYS',NULL,NULL,1,14,'{\"seo\": [\"velit\", \"beatae\", \"nostrum\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(13,3,'Beatae ipsa','beatae-ipsa-x7EC',NULL,'https://via.placeholder.com/800x600.png/000033?text=food+quo',1,7,'{\"seo\": [\"voluptate\", \"illum\", \"aut\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(14,3,'Eius laboriosam','eius-laboriosam-R3L4','Laboriosam quo dolores at libero. Sit quidem deserunt a ut quod. Aperiam ipsa et rem ab provident dicta maiores sunt. Excepturi dolor voluptatibus sunt dolor.',NULL,1,1,'{\"seo\": [\"praesentium\", \"laudantium\", \"vero\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(15,3,'Minima dolore','minima-dolore-mszN',NULL,'https://via.placeholder.com/800x600.png/00ee99?text=food+eum',1,9,'{\"seo\": [\"consectetur\", \"molestias\", \"neque\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(16,4,'Esse occaecati','esse-occaecati-W3sb','Voluptatem quasi esse itaque esse provident aspernatur. Voluptatum provident aperiam amet natus ut itaque. Quam molestiae soluta culpa atque consequuntur quas quidem. Quidem et itaque et voluptatibus qui omnis qui.','https://via.placeholder.com/800x600.png/0022dd?text=food+molestiae',1,5,'{\"seo\": [\"est\", \"assumenda\", \"natus\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(17,4,'Quod sint','quod-sint-l9pC','Ut alias quidem aut fugit. Placeat ratione molestiae vel atque ratione. Tempora enim quidem sed voluptatem consequatur voluptate eos. Et facilis perspiciatis beatae quae reiciendis.','https://via.placeholder.com/800x600.png/00bb55?text=food+quos',1,13,'{\"seo\": [\"vel\", \"nisi\", \"tenetur\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(18,4,'Qui vitae','qui-vitae-FbQn',NULL,'https://via.placeholder.com/800x600.png/0077dd?text=food+maiores',1,0,'{\"seo\": [\"eum\", \"fugiat\", \"est\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(19,5,'Sit est','sit-est-hitn','Sit enim voluptatem veritatis deserunt ducimus. Placeat dolorum est aut officiis totam error.','https://via.placeholder.com/800x600.png/0044ff?text=food+fuga',1,13,'{\"seo\": [\"consequuntur\", \"omnis\", \"quaerat\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(20,5,'Rerum sint','rerum-sint-MT08',NULL,'https://via.placeholder.com/800x600.png/004422?text=food+quas',1,0,'{\"seo\": [\"voluptas\", \"et\", \"quis\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(21,5,'Quo rerum','quo-rerum-jqKP','Eveniet non odit quidem dicta dolor. Cumque laudantium omnis quas in odio. Non laudantium qui laudantium accusamus voluptatibus.',NULL,1,9,'{\"seo\": [\"unde\", \"alias\", \"quam\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(22,5,'Natus autem','natus-autem-zTFo','In dolores vitae culpa id cum libero voluptas. Ratione explicabo fugit quaerat inventore quae sit veritatis. Quas ullam deserunt facere fuga. Debitis repellat ipsam commodi non.','https://via.placeholder.com/800x600.png/0088ee?text=food+et',1,11,'{\"seo\": [\"aut\", \"dolore\", \"eius\"]}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL);
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_category_product`
--

DROP TABLE IF EXISTS `product_category_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_category_product` (
  `product_id` bigint unsigned NOT NULL,
  `product_category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`product_category_id`),
  KEY `product_category_product_product_category_id_foreign` (`product_category_id`),
  CONSTRAINT `product_category_product_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_category_product`
--

LOCK TABLES `product_category_product` WRITE;
/*!40000 ALTER TABLE `product_category_product` DISABLE KEYS */;
INSERT INTO `product_category_product` VALUES (4,6),(16,6),(29,6),(25,7),(26,7),(28,7),(34,7),(36,7),(2,8),(4,8),(10,8),(20,8),(21,8),(24,8),(16,9),(7,10),(26,10),(17,11),(19,11),(13,12),(25,12),(38,12),(39,12),(8,13),(22,13),(25,13),(33,13),(1,14),(5,14),(18,14),(19,14),(35,14),(40,14),(6,15),(9,15),(12,15),(18,15),(23,15),(34,15),(12,16),(22,16),(30,16),(19,17),(38,17),(7,18),(11,18),(16,18),(27,18),(28,18),(32,18),(2,19),(4,19),(12,19),(14,19),(15,19),(29,19),(39,19),(18,20),(20,20),(24,20),(10,21),(24,21),(31,21),(34,21),(37,21),(38,21),(3,22),(10,22),(26,22);
/*!40000 ALTER TABLE `product_category_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(2,1,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(3,1,'products/placeholder.jpg','Omnis sed exercitationem quos.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(4,1,'products/placeholder.jpg','Ab nisi eum.',0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(5,2,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(6,2,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(7,3,'products/placeholder.jpg','Qui accusantium perspiciatis velit.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(8,3,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(9,4,'products/placeholder.jpg','Vel iste consequatur.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(10,4,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(11,4,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(12,4,'products/placeholder.jpg','Impedit rem sed et.',0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(13,5,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(14,5,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(15,5,'products/placeholder.jpg','Dicta ipsa excepturi recusandae.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(16,6,'products/placeholder.jpg','Vel ea aut qui.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(17,6,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(18,6,'products/placeholder.jpg','Et harum dolore.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(19,7,'products/placeholder.jpg','Eius ducimus dolores ut.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(20,7,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(21,8,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(22,8,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(23,8,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(24,8,'products/placeholder.jpg','Ea molestiae rerum.',0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(25,9,'products/placeholder.jpg','Molestiae quos.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(26,9,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(27,9,'products/placeholder.jpg','Et maiores soluta ex minus.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(28,10,'products/placeholder.jpg','Unde error necessitatibus non.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(29,10,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(30,11,'products/placeholder.jpg','In perferendis esse.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(31,11,'products/placeholder.jpg','Ad nisi dignissimos.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(32,11,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(33,12,'products/placeholder.jpg','Libero itaque ea ducimus.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(34,12,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(35,12,'products/placeholder.jpg','Deserunt nihil eveniet.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(36,13,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(37,13,'products/placeholder.jpg','Et dolores.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(38,14,'products/placeholder.jpg','Sit dolorem magni.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(39,14,'products/placeholder.jpg','Sunt id ut.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(40,14,'products/placeholder.jpg','Quam molestiae placeat tempore.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(41,14,'products/placeholder.jpg',NULL,0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(42,15,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(43,15,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(44,16,'products/placeholder.jpg','Rerum qui id.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(45,16,'products/placeholder.jpg','In enim porro voluptatem.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(46,16,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(47,17,'products/placeholder.jpg','Dolorum totam quod iste.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(48,17,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(49,18,'products/placeholder.jpg','Et quaerat ea.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(50,18,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(51,19,'products/placeholder.jpg','Sequi cum non.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(52,19,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(53,19,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(54,19,'products/placeholder.jpg','Ut deserunt et architecto.',0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(55,20,'products/placeholder.jpg','Quas dolores.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(56,20,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(57,20,'products/placeholder.jpg','Numquam vel ex quis.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(58,21,'products/placeholder.jpg','Quaerat libero doloribus sunt.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(59,21,'products/placeholder.jpg','Quasi officia aut vel perspiciatis.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(60,21,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(61,22,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(62,22,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(63,22,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(64,23,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(65,23,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(66,24,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(67,24,'products/placeholder.jpg','Totam esse est eius.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(68,24,'vproducts/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(69,25,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(70,25,'products/placeholder.jpg','Qui eum consequatur.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(71,26,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(72,26,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(73,26,'products/placeholder.jpg','Sunt fugiat vel sequi.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(74,27,'products/placeholder.jpg','Voluptatem voluptate similique.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(75,27,'products/placeholder.jpg','Odit maxime omnis.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(76,27,'vproducts/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(77,27,'products/placeholder.jpg','Minima non.',0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(78,28,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(79,28,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(80,29,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(81,29,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(82,29,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(83,29,'products/placeholder.jpg',NULL,0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(84,30,'products/placeholder.jpg','Tenetur quia praesentium.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(85,30,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(86,30,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(87,30,'products/placeholder.jpg',NULL,0,3,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(88,31,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(89,31,'products/placeholder.jpg','Dolor ea voluptatem voluptatem.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(90,32,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(91,32,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(92,33,'products/placeholder.jpg','Repellendus ex molestias nisi.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(93,33,'products/placeholder.jpg','Modi exercitationem quod.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(94,33,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(95,34,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(96,34,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(97,35,'products/placeholder.jpg','Magni quibusdam laboriosam.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(98,35,'products/placeholder.jpg','Tenetur nihil animi.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(99,35,'products/placeholder.jpg','Quod aut inventore quod ut.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(100,36,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(101,36,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(102,36,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(103,37,'products/placeholder.jpg','Quibusdam est quisquam praesentium.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(104,37,'products/placeholder.jpg',NULL,0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(105,38,'products/placeholder.jpg',NULL,1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(106,38,'products/placeholder.jpg','Dolores odit doloribus et.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(107,38,'products/placeholder.jpg','Amet quas.',0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(108,39,'products/placeholder.jpg','Impedit esse maxime.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(109,39,'products/placeholder.jpg','Id quo qui voluptatem.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(110,40,'products/placeholder.jpg','Necessitatibus animi quo.',1,0,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(111,40,'products/placeholder.jpg','Animi repudiandae fuga.',0,1,'2025-11-02 20:24:31','2025-11-02 20:24:31'),(112,40,'products/placeholder.jpg',NULL,0,2,'2025-11-02 20:24:31','2025-11-02 20:24:31');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `reviewable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reviewable_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_customer_id_foreign` (`customer_id`),
  KEY `product_reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`),
  KEY `product_reviews_parent_id_foreign` (`parent_id`),
  KEY `product_reviews_rating_status_index` (`rating`,`status`),
  CONSTRAINT `product_reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_reviews` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json DEFAULT NULL,
  `weight_gram` int unsigned NOT NULL DEFAULT '0',
  `price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(16,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'HZWPHSPRJZF6','M / Original','{\"size\": \"M\", \"style\": \"Matcha\"}',431,57840.30,176765.20,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(2,1,'ITGG1AK0A1SR','S / Taro','{\"size\": \"M\", \"style\": \"Taro\"}',483,158868.91,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(3,1,'9RNTJPEFSGBO','L / Original','{\"size\": \"XL\", \"style\": \"Matcha\"}',175,120425.51,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(4,2,'T2UVQHD648YT','L / Taro','{\"size\": \"XL\", \"style\": \"Chocolate\"}',346,118656.97,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(5,2,'1C93KHFRPM6M','XL / Taro','{\"size\": \"L\", \"style\": \"Taro\"}',158,145426.40,217698.91,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(6,2,'X4G2C7R8WUC8','M / Taro','{\"size\": \"M\", \"style\": \"Original\"}',576,106502.79,112970.20,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(7,3,'H2KDRXZBUAKU','L / Matcha','{\"size\": \"M\", \"style\": \"Matcha\"}',357,65346.85,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(8,3,'JGS3K5MVF4G4','XL / Taro','{\"size\": \"XL\", \"style\": \"Chocolate\"}',503,117285.57,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(9,3,'XXFQKBLRARBS','M / Chocolate','{\"size\": \"L\", \"style\": \"Taro\"}',280,134152.01,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(10,4,'CMHRJH34EVTS','S / Taro','{\"size\": \"M\", \"style\": \"Chocolate\"}',694,88256.62,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(11,6,'D0X5NICYXQ6M','L / Taro','{\"size\": \"L\", \"style\": \"Taro\"}',298,142612.80,153445.76,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(12,6,'BZ9GNEOL8XTR','XL / Chocolate','{\"size\": \"S\", \"style\": \"Matcha\"}',647,287716.30,95054.79,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(13,6,'BM1VZRVR4SSN','XL / Chocolate','{\"size\": \"S\", \"style\": \"Taro\"}',417,181263.99,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(14,7,'PABSOEYDDLAV','XL / Original','{\"size\": \"XL\", \"style\": \"Matcha\"}',543,294217.31,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(15,8,'TJKQBZLAOXXA','S / Chocolate','{\"size\": \"S\", \"style\": \"Taro\"}',321,162471.05,233577.40,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(16,8,'BKJVC5B2OO9R','XL / Chocolate','{\"size\": \"S\", \"style\": \"Original\"}',203,271101.24,209431.11,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(17,9,'X5M1ZHFSEZ54','L / Taro','{\"size\": \"S\", \"style\": \"Chocolate\"}',137,63401.48,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(18,9,'L48ZK2BIQYKP','L / Matcha','{\"size\": \"XL\", \"style\": \"Taro\"}',258,168235.16,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(19,9,'FOIROPKN0GXZ','XL / Matcha','{\"size\": \"S\", \"style\": \"Taro\"}',754,161828.56,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(20,10,'SIJVUXJNNYV7','S / Taro','{\"size\": \"M\", \"style\": \"Taro\"}',530,254413.26,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(21,10,'DABLDSRP6BRV','M / Matcha','{\"size\": \"S\", \"style\": \"Matcha\"}',193,55303.01,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(22,10,'YEHXD1AXWSMQ','XL / Taro','{\"size\": \"S\", \"style\": \"Matcha\"}',744,31712.31,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(23,11,'HW6C5ZW84SQL','S / Original','{\"size\": \"L\", \"style\": \"Original\"}',787,263320.37,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(24,12,'CVWVP5T3XOAG','S / Taro','{\"size\": \"L\", \"style\": \"Chocolate\"}',162,22933.63,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(25,12,'P2MSN8KKZB9T','S / Matcha','{\"size\": \"S\", \"style\": \"Chocolate\"}',348,294402.29,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(26,12,'DRJLMSJAMW1X','S / Original','{\"size\": \"S\", \"style\": \"Original\"}',430,75011.30,113902.38,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(27,13,'5J1MLDD1TCAU','L / Matcha','{\"size\": \"L\", \"style\": \"Original\"}',511,24679.29,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(28,13,'S7Q2IXXVUXOB','M / Chocolate','{\"size\": \"S\", \"style\": \"Matcha\"}',346,296820.75,68809.60,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(29,13,'HDZDH5UADT6U','M / Chocolate','{\"size\": \"XL\", \"style\": \"Original\"}',344,74270.72,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(30,14,'IMBMVDFNQNRK','S / Chocolate','{\"size\": \"M\", \"style\": \"Chocolate\"}',622,92630.69,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(31,15,'IHXYNARQKZGO','XL / Chocolate','{\"size\": \"XL\", \"style\": \"Matcha\"}',350,49927.45,191405.45,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(32,15,'UYRQBSOWUCAW','XL / Original','{\"size\": \"XL\", \"style\": \"Original\"}',500,183401.51,170635.67,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(33,16,'YLQZKIRH9NHQ','XL / Original','{\"size\": \"L\", \"style\": \"Original\"}',238,85635.82,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(34,16,'ZKVSRP4SEXET','L / Chocolate','{\"size\": \"S\", \"style\": \"Original\"}',672,55918.76,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(35,17,'ECWKEQVY8PCV','S / Chocolate','{\"size\": \"M\", \"style\": \"Original\"}',596,64509.02,240907.02,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(36,17,'SL92DQADPVNL','L / Chocolate','{\"size\": \"M\", \"style\": \"Chocolate\"}',457,65662.10,63381.97,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(37,18,'XU7OVFN4M0WD','M / Taro','{\"size\": \"M\", \"style\": \"Original\"}',787,108917.57,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(38,18,'KZ33IMBYUXOX','S / Taro','{\"size\": \"S\", \"style\": \"Original\"}',740,119833.52,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(39,20,'L7DXLVH9T2OE','S / Chocolate','{\"size\": \"M\", \"style\": \"Chocolate\"}',308,203579.73,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(40,21,'UINHIIWNAISX','XL / Original','{\"size\": \"S\", \"style\": \"Matcha\"}',287,239379.18,131434.30,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(41,21,'T1RKRWZWPPBM','XL / Taro','{\"size\": \"L\", \"style\": \"Taro\"}',344,138685.43,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(42,22,'FOSGV0E1YYA7','M / Chocolate','{\"size\": \"S\", \"style\": \"Original\"}',387,192723.31,191349.94,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(43,22,'ZONVVHFZZO9R','M / Original','{\"size\": \"XL\", \"style\": \"Taro\"}',499,289818.90,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(44,23,'JCUO4MO0VMY1','XL / Taro','{\"size\": \"S\", \"style\": \"Chocolate\"}',442,13619.65,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(45,24,'JPKGDBLK0CKP','S / Taro','{\"size\": \"S\", \"style\": \"Chocolate\"}',328,48978.45,128772.25,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(46,24,'8AH9FWXUBP02','M / Matcha','{\"size\": \"S\", \"style\": \"Matcha\"}',344,187099.49,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(47,26,'6K4WUT2UAOCY','M / Original','{\"size\": \"L\", \"style\": \"Taro\"}',157,114687.31,104660.96,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(48,28,'LUPOTPVZZKSV','L / Matcha','{\"size\": \"M\", \"style\": \"Chocolate\"}',518,214864.92,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(49,28,'FDNR8GOMXFFS','L / Taro','{\"size\": \"M\", \"style\": \"Matcha\"}',215,75693.24,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(50,28,'TTTNPRIRUW9Q','L / Matcha','{\"size\": \"XL\", \"style\": \"Matcha\"}',471,105536.63,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(51,29,'8EEN5LVHXUNT','XL / Chocolate','{\"size\": \"M\", \"style\": \"Original\"}',456,153321.30,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(52,29,'KLIUYOJBVWOU','L / Chocolate','{\"size\": \"S\", \"style\": \"Chocolate\"}',632,51697.99,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(53,29,'XAD7O2NHDIOY','XL / Taro','{\"size\": \"L\", \"style\": \"Matcha\"}',676,126795.09,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(54,31,'MPINHRJJYM6S','L / Original','{\"size\": \"M\", \"style\": \"Matcha\"}',463,13247.59,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(55,32,'8PQ7EZ39IMBW','S / Matcha','{\"size\": \"L\", \"style\": \"Matcha\"}',661,25139.77,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(56,34,'Q2GKN2OLHA7D','L / Matcha','{\"size\": \"M\", \"style\": \"Chocolate\"}',727,111699.26,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(57,34,'R5AE2R3CDC60','XL / Matcha','{\"size\": \"S\", \"style\": \"Original\"}',541,168252.64,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(58,34,'AXWNYJ2FG43I','XL / Taro','{\"size\": \"S\", \"style\": \"Chocolate\"}',342,196519.48,110233.73,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(59,35,'0JC7LOPWUY9G','S / Original','{\"size\": \"L\", \"style\": \"Taro\"}',174,95947.40,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(60,35,'DO7KGXC8SWDH','XL / Taro','{\"size\": \"L\", \"style\": \"Matcha\"}',707,265424.66,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(61,35,'1VDKJAXXZAZZ','L / Chocolate','{\"size\": \"XL\", \"style\": \"Chocolate\"}',751,182948.72,146903.51,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(62,36,'RM3KBLAE2WTR','S / Chocolate','{\"size\": \"S\", \"style\": \"Taro\"}',641,232749.58,246016.44,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(63,36,'IPJOKKGO51VV','L / Taro','{\"size\": \"L\", \"style\": \"Original\"}',549,49353.61,163493.33,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(64,36,'B1MRQK8NPXVG','S / Chocolate','{\"size\": \"XL\", \"style\": \"Matcha\"}',752,208450.60,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(65,37,'KBUXGK9U54VU','M / Original','{\"size\": \"L\", \"style\": \"Matcha\"}',669,43059.36,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(66,37,'F3B5AYELGV5E','M / Taro','{\"size\": \"S\", \"style\": \"Taro\"}',212,35634.14,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(67,37,'ZZGBEAF2SYCB','L / Chocolate','{\"size\": \"XL\", \"style\": \"Taro\"}',679,12198.36,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(68,38,'OCO1JDKDRBNQ','L / Matcha','{\"size\": \"M\", \"style\": \"Matcha\"}',674,261922.16,NULL,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(69,40,'BU2JZXHLU8QZ','M / Matcha','{\"size\": \"L\", \"style\": \"Chocolate\"}',285,231142.20,229324.18,1,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL);
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `weight_gram` int unsigned NOT NULL DEFAULT '0',
  `length_mm` int unsigned DEFAULT NULL,
  `width_mm` int unsigned DEFAULT NULL,
  `height_mm` int unsigned DEFAULT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `price` decimal(16,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(16,2) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('draft','active','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `attributes` json DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_name_index` (`name`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'E1TWRZVBEH','Similique rerum voluptates','similique-rerum-voluptates-e3r24',5,'Numquam maiores et reprehenderit facilis dolores sit enim omnis tenetur nihil commodi.','Animi itaque et officiis et unde laudantium. Et fuga amet iure molestias et reiciendis. Id enim et ad tempora tempore non. Incidunt quaerat expedita aut recusandae aut.',811,230,NULL,NULL,115,49177.48,294255.61,0,'active','{\"flavor\": \"taro\"}','IDR',NULL,'Ipsam tempore esse facere laudantium sint repellendus blanditiis et voluptas.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(2,'4H6MAQI3F4','Omnis officia provident','omnis-officia-provident-aBlCk',1,'Vitae nulla accusamus quae tenetur qui asperiores nihil ratione consequatur sunt repudiandae.',NULL,277,NULL,383,NULL,13,91386.54,161578.05,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,'Quae quis quis voluptas nihil sapiente et in.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(3,'RWPUFLMU7B','Omnis eius adipisci','omnis-eius-adipisci-4lskt',4,'Qui corrupti asperiores non qui in dolores nam laboriosam sapiente fugiat praesentium.','Illum alias dolorem distinctio eum voluptatem quia dicta. Saepe quo mollitia quia aperiam. Molestiae consectetur tempore beatae ut ratione.',224,355,242,NULL,208,491081.44,NULL,0,'active','{\"flavor\": \"taro\"}','IDR','Asperiores similique labore.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(4,'6FJQAVWUHK','Aut dicta dolorum','aut-dicta-dolorum-6iSRT',6,'Dolorem voluptatum eos qui ut quibusdam laboriosam beatae cum odit et dolor vel dignissimos dignissimos.',NULL,506,185,NULL,361,154,248798.50,NULL,0,'active','{\"flavor\": \"red velvet\"}','IDR','Eum facilis fugit hic.','Eum quia ea laudantium at sunt quia et.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(5,'JBLTM65RDH','Debitis quos harum','debitis-quos-harum-VrV2x',5,NULL,'Architecto qui magni quaerat id recusandae. Voluptatem quo aut harum officia repudiandae. Suscipit alias et rerum perferendis. Hic voluptas non magni ullam. Ipsam laborum quia saepe vel dolorem. Quod veritatis dolor voluptatem animi dolores culpa distinctio.',813,NULL,304,NULL,79,431952.89,143194.48,0,'active','{\"flavor\": \"taro\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(6,'E6MXGWBRQ2','Et ea amet','et-ea-amet-HkmLQ',6,'Ratione quidem quia officia expedita soluta minus qui corrupti.',NULL,703,NULL,103,356,129,363910.03,NULL,0,'active','{\"flavor\": \"red velvet\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(7,'JS1RRBVA6A','Animi architecto est','animi-architecto-est-TO8Qd',3,NULL,NULL,371,NULL,239,297,193,471128.77,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(8,'VOURXBB3KZ','Culpa fugiat ut','culpa-fugiat-ut-uE6V0',3,NULL,'Dolorum quia deleniti harum et saepe a omnis omnis. Impedit voluptatem occaecati deleniti non ratione. Consequatur et ullam atque odio sit. Quod reiciendis non similique cupiditate doloribus. Est voluptatem doloremque et doloremque quo dolorum. Quis eos tempora ea.',440,295,NULL,129,275,435930.90,45881.71,0,'active','{\"flavor\": \"taro\"}','IDR',NULL,'Aperiam et sint ut quam consectetur maiores qui.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(9,'LCDOTSTCQT','Mollitia provident reiciendis','mollitia-provident-reiciendis-xIPzK',4,NULL,NULL,863,197,64,73,86,48219.55,223147.24,0,'active','{\"flavor\": \"red velvet\"}','IDR','Blanditiis et est sit.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(10,'VFVAD1EGTZ','Impedit hic in','impedit-hic-in-oIXjr',1,NULL,'Tempore dicta aliquam ducimus incidunt voluptatem quasi deleniti. Sed tempora iusto similique totam laudantium aut et. Est quia dicta doloribus autem et sed. Aut ab nihil aut enim inventore magni non. Soluta non pariatur et dignissimos veritatis modi. Pariatur ducimus cumque eius et tempore corporis nulla.',1182,NULL,66,NULL,236,250900.20,NULL,0,'active','{\"flavor\": \"taro\"}','IDR','Est cumque sint.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(11,'RQXZPVTQIF','Numquam maiores omnis','numquam-maiores-omnis-Z5Gpb',3,NULL,NULL,969,233,182,NULL,102,373035.34,260975.10,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(12,'AASWKZ9QMO','Qui maxime qui','qui-maxime-qui-Mtv6b',1,NULL,NULL,792,199,126,278,128,115755.94,342468.85,0,'active','{\"flavor\": \"taro\"}','IDR','Aut harum in et.','Nesciunt nihil cum molestiae ab rem.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(13,'PLINGPUDKM','Debitis porro magni','debitis-porro-magni-Zz7lZ',4,'Cumque et sequi soluta et facilis voluptates aperiam voluptatem vel nihil.',NULL,358,NULL,256,102,5,332144.57,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR','Voluptatem soluta doloremque omnis.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(14,'PZ5C4PR8Z9','Molestiae sint qui','molestiae-sint-qui-F2lS2',2,NULL,'Omnis porro enim nesciunt doloremque qui nam quis. Sint fuga quos excepturi aliquid facilis enim molestias. Nemo nemo esse quia possimus mollitia. Numquam qui non et omnis magnam et velit est.',896,344,NULL,346,66,273351.57,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(15,'TYF4RCCDCM','Animi amet eaque','animi-amet-eaque-6Hhk7',6,NULL,NULL,649,NULL,164,234,196,133118.81,NULL,0,'active','{\"flavor\": \"vanilla\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(16,'IAGCQGYJFT','Voluptatem quia quasi','voluptatem-quia-quasi-IP94L',2,NULL,'Id repudiandae vitae et eaque. Atque est sequi error quidem atque perferendis autem magnam. Qui maiores ut ea dolorem expedita. Eligendi eius quia molestiae voluptatum vitae quis. Dolorem quidem illo omnis beatae aut natus consectetur.',735,NULL,NULL,276,173,202544.89,NULL,0,'active','{\"flavor\": \"vanilla\"}','IDR','Amet aut delectus.','Necessitatibus qui et accusantium aut ut sequi deleniti beatae asperiores.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(17,'UWXKGNUMAA','Voluptatem explicabo ut','voluptatem-explicabo-ut-D7r42',4,NULL,'Et voluptate nobis sed. Qui vel aut repellendus voluptatem quae est quia. Rerum quam sed dicta pariatur nesciunt et. Nemo pariatur assumenda doloremque ullam consequuntur vel rerum.',977,NULL,288,118,239,259884.73,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR','Sit enim aut magnam.','Et assumenda optio quis et molestiae iusto eius.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(18,'QMVVOWVCNT','Minus est eaque','minus-est-eaque-Fl1Tm',3,'Odio fuga asperiores autem iste nemo quae animi quo qui sunt.',NULL,985,NULL,NULL,NULL,53,266014.86,323607.56,0,'active','{\"flavor\": \"chocolate\"}','IDR','Et facilis consectetur.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(19,'ZZSEBEM3T8','Nostrum cum pariatur','nostrum-cum-pariatur-OyP6X',5,'Reprehenderit rerum vel est voluptates temporibus et et est ullam dolor ut officiis.',NULL,1197,97,NULL,188,13,464656.38,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(20,'KCQHLWDVC4','Repudiandae iste cupiditate','repudiandae-iste-cupiditate-L3ZIm',4,NULL,NULL,910,NULL,342,117,229,114820.84,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR','Veniam doloremque odit.','Sit vitae consequatur aut in velit.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(21,'II2XILUHZ8','Earum sed dolorem','earum-sed-dolorem-bZICb',6,NULL,'Sint doloremque dolorum nostrum reprehenderit amet quisquam. Laudantium quibusdam occaecati ut voluptatem. Ratione consequatur quis eos voluptas eaque. Ea id maxime alias voluptatum nemo qui fugit. Adipisci voluptas porro praesentium officia est. Qui consequuntur sed et eum accusamus vel fugiat.',263,NULL,228,NULL,33,469683.80,NULL,0,'active','{\"flavor\": \"taro\"}','IDR','Labore quaerat impedit.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(22,'YY1BF1LJPF','Aut aut et','aut-aut-et-C9aae',4,NULL,'Aut cum blanditiis adipisci ut. Aliquid nostrum nihil consequuntur ea. Sequi maiores illo necessitatibus sint. Non quia consequatur dolorum explicabo rerum dicta.',153,381,NULL,NULL,191,277163.65,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(23,'1N8PUPOEQB','Quasi corrupti temporibus','quasi-corrupti-temporibus-IxZ9B',2,'Non voluptates recusandae quae non et suscipit et fugiat distinctio deserunt.',NULL,1117,351,NULL,104,279,124389.50,93320.70,0,'active','{\"flavor\": \"vanilla\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(24,'FYI5TQQMYH','Dicta inventore mollitia','dicta-inventore-mollitia-qjzet',6,NULL,'Sit eligendi vel ea inventore eius eum qui. Consectetur dolor numquam autem officiis vitae cum veniam. Quas rerum earum illo minima similique et. Et laborum commodi laboriosam facere odio et. Provident quas sit perferendis doloribus.',571,NULL,NULL,354,138,356325.25,NULL,1,'active','{\"flavor\": \"taro\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(25,'FVEZDT3QEW','Eveniet accusamus qui','eveniet-accusamus-qui-2x3bU',2,NULL,'Omnis vero cumque accusamus qui dolor. Aut asperiores ipsa quos doloribus culpa ea. Optio ut magni placeat ut maxime. Corporis pariatur delectus voluptate. Vero autem porro rerum ut voluptatum. Eligendi sunt et dolore aut laboriosam aut omnis.',1014,NULL,335,285,109,214330.61,249118.02,0,'active','{\"flavor\": \"vanilla\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(26,'EBUSSXQTRH','Voluptas commodi neque','voluptas-commodi-neque-F7Hlp',4,'Voluptas optio et amet unde nemo est pariatur fugit placeat quia facere praesentium quidem voluptate sint sunt.','Molestiae sed eligendi fugit inventore quis. Hic tempore qui id explicabo non perspiciatis. In asperiores et inventore sint eligendi harum.',1051,348,391,250,45,82878.20,275909.64,0,'active','{\"flavor\": \"chocolate\"}','IDR','Reiciendis eos eligendi.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(27,'RTFUJ35IDT','Ullam sed sed','ullam-sed-sed-SJCMt',2,'Voluptatibus fuga blanditiis et architecto animi natus consectetur reiciendis quis ut repellendus dolores impedit doloribus rerum.','Praesentium similique autem sint quod dolorem fugit. Quia temporibus eum reiciendis. Sunt alias sapiente est. Sed et tenetur possimus sint facilis et. Voluptatum est quis rerum voluptatem.',1012,108,NULL,NULL,83,13419.95,NULL,0,'active','{\"flavor\": \"red velvet\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(28,'2MZ51DSB9M','Minima ipsum quo','minima-ipsum-quo-7qI7Y',6,NULL,'Itaque reiciendis ea sed ea consequatur. Voluptate a tenetur porro accusamus sequi error perferendis. Neque esse praesentium aut molestias officiis neque optio molestiae. Sint distinctio optio optio facere dolorem earum ut. Qui et ut ut accusantium.',544,NULL,68,103,98,213434.37,NULL,0,'active','{\"flavor\": \"vanilla\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(29,'C3BSFP9IKX','Veniam voluptatem saepe','veniam-voluptatem-saepe-k2uxd',6,'Sapiente corporis vel dicta voluptatem et sint assumenda minus sed quo officia ut est doloribus voluptas.',NULL,157,NULL,NULL,369,169,274976.07,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,'At quam amet harum porro corrupti nobis et accusantium aut omnis reprehenderit.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(30,'ND2MFHLJWY','Qui dolor culpa','qui-dolor-culpa-rHSdr',4,NULL,NULL,579,167,209,311,192,489197.03,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR','Molestiae voluptates voluptas.','Explicabo excepturi cum eos ullam quaerat alias assumenda unde.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(31,'EDW7AJEKS0','Omnis eius molestiae','omnis-eius-molestiae-vMSCl',4,'Voluptates minus similique dolores molestiae beatae mollitia dolores sequi voluptas voluptatum et.',NULL,894,81,331,NULL,235,219081.97,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR','Iure autem odit fuga.','Magni possimus molestias sit sed sed totam animi.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(32,'7DM5XTVHA4','Temporibus et ea','temporibus-et-ea-Y9zQN',1,'Nesciunt expedita est consequatur molestiae et id sunt molestiae est aspernatur cupiditate numquam voluptatem commodi reprehenderit suscipit.','Accusantium rem corporis porro dolorem facere. Ipsa modi dolorem ipsa impedit repellendus id repudiandae. Cum veniam unde nesciunt iusto. Nihil et est sapiente consectetur velit. Qui ut accusantium enim.',365,279,NULL,NULL,280,368932.66,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR','Est qui sint dignissimos.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(33,'URLYFWUGC8','Voluptas quas repellat','voluptas-quas-repellat-jypiu',2,NULL,NULL,739,330,252,NULL,92,247073.04,NULL,0,'active','{\"flavor\": \"red velvet\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(34,'KM3TPPWLUM','Excepturi hic qui','excepturi-hic-qui-MJ6PF',5,NULL,NULL,321,NULL,NULL,NULL,122,217759.81,29379.83,0,'active','{\"flavor\": \"red velvet\"}','IDR',NULL,'Dolor voluptatum fugit iusto amet qui quia architecto quia aut.','2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(35,'DFBASDVJIQ','Aliquid iure dolor','aliquid-iure-dolor-3DUkq',2,NULL,'Et non harum necessitatibus eius vel quas qui. Mollitia harum aspernatur tenetur in vitae iusto quia. Voluptas blanditiis et voluptatem saepe id dolorem. Incidunt accusamus quas odio totam dolor voluptatem eligendi.',1130,239,NULL,NULL,104,483449.72,68513.06,1,'active','{\"flavor\": \"matcha\"}','IDR','Perferendis quia temporibus consectetur.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(36,'TGMGDELWBW','Minima minus sunt','minima-minus-sunt-52m6v',1,'Possimus soluta sed neque dolore culpa deserunt et culpa minima qui quo.',NULL,598,87,207,NULL,225,246394.34,NULL,0,'active','{\"flavor\": \"chocolate\"}','IDR','Minus exercitationem minima magni.','In enim vitae amet velit ex eum temporibus.','2025-11-02 20:24:31','2025-11-02 20:24:33',NULL),(37,'JXORQ2574I','Sit placeat voluptas','sit-placeat-voluptas-y4lzY',2,NULL,'Vel est quis autem perferendis quia aliquam eligendi fuga. Et aut esse perspiciatis aut minus voluptate. Veniam consequatur aut quod iure ducimus.',807,175,124,110,113,41714.26,NULL,1,'active','{\"flavor\": \"red velvet\"}','IDR','Enim commodi qui laboriosam.',NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(38,'LURIED8CRL','Enim suscipit et','enim-suscipit-et-akh63',2,NULL,'Omnis repellat ut aliquid repellat omnis. Voluptates voluptatem illum est accusantium ducimus et. Vel ullam doloribus dolorem ipsam deserunt hic. Voluptates tempora laboriosam ea repellat atque aut at velit. Recusandae alias atque enim magni.',278,NULL,260,314,50,457221.75,NULL,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,'Quia autem necessitatibus minus voluptas sapiente deleniti.','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(39,'POMNCCFJDB','Maxime id voluptas','maxime-id-voluptas-ejrBA',1,'Veniam illum perferendis provident expedita voluptatem facere enim mollitia quo aut aspernatur nesciunt voluptas.',NULL,897,NULL,330,NULL,36,216550.31,183748.97,0,'active','{\"flavor\": \"matcha\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(40,'VHCUOQXRMQ','Dignissimos occaecati quis','dignissimos-occaecati-quis-EEVxr',6,'Id qui nesciunt eaque sapiente aut dicta distinctio voluptatem optio quisquam iste nam officiis eos et.',NULL,121,NULL,283,66,254,201311.66,NULL,0,'active','{\"flavor\": \"red velvet\"}','IDR',NULL,NULL,'2025-11-02 20:24:31','2025-11-02 20:24:31',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_images`
--

DROP TABLE IF EXISTS `review_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_review_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `review_images_product_review_id_foreign` (`product_review_id`),
  CONSTRAINT `review_images_product_review_id_foreign` FOREIGN KEY (`product_review_id`) REFERENCES `product_reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_images`
--

LOCK TABLES `review_images` WRITE;
/*!40000 ALTER TABLE `review_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('s45r7h3XCk1KgaTByojyDUZf8Sgw0hAj50ehw9TP',11,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiOWFJVHpUTE05Y3ZYZWNLOTIwQTRma3BpSXdXZG5IaXV5WlZnc3plWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXRhbG9nIjtzOjU6InJvdXRlIjtzOjEzOiJjYXRhbG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkTEFLQjlZTS5wSXN6Tlc4ZjljQmdJdWMzZUFIQ0Mwck9NRU9aUTRTNG9qUnVqWlBldWpmUmEiO3M6NjoidGFibGVzIjthOjExOntzOjQwOiI5NzkyYjZkZTU3MzE1NmVjMDQ1ZWE4MTg4MWJlM2QzZF9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJ0aXRsZSI7czo1OiJsYWJlbCI7czo1OiJUaXRsZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoidGVtcGxhdGUiO3M6NToibGFiZWwiO3M6ODoiVGVtcGxhdGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo2OiJBY3RpdmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJzaG93X2luX2Zvb3RlciI7czo1OiJsYWJlbCI7czo5OiJJbiBGb290ZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJmb290ZXJfb3JkZXIiO3M6NToibGFiZWwiO3M6NToiT3JkZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjowO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTI6Ikxhc3QgVXBkYXRlZCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fX1zOjQwOiJmOWI5N2I4YjE3ZmE1ZTgwZjk5NTYzN2Q5MTJmYjljYV9jb2x1bW5zIjthOjg6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY3VzdG9tZXIubmFtZSI7czo1OiJsYWJlbCI7czo4OiJDdXN0b21lciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InNlc3Npb25faWQiO3M6NToibGFiZWwiO3M6MTA6IlNlc3Npb24gaWQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImN1cnJlbmN5IjtzOjU6ImxhYmVsIjtzOjg6IkN1cnJlbmN5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiYWRkcmVzcy5pZCI7czo1OiJsYWJlbCI7czo3OiJBZGRyZXNzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidm91Y2hlci5pZCI7czo1OiJsYWJlbCI7czo3OiJWb3VjaGVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZXhwaXJlc19hdCI7czo1OiJsYWJlbCI7czoxMDoiRXhwaXJlcyBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZDQyMmM5NTI3MTUwMWJlMTM1MmQ5Y2ZmYjRjNzM3NzNfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJlbWFpbCI7czo1OiJsYWJlbCI7czoxMzoiRW1haWwgYWRkcmVzcyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTc6ImVtYWlsX3ZlcmlmaWVkX2F0IjtzOjU6ImxhYmVsIjtzOjE3OiJFbWFpbCB2ZXJpZmllZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiYTQ5N2VmZGYzNDgzNzI3OTY1M2JmMzY2MGQ4OWE1Y2VfY29sdW1ucyI7YTo2OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6MTM6IkVtYWlsIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJzdWJzY3JpYmVkX2F0IjtzOjU6ImxhYmVsIjtzOjEzOiJTdWJzY3JpYmVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNToidW5zdWJzY3JpYmVkX2F0IjtzOjU6ImxhYmVsIjtzOjE1OiJVbnN1YnNjcmliZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImU3OTNhMjc5ZDU2ZTQ1MDYwOTc1NDAyMGQ2MjdiZWVjX2NvbHVtbnMiO2E6MjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoib3JkZXJfbnVtYmVyIjtzOjU6ImxhYmVsIjtzOjEyOiJPcmRlciBudW1iZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjdXN0b21lci5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkN1c3RvbWVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidm91Y2hlci5pZCI7czo1OiJsYWJlbCI7czo3OiJWb3VjaGVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY3VzdG9tZXJfbmFtZSI7czo1OiJsYWJlbCI7czoxMzoiQ3VzdG9tZXIgbmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6ImN1c3RvbWVyX2VtYWlsIjtzOjU6ImxhYmVsIjtzOjE0OiJDdXN0b21lciBlbWFpbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6ImN1c3RvbWVyX3Bob25lIjtzOjU6ImxhYmVsIjtzOjE0OiJDdXN0b21lciBwaG9uZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTc6ImJpbGxpbmdBZGRyZXNzLmlkIjtzOjU6ImxhYmVsIjtzOjE1OiJCaWxsaW5nIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE4OiJzaGlwcGluZ0FkZHJlc3MuaWQiO3M6NToibGFiZWwiO3M6MTY6IlNoaXBwaW5nIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImN1cnJlbmN5IjtzOjU6ImxhYmVsIjtzOjg6IkN1cnJlbmN5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJzdWJ0b3RhbCI7czo1OiJsYWJlbCI7czo4OiJTdWJ0b3RhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjEwO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJkaXNjb3VudF90b3RhbCI7czo1OiJsYWJlbCI7czoxNDoiRGlzY291bnQgdG90YWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxMTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ0YXhfdG90YWwiO3M6NToibGFiZWwiO3M6OToiVGF4IHRvdGFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6InNoaXBwaW5nX3RvdGFsIjtzOjU6ImxhYmVsIjtzOjE0OiJTaGlwcGluZyB0b3RhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjEzO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJncmFuZF90b3RhbCI7czo1OiJsYWJlbCI7czoxMToiR3JhbmQgdG90YWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxNDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNjoic2hpcHBpbmdfY291cmllciI7czo1OiJsYWJlbCI7czoxNjoiU2hpcHBpbmcgY291cmllciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE2OiJzaGlwcGluZ19zZXJ2aWNlIjtzOjU6ImxhYmVsIjtzOjE2OiJTaGlwcGluZyBzZXJ2aWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InNoaXBwaW5nX2Nvc3QiO3M6NToibGFiZWwiO3M6MTM6IlNoaXBwaW5nIGNvc3QiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxNzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoic2hpcHBpbmdfZXRkIjtzOjU6ImxhYmVsIjtzOjEyOiJTaGlwcGluZyBldGQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNzoid2VpZ2h0X3RvdGFsX2dyYW0iO3M6NToibGFiZWwiO3M6MTc6IldlaWdodCB0b3RhbCBncmFtIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTk7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjIwO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InBsYWNlZF9hdCI7czo1OiJsYWJlbCI7czo5OiJQbGFjZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyMTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo3OiJwYWlkX2F0IjtzOjU6ImxhYmVsIjtzOjc6IlBhaWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyMjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiY2FuY2VsbGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEyOiJDYW5jZWxsZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyMzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzb3VyY2UiO3M6NToibGFiZWwiO3M6NjoiU291cmNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjI1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImIzM2I4MjE0MzUxODgwMTUyMTYwY2FkM2IzNjA5YjBjX2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJwYXJlbnQubmFtZSI7czo1OiJsYWJlbCI7czo2OiJQYXJlbnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoic2x1ZyI7czo1OiJsYWJlbCI7czo0OiJTbHVnIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic29ydF9vcmRlciI7czo1OiJsYWJlbCI7czoxMDoiU29ydCBvcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImRlbGV0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkRlbGV0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZGI4NDNkNDhkNjRiNmRlN2NkNDVhYWJiODViMTIwZGFfY29sdW1ucyI7YToxNDp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJhdXRob3IubmFtZSI7czo1OiJsYWJlbCI7czo2OiJBdXRob3IiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InRpdGxlIjtzOjU6ImxhYmVsIjtzOjU6IlRpdGxlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzbHVnIjtzOjU6ImxhYmVsIjtzOjQ6IlNsdWciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjc6ImV4Y2VycHQiO3M6NToibGFiZWwiO3M6NzoiRXhjZXJwdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6InJlYWRpbmdfdGltZSI7czo1OiJsYWJlbCI7czoxMjoiUmVhZGluZyB0aW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToiY292ZXJfaW1hZ2UiO3M6NToibGFiZWwiO3M6MTE6IkNvdmVyIGltYWdlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoibWV0YV90aXRsZSI7czo1OiJsYWJlbCI7czoxMDoiTWV0YSB0aXRsZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjg7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6InB1Ymxpc2hlZF9hdCI7czo1OiJsYWJlbCI7czoxMjoiUHVibGlzaGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoic2NoZWR1bGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEyOiJTY2hlZHVsZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxMDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19waW5uZWQiO3M6NToibGFiZWwiO3M6OToiSXMgcGlubmVkIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjEyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZGVsZXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiRGVsZXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI5NDg4MmQxYWZhODU3OGRkMWM0OTg3ZDQ4ZWM3MzdlNl9jb2x1bW5zIjthOjQ6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY3VzdG9tZXIubmFtZSI7czo1OiJsYWJlbCI7czo4OiJDdXN0b21lciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiJlZDZmZTZjMTE3MDdhYTJhZWFjZTgxNGJmMDhlODc0M19jb2x1bW5zIjthOjEzOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoiY29kZSI7czo1OiJsYWJlbCI7czo0OiJDb2RlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJ0eXBlIjtzOjU6ImxhYmVsIjtzOjQ6IlR5cGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InZhbHVlIjtzOjU6ImxhYmVsIjtzOjU6IlZhbHVlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoibWF4X2Rpc2NvdW50IjtzOjU6ImxhYmVsIjtzOjEyOiJNYXggZGlzY291bnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJtaW5fc3VidG90YWwiO3M6NToibGFiZWwiO3M6MTI6Ik1pbiBzdWJ0b3RhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InVzYWdlX2xpbWl0IjtzOjU6ImxhYmVsIjtzOjExOiJVc2FnZSBsaW1pdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVzZWRfY291bnQiO3M6NToibGFiZWwiO3M6MTA6IlVzZWQgY291bnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo5OiJJcyBhY3RpdmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ2YWxpZF9mcm9tIjtzOjU6ImxhYmVsIjtzOjEwOiJWYWxpZCBmcm9tIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToidmFsaWRfdW50aWwiO3M6NToibGFiZWwiO3M6MTE6IlZhbGlkIHVudGlsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjExO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aToxMjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZGVsZXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiRGVsZXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI4ZmFjNmViMWNlYzI2ODAzYjNmN2ZiNDQwYTI3MTExYl9jb2x1bW5zIjthOjE4OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Mzoic2t1IjtzOjU6ImxhYmVsIjtzOjM6IlNLVSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzbHVnIjtzOjU6ImxhYmVsIjtzOjQ6IlNsdWciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJicmFuZC5uYW1lIjtzOjU6ImxhYmVsIjtzOjU6IkJyYW5kIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToid2VpZ2h0X2dyYW0iO3M6NToibGFiZWwiO3M6MTE6IldlaWdodCBncmFtIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJsZW5ndGhfbW0iO3M6NToibGFiZWwiO3M6OToiTGVuZ3RoIG1tIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJ3aWR0aF9tbSI7czo1OiJsYWJlbCI7czo4OiJXaWR0aCBtbSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaGVpZ2h0X21tIjtzOjU6ImxhYmVsIjtzOjk6IkhlaWdodCBtbSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjg7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToic3RvY2siO3M6NToibGFiZWwiO3M6NToiU3RvY2siO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo5O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InByaWNlIjtzOjU6ImxhYmVsIjtzOjU6IlByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InNhbGVfcHJpY2UiO3M6NToibGFiZWwiO3M6MTA6IlNhbGUgcHJpY2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxMTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToiaXNfZmVhdHVyZWQiO3M6NToibGFiZWwiO3M6MTE6IklzIGZlYXR1cmVkIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjEzO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImN1cnJlbmN5IjtzOjU6ImxhYmVsIjtzOjg6IkN1cnJlbmN5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6Im1ldGFfdGl0bGUiO3M6NToibGFiZWwiO3M6MTA6Ik1ldGEgdGl0bGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxNTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MTY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjE3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJkZWxldGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJEZWxldGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6IjdmNWQ2OTY0ODE5ZGIzMjg3NjUwOTlhYzE3M2E4ZTYwX2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoic2x1ZyI7czo1OiJsYWJlbCI7czo0OiJTbHVnIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJsb2dvX3BhdGgiO3M6NToibGFiZWwiO3M6OToiTG9nbyBwYXRoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6OToiSXMgYWN0aXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZGVsZXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiRGVsZXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX19fQ==',1762185680),('u717mVjth7PpwVMSwujRkyV6tj5V3nVUDfqdgENt',11,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoidTVoRDNVQ05HS3dOQTZaMkc0ZXI3bVFVM2lUb3JGU3dnN3ZQS3VsNiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vd2lzaGxpc3RzIjtzOjU6InJvdXRlIjtzOjQwOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMud2lzaGxpc3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRMQUtCOVlNLnBJc3pOVzhmOWNCZ0l1YzNlQUhDQzByT01FT1pRNFM0b2pSdWpaUGV1amZSYSI7czo2OiJ0YWJsZXMiO2E6Mjp7czo0MDoiZjliOTdiOGIxN2ZhNWU4MGY5OTU2MzdkOTEyZmI5Y2FfY29sdW1ucyI7YTo4OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImN1c3RvbWVyLm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ3VzdG9tZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJzZXNzaW9uX2lkIjtzOjU6ImxhYmVsIjtzOjEwOiJTZXNzaW9uIGlkIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJjdXJyZW5jeSI7czo1OiJsYWJlbCI7czo4OiJDdXJyZW5jeSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImFkZHJlc3MuaWQiO3M6NToibGFiZWwiO3M6NzoiQWRkcmVzcyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InZvdWNoZXIuaWQiO3M6NToibGFiZWwiO3M6NzoiVm91Y2hlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImV4cGlyZXNfYXQiO3M6NToibGFiZWwiO3M6MTA6IkV4cGlyZXMgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6Ijk0ODgyZDFhZmE4NTc4ZGQxYzQ5ODdkNDhlYzczN2U2X2NvbHVtbnMiO2E6NDp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjdXN0b21lci5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkN1c3RvbWVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fX19',1762212899),('uwSRxZc1rgjJY0cUG4kuHR3RqXxc15UmjNRaRTkC',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDdPd1doNnBMMm1mYjZSTUJWNWdYQWlIYjY0S1FSRlNhSGFsb2hBdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTU6ImxvZ2luX2N1c3RvbWVyXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1762181865);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `courier` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waybill` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(16,2) DEFAULT NULL,
  `etd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','packed','shipped','in_transit','delivered','returned','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `raw_response` json DEFAULT NULL,
  `origin_id` int unsigned DEFAULT NULL,
  `destination_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipments_order_id_foreign` (`order_id`),
  KEY `shipments_waybill_index` (`waybill`),
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
INSERT INTO `shipments` VALUES (1,1,'tiki','OKE','WB92283408',42814.57,'3-5 HARI','2025-11-02 20:24:33',NULL,NULL,'shipped',NULL,1797,725,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,'jne','BEST','WB77531619',12772.91,'3-5 HARI','2025-11-02 20:24:33',NULL,NULL,'shipped',NULL,2000,1098,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,3,'pos','YES',NULL,32870.45,'3-5 HARI','2025-11-02 20:24:33',NULL,NULL,'shipped',NULL,1759,970,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,4,'jne','YES',NULL,22950.02,'2-3 HARI','2025-11-02 20:24:33',NULL,NULL,'shipped',NULL,832,1123,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,5,'jne','BEST','WB03159396',40994.60,'1-2 HARI','2025-11-02 20:24:33',NULL,NULL,'shipped',NULL,1984,1152,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,6,'jne','BEST',NULL,38050.66,'2-3 HARI','2025-11-02 20:24:34',NULL,NULL,'shipped',NULL,100,1033,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(7,7,'pos','OKE','WB95954172',14939.82,'2-3 HARI','2025-11-02 20:24:34',NULL,NULL,'shipped',NULL,152,1034,'2025-11-02 20:24:34','2025-11-02 20:24:34'),(15,16,'JNE_YES','jne YES',NULL,8889.09,NULL,NULL,NULL,NULL,'pending',NULL,NULL,NULL,'2025-11-03 04:35:19','2025-11-03 04:35:19'),(16,17,'JNE_YES','jne YES',NULL,8889.09,NULL,NULL,NULL,NULL,'pending',NULL,NULL,NULL,'2025-11-03 07:29:02','2025-11-03 07:29:02'),(17,18,'JNE_YES','jne YES',NULL,8889.09,NULL,NULL,NULL,NULL,'pending',NULL,NULL,NULL,'2025-11-03 07:36:02','2025-11-03 07:36:02'),(18,19,'JNE_YES','jne YES',NULL,8889.09,NULL,NULL,NULL,NULL,'pending',NULL,NULL,NULL,'2025-11-03 07:38:44','2025-11-03 07:38:44');
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_quotes`
--

DROP TABLE IF EXISTS `shipping_quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_quotes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned DEFAULT NULL,
  `address_id` bigint unsigned DEFAULT NULL,
  `courier` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(16,2) NOT NULL,
  `etd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rajaongkir_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipping_quotes_address_id_foreign` (`address_id`),
  KEY `shipping_quotes_cart_id_courier_index` (`cart_id`,`courier`),
  CONSTRAINT `shipping_quotes_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shipping_quotes_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_quotes`
--

LOCK TABLES `shipping_quotes` WRITE;
/*!40000 ALTER TABLE `shipping_quotes` DISABLE KEYS */;
INSERT INTO `shipping_quotes` VALUES (1,1,2,'pos','YES',32870.45,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,4,'sicepat','BEST',39217.98,'3-5 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,2,4,'jne','YES',23833.75,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,2,4,'tiki','REG',22374.59,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,3,5,'tiki','OKE',42814.57,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,3,5,'tiki','YES',19975.71,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,4,8,'tiki','OKE',16014.45,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,5,9,'tiki','BEST',14939.82,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,5,9,'pos','OKE',34761.54,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,6,12,'jne','BEST',40994.60,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(11,7,14,'sicepat','OKE',17414.05,'3-5 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(12,7,14,'tiki','BEST',19986.29,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(13,7,14,'pos','OKE',27752.23,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(14,8,15,'jne','BEST',30027.42,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(15,8,15,'sicepat','OKE',38050.66,'3-5 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(16,8,15,'pos','OKE',39726.27,'1-2 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(17,9,18,'sicepat','REG',22950.02,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(18,9,18,'jne','YES',8889.09,'3-5 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(19,10,20,'jne','BEST',12772.91,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(20,10,20,'pos','OKE',36434.44,'3-5 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(21,10,20,'pos','OKE',35982.65,'2-3 HARI',NULL,'2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `shipping_quotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'#quasi','quasi-mJc','2025-11-02 20:24:34','2025-11-02 20:24:34'),(2,'#ipsam','ipsam-PDB','2025-11-02 20:24:34','2025-11-02 20:24:34'),(3,'#iure','iure-6ET','2025-11-02 20:24:34','2025-11-02 20:24:34'),(4,'#eum','eum-mbG','2025-11-02 20:24:34','2025-11-02 20:24:34'),(5,'#omnis','omnis-DAL','2025-11-02 20:24:34','2025-11-02 20:24:34'),(6,'#aut','aut-S81','2025-11-02 20:24:34','2025-11-02 20:24:34'),(7,'#a','a-cvL','2025-11-02 20:24:34','2025-11-02 20:24:34'),(8,'#quam','quam-J3C','2025-11-02 20:24:34','2025-11-02 20:24:34');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Wilfred Kiehn','piper.weber@example.net','2025-11-02 20:24:30','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','WeCPHETtIB','2025-11-02 20:24:31','2025-11-02 20:24:31'),(2,'Dr. Meda Hilpert Jr.','zstreich@example.org','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','yG1OX1ikOD','2025-11-02 20:24:31','2025-11-02 20:24:31'),(3,'Maybelle Hudson','moises03@example.net','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','wc6ZEDUKT0','2025-11-02 20:24:31','2025-11-02 20:24:31'),(4,'Alfonzo Kreiger','rgleichner@example.net','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','7T9Y75mRPb','2025-11-02 20:24:31','2025-11-02 20:24:31'),(5,'Marisol Marvin','hartmann.alf@example.net','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','zaJYcF2CvV','2025-11-02 20:24:31','2025-11-02 20:24:31'),(6,'Zachery Huel','prohaska.walker@example.org','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','H38ZeDDNuw','2025-11-02 20:24:31','2025-11-02 20:24:31'),(7,'Mr. Zackary Koelpin','padberg.sallie@example.com','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','BfIqR6r3dw','2025-11-02 20:24:31','2025-11-02 20:24:31'),(8,'Lacy Johnson','chyatt@example.org','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','ihUwQ4rgfI','2025-11-02 20:24:31','2025-11-02 20:24:31'),(9,'Ian Steuber MD','lourdes.treutel@example.com','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','PzneNEcSRc','2025-11-02 20:24:31','2025-11-02 20:24:31'),(10,'Libby Homenick','cassin.elenor@example.net','2025-11-02 20:24:31','$2y$12$pWQPqTOdkruxtVNpZqHsze9TVUQa.BRUZVGXUdC8/CKWr/vZWhIWC','xMaITjRKhd','2025-11-02 20:24:31','2025-11-02 20:24:31'),(11,'Superadmin User','superadmin@arham-ecommerce.tes','2025-11-02 20:24:31','$2y$12$LAKB9YM.pIszNW8f9cBgIuc3eAHCC0rOMEOZQ4S4ojRujZPeujfRa','sFRRkYkJfa','2025-11-02 20:24:31','2025-11-02 20:24:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percent','fixed','free_shipping') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `value` decimal(16,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(16,2) DEFAULT NULL,
  `min_subtotal` decimal(16,2) DEFAULT NULL,
  `usage_limit` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `applicable` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vouchers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (1,'EMPNZRAK','free_shipping',0.00,48197.00,99809.00,NULL,0,1,'2025-10-30 20:24:31','2025-12-26 20:24:31','{\"scope\": \"all\"}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(2,'OZITTVGS','percent',5.00,NULL,NULL,NULL,0,1,'2025-10-29 20:24:31','2025-11-18 20:24:31','{\"scope\": \"all\"}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(3,'KSJOS4FX','fixed',24993.00,NULL,NULL,NULL,0,1,'2025-10-28 20:24:31','2025-11-24 20:24:31','{\"scope\": \"all\"}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(4,'BWDOPZ3T','percent',17.00,23844.00,167317.00,NULL,0,1,'2025-10-28 20:24:31','2025-11-18 20:24:31','{\"scope\": \"all\"}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL),(5,'TNMENZII','fixed',31610.00,NULL,176887.00,NULL,0,1,'2025-10-27 20:24:31','2025-12-25 20:24:31','{\"scope\": \"all\"}','2025-11-02 20:24:31','2025-11-02 20:24:31',NULL);
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wishlist_id` bigint unsigned NOT NULL,
  `purchasable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchasable_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `price_at_addition` decimal(16,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_item_unique` (`wishlist_id`,`purchasable_type`,`purchasable_id`),
  KEY `wishlist_items_purchasable_type_purchasable_id_index` (`purchasable_type`,`purchasable_id`),
  CONSTRAINT `wishlist_items_wishlist_id_foreign` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
INSERT INTO `wishlist_items` VALUES (4,2,'App\\Models\\Product',34,NULL,29379.83,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,2,'App\\Models\\Product',40,NULL,201311.66,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,2,'App\\Models\\Product',36,'Et molestiae officiis eaque itaque.',246394.34,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,2,'App\\Models\\ProductVariant',39,'Corrupti fuga saepe officia praesentium.',203579.73,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,3,'App\\Models\\Product',26,NULL,275909.64,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,3,'App\\Models\\ProductVariant',47,NULL,104660.96,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,3,'App\\Models\\Product',22,'Fuga exercitationem officia unde exercitationem.',277163.65,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(11,4,'App\\Models\\Product',21,'Velit perferendis omnis eaque possimus et sit.',469683.80,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(12,5,'App\\Models\\ProductVariant',53,'Atque nemo expedita ut.',126795.09,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(13,5,'App\\Models\\ProductVariant',31,'Beatae cumque animi rerum aut et deserunt.',191405.45,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(14,5,'App\\Models\\ProductVariant',23,NULL,263320.37,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(15,5,'App\\Models\\Product',29,'Deleniti recusandae numquam vel eligendi rerum.',274976.07,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(16,6,'App\\Models\\Product',12,NULL,342468.85,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(17,6,'App\\Models\\Product',4,NULL,248798.50,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(18,6,'App\\Models\\Product',20,'Non expedita corporis quia dolore qui blanditiis voluptatum vel.',114820.84,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(19,6,'App\\Models\\ProductVariant',5,'Explicabo fuga aut voluptatem ratione quaerat.',217698.91,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(20,7,'App\\Models\\ProductVariant',15,'Cupiditate harum qui veritatis repellendus consequatur voluptatem officiis.',233577.40,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(21,7,'App\\Models\\ProductVariant',53,'Saepe vel placeat ex sint ut eos.',126795.09,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(22,8,'App\\Models\\ProductVariant',53,NULL,126795.09,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(23,9,'App\\Models\\Product',23,NULL,93320.70,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(24,9,'App\\Models\\ProductVariant',10,NULL,88256.62,'2025-11-02 20:24:33','2025-11-02 20:24:33'),(25,10,'App\\Models\\ProductVariant',51,NULL,153321.30,'2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default Wishlist',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_customer_id_unique` (`customer_id`),
  CONSTRAINT `wishlists_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
INSERT INTO `wishlists` VALUES (1,1,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(2,2,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(3,3,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(4,4,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(5,5,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(6,6,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(7,7,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(8,8,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(9,9,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33'),(10,10,'Default Wishlist','2025-11-02 20:24:33','2025-11-02 20:24:33');
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'arham_ecommerce'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-04  6:41:43
