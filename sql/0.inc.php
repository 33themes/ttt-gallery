<?php
$sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."tttgallery` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`description` varchar(255) NOT NULL,
	`medias` text NOT NULL,
	`template` varchar(255) NOT NULL,
	`updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`created_at` TIMESTAMP NOT NULL,
	`used_at` varchar(255) NOT NULL,	
	PRIMARY KEY (`id`)
);";
dbDelta($sql);
?>
