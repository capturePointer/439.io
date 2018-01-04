CREATE TABLE `short_link` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(1024) NOT NULL DEFAULT '' COMMENT '原始url',
  `url_hash` char(32) NOT NULL DEFAULT '' COMMENT '原始url做md5，用于重复url记录检测',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_url_hash` (`url_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;