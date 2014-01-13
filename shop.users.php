<?php

/* ====================
  [BEGIN_COT_EXT]
  Hooks=users.details.tags
  [END_COT_EXT]
  ==================== */

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2010 esclkm
 */
defined('COT_CODE') or die('Wrong URL');

list($usr['shop_auth_read'], $usr['shop_auth_write'], $usr['shop_isadmin']) = cot_auth('plug', 'shop');

if ((int)$id == $usr['id'] || $usr['shop_isadmin'])
{
	$mskin = cot_tplfile(array('shop', 'users'), 'plug');
	$tt = new XTemplate($mskin);

	list($pnshop, $dshop, $dshop_url) = cot_import_pagenav('dshop', $cfg['maxrowsperpage']);
	
	$totallines = $db->query("SELECT COUNT(*) FROM $db_orders WHERE order_payed >0 AND order_userid = ".(int)$id)->fetchColumn();

	$sql = $db->query("SELECT * FROM $db_orders
		WHERE  order_payed >0 AND order_userid = ".(int)$id." ORDER BY order_id DESC LIMIT $dshop,".$cfg['maxrowsperpage']);
	$ii = 0;
	while ($row = $sql->fetch())
	{
		$sql2 = $db->query("SELECT o.*, p.* FROM $db_orders_desc AS o
		JOIN $db_pages AS p ON o.od_pageid=p.page_id
		WHERE od_orderid = ".(int)$row['order_id']);
		$jj = 0;
		while ($row2 = $sql2->fetch())
		{
			$jj++;
			$tt->assign(cot_generate_pagetags($row2, 'SHOP_ROW_'));
			$tt->assign(array(
				'SHOP_ROW_COUNT' => $row2['od_count'],
				'SHOP_ROW_PRICE' => cot_format_money($row2['od_price'], $shopcurr),
				'SHOP_ROW_TOTAL' => cot_format_money($row2['od_total'], $shopcurr),
				'SHOP_ROW_PRICE_DEF' => cot_format_money($row2['od_price'], $shopdefcurr),
				'SHOP_ROW_TOTAL_DEF' => cot_format_money($row2['od_total'], $shopdefcurr),
				'SHOP_ROW_DESC' => htmlspecialchars($row2['od_desc']),
				'SHOP_ROW_ODDEVEN' => cot_build_oddeven($jj),
				'SHOP_ROW_TITLE' => htmlspecialchars($row2['od_title']),
				'SHOP_ROW_PAGEID' => htmlspecialchars($row2['od_pageid']),
				'SHOP_ROW_NUM' => $jj
			));
			$tt->parse('MAIN.DETAILS.ROW');
		}
		if ($jj == 0)
		{
			$tt->parse('MAIN.DETAILS.NOROW');
		}

		$ii++;

		$tt->assign(array(
			'SHOP_PAYERNAME' => htmlspecialchars($row['order_payername']),
			'SHOP_PAYERPHONE' => htmlspecialchars($row['order_payerphone']),
			'SHOP_PAYEREMAIL' => htmlspecialchars($row['order_payeremail']),
			'SHOP_PAYERADDRESS' => htmlspecialchars($row['order_payeraddress']),
			'SHOP_PAYEROTHER' => htmlspecialchars($row['order_payerother']),
			'SHOP_PAYERTOTAL' => cot_format_money($row['order_total'], $shopcurr),
			'SHOP_PAYERTOTAL_DEF' => cot_format_money($row['order_total'], $shopdefcurr),
			'SHOP_DATE' => @date($cfg['formatyearmonthday'], $row['order_date'] + $usr['timezone'] * 3600),
			'SHOP_ID' => htmlspecialchars($row['order_id']),
			'SHOP_ORDERSTATE' => $odstate,
			'SHOP_ODDEVEN' => cot_build_oddeven($ii),
			'SHOP_NUM' => $ii
		));
		$tt->parse('MAIN.DETAILS');
	}
	if ($ii == 0)
	{
		$tt->parse('MAIN.NODETAILS');
	}
	
	$pagenav = cot_pagenav('users', "m=details&id=$id", $dshop, $totallines, $cfg['maxrowsperpage'], 'dshop');

	$tt->assign(array(
		"SHOP_PAGINATION" => $pagenav['main'],
		"SHOP_PAGEPREV" => $pagenav['prev'],
		"SHOP_PAGENEXT" => $pagenav['next'],
	));
	$tt->parse('MAIN');
	$t->assign('SHOP_DETAILS', $tt->text("MAIN"));
}
?>