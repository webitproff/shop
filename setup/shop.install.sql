/* Add oders tables */
CREATE TABLE IF NOT EXISTS cot_orders (
			  `order_id` int(11) unsigned NOT NULL auto_increment,
			  `order_userid` int(11) NOT NULL default '-1',
			  `order_date` int(11) NOT NULL default '0',
			  `order_total` decimal(12,2) NOT NULL default '0',
			  `order_apply` int(11) unsigned NOT NULL default '0',
			  `order_payed` int(11) unsigned NOT NULL default '0',
			  `order_payername` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_payerphone` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_payeremail` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_payeraddress` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_currency` varchar(64) collate utf8_unicode_ci NOT NULL default '',
			  `order_payerother` text collate utf8_unicode_ci NOT NULL,
			  `order_desc` text collate utf8_unicode_ci NOT NULL,
			  PRIMARY KEY  (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS cot_orders_desc (
			  `od_id` int(11) unsigned NOT NULL auto_increment,
			  `od_orderid` int(11) NOT NULL default '0',
			  `od_pageid` int(11) NOT NULL default '0',
			  `od_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `od_price` decimal(12,2) NOT NULL default '0',
			  `od_count` int(11) NOT NULL default '0',
			  `od_total` decimal(12,2) NOT NULL default '0',
			  `od_desc` text collate utf8_unicode_ci NOT NULL,
			  PRIMARY KEY  (`od_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;