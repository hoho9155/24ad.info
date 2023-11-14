DROP TABLE IF EXISTS `__PREFIX__reviews`;
CREATE TABLE `__PREFIX__reviews`
(
	`id` bigint(20) unsigned NOT NULL,
	`post_id` bigint(20) unsigned NOT NULL,
	`user_id` bigint(20) unsigned NULL DEFAULT NULL,
	`rating` int(11) NOT NULL,
	`comment` text COLLATE utf8_unicode_ci NOT NULL,
	`approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`spam` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`created_at` timestamp NULL DEFAULT NULL,
	`updated_at` timestamp NULL DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

ALTER TABLE `__PREFIX__reviews`
	ADD PRIMARY KEY (`id`),
	ADD KEY `post_id` (`post_id`),
	ADD KEY `user_id` (`user_id`);

ALTER TABLE `__PREFIX__reviews`
	MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
