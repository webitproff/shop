<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=header.main
[END_COT_EXT]
==================== */

/**
 * Shop
 *
 * @package Cotonti
 * @version 3.00
 * @author Sedito.by
 * @copyright Copyright (c) esclkm 2008-2010
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

if (cot_auth('plug', 'shop', 'A'))
{

	$totallines_app = $db->query("SELECT COUNT(*) FROM $db_orders WHERE order_apply=0")->fetchColumn();

	$totallines_pay = $db->query("SELECT COUNT(*) FROM $db_orders WHERE order_payed=0")->fetchColumn();

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
		$shop_not = cot_rc_link(cot_url('plug', 'e=shop&m=tools'), $L['shop_eshop'] . ':' . $totallines_line);
		$out['notices'] .= $shop_not;
	}
}
?>