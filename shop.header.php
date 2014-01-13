<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=header
File=shop.header
Hooks=header.main
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

/**
 * Shop
 *
 * @package Cotonti
 * @version 2.00
 * @author Sedito.by
 * @copyright Copyright (c) Seditio.by 2008-2010
 * @license BSD
 */
defined('SED_CODE') or die('Wrong URL');

list($usr['shop_auth_read'], $usr['shop_auth_write'], $usr['shop_isadmin']) = sed_auth('plug', 'shop');
require(sed_langfile('shop'));
if ($usr['shop_isadmin'])
{
	$sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_orders WHERE order_apply=0");
	$totallines_app = sed_sql_result($sql2, 0, 0);

	$sql2 = sed_sql_query("SELECT COUNT(*) FROM $db_orders WHERE order_payed=0");
	$totallines_pay = sed_sql_result($sql2, 0, 0);

	if ($totallines_app > 0)
	{
		$totallines_line = ' ' . $totallines_app . ' ' . $L['shop_filter_pending'];
	}
	if ($totallines_pay > 0)
	{
		$totallines_line .= ( !empty($totallines_line)) ? ',' : '';
		$totallines_line .= ' ' . $totallines_pay . ' ' . $L['shop_filter_unpaid'];
	}
	if ($totallines_pay > 0 || $totallines_app > 0)
	{
		$shop_not = '<a href="' . sed_url('plug', 'e=shop&m=tools') . '">' . $L['shop_eshop'] . ':' . $totallines_line . '</a>';
		$out['notices'] .= $shop_not;
	}
}
?>