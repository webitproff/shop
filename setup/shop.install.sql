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
			  `order_delivery_type` int(11) unsigned NOT NULL default '0',
			  `order_delivery_desc` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_delivery_price` decimal(12,2) NOT NULL default '0',
			  `order_transaction_type` int(11) NOT NULL default '0',
			  `order_transaction_desc` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_transaction_num` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_transaction_state` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `order_transaction_date` int(11) NOT NULL default '0',
			  `order_transaction_ans` text collate utf8_unicode_ci NOT NULL,
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