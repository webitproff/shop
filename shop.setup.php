<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Name=Shop
Description=E-Shop Module for Cotonti
Version=2.00
Date=21-Dec-2010
Author=Seditio.by
Copyright=&copy; Seditio.by 2010 
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
email=11:string:::Administrator email
cat=13:string::shop:Shop category
conversion=50:text::USD|1/1|US Dollar|$ |2|:Currency Exchange
testmode=99:radio::0:Test Mode
[END_SED_EXTPLUGIN_CONFIG]
==================== */

/**
 * Shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (c) 2008-2010 Seditio.by
 * 
 */

defined('SED_CODE') or die('Wrong URL');

require_once $cfg['plugins_dir'].'/shop/shop.global.php';

if($action == 'install')
{
	$sql_tmp = sed_sql_query("CREATE TABLE IF NOT EXISTS $db_orders (
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
			  PRIMARY KEY  (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sql_tmp = sed_sql_query("CREATE TABLE IF NOT EXISTS $db_orders_desc (
			  `od_id` int(11) unsigned NOT NULL auto_increment,
			  `od_orderid` int(11) NOT NULL default '0',
			  `od_pageid` int(11) NOT NULL default '0',
			  `od_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
			  `od_price` decimal(12,2) NOT NULL default '0',
			  `od_count` int(11) NOT NULL default '0',
			  `od_total` decimal(12,2) NOT NULL default '0',
			  PRIMARY KEY  (`od_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
	
	// Extrafields setup
	$name = str_replace('page_', '', $shopcfg['price']);
	$L['page_'.$name.'_title'] = (isset($L['page_'.$name.'_title'])) ? $L['page_'.$name.'_title'] : '';
	sed_extrafield_add('pages', $name, 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', $L['page_'.$name.'_title']);
	
	$name = str_replace('page_', '', $shopcfg['price_old']);
	$L['page_'.$name.'_title'] = (isset($L['page_'.$name.'_title'])) ? $L['page_'.$name.'_title'] : '';
	sed_extrafield_add('pages', $name, 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', $L['page_'.$name.'_title']);
	
	$name = str_replace('page_', '', $shopcfg['instock']);
	$L['page_'.$name.'_title'] = (isset($L['page_'.$name.'_title'])) ? $L['page_'.$name.'_title'] : '';
	sed_extrafield_add('pages', $name, "checkbox", '<input type=checkbox />', '', $L['page_'.$name.'_title']);


	$name = str_replace('user_', '', $shopcfg['payernamefld']);
	$L['user_'.$name.'_title'] = (isset($L['user_'.$name.'_title'])) ? $L['user_'.$name.'_title'] : '';
	sed_extrafield_add('users', $name, 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', $L['user_'.$name.'_title']);
	
	$name = str_replace('user_', '', $shopcfg['payerphonefld']);
	$L['user_'.$name.'_title'] = (isset($L['user_'.$name.'_title'])) ? $L['user_'.$name.'_title'] : '';
	sed_extrafield_add('users', $name, 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', $L['user_'.$name.'_title']);
	
	$name = str_replace('user_', '', $shopcfg['payeraddressfld']);
	$L['user_'.$name.'_title'] = (isset($L['user_'.$name.'_title'])) ? $L['user_'.$name.'_title'] : '';
	sed_extrafield_add('users', $name, 'input', '<input class="text" type="text" maxlength="255" size="56" />', '', $L['user_'.$name.'_title']);
}

?>