<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=users.details
File=shop.users
Hooks=users.details.tags
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

/**
 * Shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (с) 2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL');

list($usr['shop_auth_read'], $usr['shop_auth_write'], $usr['shop_isadmin']) = sed_auth('plug', 'shop');

if((int)$id == $usr['id'] || $usr['shop_isadmin'])
{
	require_once(sed_langfile('shop'));
	$db_orders = $db_x.'orders';
	$db_orders_desc = $db_x.'orders_desc';

	$mskin = sed_skinfile(array('shop', 'users'), true);
	$tt = new XTemplate($mskin);

	$dshop = sed_import('dshop','G','INT');
	$dshop = (empty($dshop)) ? '0' : $dshop;

	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_orders WHERE order_payed >0 AND order_userid = ".(int)$id);
	$totallines = sed_sql_result($sql, 0, 0);
	$sql = sed_sql_query("SELECT * FROM $db_orders
		WHERE  order_payed >0 AND order_userid = ".(int)$id." ORDER BY order_id DESC LIMIT $dshop,".$cfg['maxrowsperpage']);
	$ii = 0;
	while($row = sed_sql_fetchassoc($sql))
	{
		$sql2 = sed_sql_query("SELECT o.*, p.* FROM $db_orders_desc AS o
		JOIN $db_pages AS p ON o.od_pageid=p.page_id
		WHERE od_orderid = ".(int)$row['order_id']);
		$jj = 0;
		while($row2 = sed_sql_fetchassoc($sql2))
		{
			$jj++;
			$tt->assign(sed_generate_pagetags($row2, 'SHOP_ROW_'));
			$tt->assign(sed_generate_pagetags_currency($row2, 'SHOP_ROW_'));
			$tt->assign(sed_generate_pagetags($row2, 'SHOP_ROW_'));
			$tt->assign(array(
				'SHOP_ROW_COUNT' => $row2['od_count'],
				'SHOP_ROW_PRICE' => sed_format_money($row2['od_price'], $shopcurr),
				'SHOP_ROW_TOTAL' => sed_format_money($row2['od_total'], $shopcurr),
				'SHOP_ROW_PRICE_DEF' => sed_format_money($row2['od_price'], $shopdefcurr),
				'SHOP_ROW_TOTAL_DEF' => sed_format_money($row2['od_total'], $shopdefcurr),
				'SHOP_ROW_ODDEVEN' => sed_build_oddeven($jj),
				'SHOP_ROW_TITLE' => htmlspecialchars($row2['od_title']),
				'SHOP_ROW_PAGEID' => htmlspecialchars($row2['od_pageid']),
				'SHOP_ROW_NUM' => $jj
			));
			$tt->parse('MAIN.DETAILS.ROW');
		}
		if($jj == 0) 
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
			'SHOP_PAYERTOTAL' => sed_format_money($row['order_total'], $shopcurr),
			'SHOP_PAYERTOTAL_DEF' => sed_format_money($row['order_total'], $shopdefcurr),
			'SHOP_DATE' => @date($cfg['formatyearmonthday'], $row['order_date'] + $usr['timezone'] * 3600),
			'SHOP_ID' => htmlspecialchars($row['order_id']),
			'SHOP_ORDERSTATE' => $odstate,
			'SHOP_ODDEVEN' => sed_build_oddeven($ii),
			'SHOP_NUM' => $ii
		));
		$tt->parse('MAIN.DETAILS');
	}
	if ($ii == 0)
	{	
		$tt->parse('MAIN.NODETAILS');
	}		

	$pagination = sed_pagination(sed_url('users', "m=details&id=$id"), $dshop, $totallines, $cfg['maxrowsperpage'], 'dshop');
	list($pageprev, $pagenext) = sed_pagination_pn(sed_url('users', "m=details&id=$id"), $dshop, $totallines, $cfg['maxrowsperpage'], TRUE, 'dshop');

	$tt->assign(array(
		"SHOP_PAGINATION" => $pagination,
		"SHOP_PAGEPREV" => $pageprev,
		"SHOP_PAGENEXT" => $pagenext,

	));
	$tt->parse('MAIN');
	$t->assign('SHOP_DETAILS', $tt -> text("MAIN"));
}

?>