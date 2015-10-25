ALTER TABLE `cot_orders` ADD COLUMN `order_delivery_type` int(11) unsigned NOT NULL default '0';
ALTER TABLE `cot_orders` ADD COLUMN `order_delivery_desc` varchar(255) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `cot_orders` ADD COLUMN `order_delivery_price` decimal(12,2) NOT NULL default '0';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_type` int(11)  NOT NULL default '0';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_desc` varchar(255) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_num` varchar(255) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_state` varchar(255) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_date` int(11) NOT NULL default '0';
ALTER TABLE `cot_orders` ADD COLUMN `order_transaction_ans` text collate utf8_unicode_ci NOT NULL;

CREATE TABLE IF NOT EXISTS cot_orders_payments (
			  `odp_id` int(11) unsigned NOT NULL auto_increment,
			  `odp_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `odp_desc` text collate utf8_unicode_ci NOT NULL,
			  `odp_driver` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  PRIMARY KEY  (`odp_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS cot_orders_delivery (
			  `odd_id` int(11) unsigned NOT NULL auto_increment,
			  `odd_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `odd_desc` text collate utf8_unicode_ci NOT NULL,
			  PRIMARY KEY  (`odd_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS cot_orders_delivery_prices (
			  `oddp_id` int(11) unsigned NOT NULL auto_increment,
			  `oddp_oddid` int(11) NOT NULL default '0',
			  `oddp_mintotal` decimal(12,2) NOT NULL default '0',
			  `oddp_type` int(11) NOT NULL default '0',
			  `oddp_price` decimal(12,2) NOT NULL default '0',
			  PRIMARY KEY  (`oddp_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;