SET FOREIGN_KEY_CHECKS=0;

--
-- Table structure for table `__PREFIX__domain_settings`
--

DROP TABLE IF EXISTS `__PREFIX__domains`;
CREATE TABLE `__PREFIX__domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `https` tinyint(1) UNSIGNED DEFAULT '0',
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `__PREFIX__domains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `host` (`host`);

ALTER TABLE `__PREFIX__domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `__PREFIX__domain_settings`
--

DROP TABLE IF EXISTS `__PREFIX__domain_settings`;
CREATE TABLE `__PREFIX__domain_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field` text COLLATE utf8_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `__PREFIX__domain_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`),
  ADD KEY `active` (`active`),
  ADD KEY `country_code` (`country_code`);

ALTER TABLE `__PREFIX__domain_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `__PREFIX__domain_meta_tags`
--

DROP TABLE IF EXISTS `__PREFIX__domain_meta_tags`;
CREATE TABLE `__PREFIX__domain_meta_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `page` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `__PREFIX__domain_meta_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `country_code` (`country_code`);

ALTER TABLE `__PREFIX__domain_meta_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `__PREFIX__domain_home_sections`
--

DROP TABLE IF EXISTS `__PREFIX__domain_home_sections`;
CREATE TABLE `__PREFIX__domain_home_sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `method` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `view` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `depth` int(10) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `__PREFIX__domain_home_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `country_code` (`country_code`);

ALTER TABLE `__PREFIX__domain_home_sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


SET FOREIGN_KEY_CHECKS=1;
