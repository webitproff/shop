<?php
/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (с) 2010 esclkm
 */

defined('COT_CODE') or die('Wrong URL');

cot_block(cot_auth('plug', 'shop', 'A'));

$filter = cot_import('filter', 'G', 'ALP');

list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

$id = cot_import('id','G','INT');
$delid = cot_import('delid','G','INT');
$accid = cot_import('accid','G','INT');
$bid = cot_import('bid','G','INT');
$unaccid = cot_import('unaccid','G','INT');
$unbid = cot_import('unbid','G','INT');
$file = cot_import('file','G','TXT');

if((int)$delid > 0)
{
	$db->query("DELETE FROM $db_orders_desc WHERE od_orderid='".$delid."'");
	$db->query("DELETE FROM $db_orders WHERE order_id='".$delid."'");
}
if((int)$accid > 0)
{
	$db->query("UPDATE $db_orders SET order_apply='".(int)$usr['id']."' WHERE order_id='".(int)$accid."'");
}
if((int)$bid > 0)
{
	$db->query("UPDATE $db_orders SET order_payed='".(int)$usr['id']."' WHERE order_id='".(int)$bid."'");
}
if((int)$unaccid > 0)
{
	$db->query("UPDATE $db_orders SET order_apply='0' WHERE order_id='".(int)$unaccid."'");
}
if((int)$unbid > 0)
{
	$db->query("UPDATE $db_orders SET order_payed='0' WHERE order_id='".(int)$unbid."'");
}

if ($a == 'autodel')
{
	$ryear = cot_import('ryear','P','INT');
	$rmonth = cot_import('rmonth','P','INT');
	$rday = cot_import('rday','P','INT');

	$rpagedate = cot_mktime(0, 0, 0, $rmonth, $rday, $ryear) - $usr['timezone'] * 3600;

	$rordertype = cot_import('rordertype','P','ALP');
	$rordertype = ($rordertype == "payed") ? 'payed' : 'apply';

	$sql = $db->query("SELECT order_id FROM $db_orders WHERE order_$rordertype = 0 AND order_date < $rpagedate");

	while($row = $sql->fetch())
	{
		$db->query("DELETE FROM $db_orders_desc WHERE od_orderid='".$row['order_id']."'");
		$db->query("DELETE FROM $db_orders WHERE order_id='".$row['order_id']."'");
	}
}


$mskin = cot_tplfile(array('shop', 'tools'), 'plug');
$t = new XTemplate($mskin);

if((int)$id > 0)
{
	$sql = $db->query("SELECT * FROM $db_orders WHERE order_id = ".(int)$id." LIMIT 1");
	cot_die($sql->rowCount()==0);
	$row = $sql->fetch();
	$sql2 = $db->query("SELECT o.*, p.* FROM $db_orders_desc AS o
		JOIN $db_pages AS p ON o.od_pageid=p.page_id
		WHERE od_orderid = ".(int)$id);
	$jj = 0;
	while($row2 = $sql2->fetch())
	{
		$jj++;
		$t->assign(cot_generate_pagetags($row2, 'SHOP_ROW_'));
		$t->assign(array(
			'SHOP_ROW_COUNT' => $row2['od_count'],
			'SHOP_ROW_PRICE' => cot_format_money($row2['od_price'], $row['order_currency']),
			'SHOP_ROW_TOTAL' => cot_format_money($row2['od_total'], $row['order_currency']),
			'SHOP_ROW_PRICE_DEF' => cot_format_money($row2['od_price'], $shopdefcurr),
			'SHOP_ROW_TOTAL_DEF' => cot_format_money($row2['od_total'], $shopdefcurr),
			'SHOP_ROW_DESC' => htmlspecialchars($row2['od_desc']),
			'SHOP_ROW_ODDEVEN' => cot_build_oddeven($jj),
			'SHOP_ROW_TITLE' => htmlspecialchars($row2['od_title']),
			'SHOP_ROW_PAGEID' => htmlspecialchars($row2['od_pageid']),
			'SHOP_ROW_NUM' => $jj
		));
		$t->parse('MAIN.DETAILS.ROW');
		if($jj == 0)
		{
			$t->parse('MAIN.DETAILS.NOROW');
		}
	}

	$t->assign(cot_generate_usertags((int)$row['order_userid'], 'SHOP_PAYER_'));
	if($row['order_apply'] > 0)
	{
		$t->assign(cot_generate_usertags((int)$row['order_apply'], 'SHOP_VALIDATOR_'));
	}
	if($row['order_payed'] > 0)
	{
		$t->assign(cot_generate_usertags((int)$row['order_payed'], 'SHOP_VALIDATOR2_'));
	}

	$odstate = ($row['order_apply'] > 0) ? 1 : 0;
	$odstate = ($row['order_payed'] > 0) ? 2 : $odstate;
	$t->assign(array(
		'SHOP_PAYERNAME' => htmlspecialchars($row['order_payername']),
		'SHOP_PAYERPHONE' => htmlspecialchars($row['order_payerphone']),
		'SHOP_PAYEREMAIL' => htmlspecialchars($row['order_payeremail']),
		'SHOP_PAYERADDRESS' => htmlspecialchars($row['order_payeraddress']),
		'SHOP_PAYEROTHER' => htmlspecialchars($row['order_payerother']),
		'SHOP_PAYERTOTAL' => cot_format_money($row['order_total'], $row['order_currency']),
		'SHOP_PAYERTOTAL_DEF' => cot_format_money($row['order_total'], $shopdefcurr),
		'SHOP_DATE' => cot_date('datetime_medium',$row['order_date']),
		'SHOP_ID' => htmlspecialchars($row['order_id']),
		'SHOP_ORDERSTATE' => $odstate,
		'SHOP_DELETEURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&delid='.$row['order_id']),
		'SHOP_ACCEPTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&accid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_BOUGHTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&bid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_UNACCEPTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&unaccid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_UNBOUGHTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&unbid='.$row['order_id'].'&id='.$row['order_id']),
	));
	$t->parse('MAIN.DETAILS');
}
else
{
	if ($filter == 'unaccepted')
	{
		$whereshop = " order_apply=0";
	}
	elseif($filter == 'unpayed')
	{
		$whereshop = " order_payed=0";
	}
	else
	{
		$whereshop = " 1";
	}
	$totallines = $db->query("SELECT COUNT(*) FROM $db_orders WHERE $whereshop")->fetchColumn();

	$sql = $db->query("SELECT * FROM $db_orders 
		WHERE $whereshop ORDER BY order_id DESC LIMIT $d,".$cfg['maxrowsperpage']);

	$jj = 0;
	while($row = $sql->fetch())
	{
		$jj++;
		$t->assign(cot_generate_usertags((int)$row['order_userid'], 'SHOP_ROW_PAYER_'));
		if($row['order_apply'] > 0)
		{
			$t->assign(cot_generate_usertags((int)$row['order_apply'], 'SHOP_ROW_VALIDATOR_'));
		}
		if($row['order_payed'] > 0)
		{
			$t->assign(cot_generate_usertags((int)$row['order_payed'], 'SHOP_ROW_VALIDATOR2_'));
		}
		$odstate = ($row['order_apply'] > 0) ? 1 : 0;
		$odstate = ($row['order_payed'] > 0) ? 2 : $odstate;
		$t->assign(array(
			'SHOP_ROW_PAYERNAME' => htmlspecialchars($row['order_payername']),
			'SHOP_ROW_PAYERPHONE' => htmlspecialchars($row['order_payerphone']),
			'SHOP_ROW_PAYEREMAIL' => htmlspecialchars($row['order_payeremail']),
			'SHOP_ROW_PAYERADDRESS' => htmlspecialchars($row['order_payeraddress']),
			'SHOP_ROW_PAYEROTHER' => htmlspecialchars($row['order_payerother']),
			'SHOP_ROW_PAYERTOTAL' => cot_format_money($row['order_total'], $row['order_currency']),
			'SHOP_ROW_PAYERTOTAL_DEF' => cot_format_money($row['order_total'], $shopdefcurr),
			'SHOP_ROW_DATE' => cot_date('datetime_medium',$row['order_date']),
			'SHOP_ROW_ORDERSTATE' => $odstate,
			'SHOP_ROW_ID' => htmlspecialchars($row['order_id']),
			'SHOP_ROW_DETAILSURL' => cot_url('plug', 'e=shop&m=tools&id='.$row['order_id']),
			'SHOP_ROW_DELETEURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&delid='.$row['order_id']),
			'SHOP_ROW_ACCEPTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&accid='.$row['order_id']),
			'SHOP_ROW_BOUGHTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&bid='.$row['order_id']),
			'SHOP_ROW_UNACCEPTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&unaccid='.$row['order_id']),
			'SHOP_ROW_UNBOUGHTURL' => cot_url('plug', 'e=shop&m=tools&filter='.$filter.'&unbid='.$row['order_id']),
			'SHOP_ROW_ODDEVEN' => cot_build_oddeven($jj),
			'SHOP_ROW_NUM' => $jj
		));
		$t->parse('MAIN.ORDERS.ROW');
	}

	if($jj == 0)
	{
		$t->parse('MAIN.ORDERS.NOROW');
	}

	$pagenav = cot_pagenav('plug', 'e=shop&m=tools&&filter'.$filter, $d, $totallines, $cfg['maxrowsperpage']);

	$t->assign(array(
		"SHOP_PAGINATION" => $pagenav['main'],
		"SHOP_PAGEPREV" => $pagenav['prev'],
		"SHOP_PAGENEXT" => $pagenav['next'],
		"SHOP_FORM_URL" => cot_url('plug', 'e=shop&m=tools&a=autodel&filter='.$filter),
		"SHOP_FORM_SELECT" => cot_selectbox(0, 'rordertype', array('apply','payed'), $L['admin_autodel']),
		"SHOP_FORM_DATE" => cot_selectbox_date($sys['now_offset']+$usr['timezone']*3600 - 259200, 'short'),
	));
	$t->parse('MAIN.ORDERS');
}

?>