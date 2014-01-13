<?php
/**
 * Shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (с) 2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL');

list($usr['shop_auth_read'], $usr['shop_auth_write'], $usr['shop_isadmin']) = sed_auth('plug', 'shop');

(!$usr['shop_isadmin']) or sed_die();

$filter = sed_import('filter', 'G', 'ALP');
$d = sed_import('d','G','INT');
$d = (empty($d)) ? '0' : $d;

$id = sed_import('id','G','INT');
$delid = sed_import('delid','G','INT');
$accid = sed_import('accid','G','INT');
$bid = sed_import('bid','G','INT');
$unaccid = sed_import('unaccid','G','INT');
$unbid = sed_import('unbid','G','INT');
$file = sed_import('file','G','TXT');

if((int)$delid > 0)
{
	$sql = sed_sql_query("DELETE FROM $db_orders_desc WHERE od_orderid='".$delid."'");
	$sql = sed_sql_query("DELETE FROM $db_orders WHERE order_id='".$delid."'");
}
if((int)$accid > 0)
{
	$sql = sed_sql_query("UPDATE $db_orders SET order_apply='".(int)$usr['id']."' WHERE order_id='".(int)$accid."'");
}
if((int)$bid > 0)
{
	$sql = sed_sql_query("UPDATE $db_orders SET order_payed='".(int)$usr['id']."' WHERE order_id='".(int)$bid."'");
}
if((int)$unaccid > 0)
{
	$sql = sed_sql_query("UPDATE $db_orders SET order_apply='0' WHERE order_id='".(int)$unaccid."'");
}
if((int)$unbid > 0)
{
	$sql = sed_sql_query("UPDATE $db_orders SET order_payed='0' WHERE order_id='".(int)$unbid."'");
}

if ($a == 'autodel')
{
	$ryear = sed_import('ryear','P','INT');
	$rmonth = sed_import('rmonth','P','INT');
	$rday = sed_import('rday','P','INT');

	$rpagedate = sed_mktime(0, 0, 0, $rmonth, $rday, $ryear) - $usr['timezone'] * 3600;

	$rordertype = sed_import('rordertype','P','ALP');
	$rordertype = ($rordertype == "payed") ? 'payed' : 'apply';

	$sql = sed_sql_query("SELECT order_id FROM $db_orders
		WHERE order_$rordertype = 0 AND order_date < $rpagedate");

	while($row = sed_sql_fetchassoc($sql))
	{
		$sql2 = sed_sql_query("DELETE FROM $db_orders_desc WHERE od_orderid='".$row['order_id']."'");
		$sql2 = sed_sql_query("DELETE FROM $db_orders WHERE order_id='".$row['order_id']."'");
	}
}
if ($a == 'generate' && !empty($file))
{
	$extf = sed_import('extf','G','TXT');
	$extf = (!$extf) ? 'txt' : $extf;
	
	$instock =  sed_import('instock','G','BOL') ? 1 : 0;
	
	$cfg['html_cleanup']= false;

	$mskin = sed_skinfile(array('shop', 'export', $file), true);
	$t = new XTemplate($mskin);

	$shopcat = sed_structure_children($cfg['plugin']['shop']['cat'], true, true, false);
	$shopcat = (count($shopcat)) ? " AND page_cat IN ('". implode("', '", $shopcat) ."')" : '';
	$instock = ($instock) ?  " AND {$shopcfg['instock']} = 1" : '';
	
	$sql2 = "SELECT * FROM $db_pages WHERE (page_state=0 OR page_state=2) $shopcat  $instock";
	$jj=0;
	while($row2 = sed_sql_fetcharray($sql2))
	{
		$jj++;
		preg_match('/src="([^"]*)"/', $sed_cat[$row2['page_cat']]['icon'], $matches);
		$rowicon = explode('|', $matches[1]);

		$row2['page_html'] = str_replace( array( "\n", "\r", "\t" ), '', $row2['page_html']);
		$row2['page_text'] = str_replace( array( "\n", "\r", "\t" ), '', $row2['page_text']);
		$row2['page_desc'] = str_replace( array( "\n", "\r", "\t" ), '', $row2['page_desc']);

		$row2['page_html'] = str_replace( "\"", '&quot;', $row2['page_html']);
		$row2['page_text'] = str_replace( "\"", '&quot;', $row2['page_text']);
		$row2['page_desc'] = str_replace( "\"", '&quot;', $row2['page_desc']);
			
		$textpag = strip_tags((empty($row2['page_html']) ? $row2['page_text'] : $row2['page_html']));
		$t1->assign(sed_generate_pagetags($row2, 'SHOP_ROW_', '', false));
		$t1->assign(sed_generate_pagetags_currency($row2, 'SHOP_ROW_'));
		$t1->assign(array(
			'SHOP_ROW_CODES' => $rowicon,
			'SHOP_ROW_CODE' => $matches[1],
			'SHOP_ROW_CODE1' => $rowicon[0],
			'SHOP_ROW_CODE2' => $rowicon[1],
			'SHOP_ROW_CODE3' => $rowicon[2],
			'SHOP_ROW_NOHTML' => $textpag,
			'SHOP_ROW_NOHTMLDESC' => strip_tags($row2['page_desc']),
			'SHOP_ROW_ODDEVEN' => sed_build_oddeven($jj),
			'SHOP_ROW_NUM' => $jj,
			'SEP' => '[[br]]',
		));/**/
			
		$t1->parse('MAIN.ROW');
	}
	
	$t1->parse('MAIN');
	$xtext = $t1->text('MAIN');
	$xtext = iconv("UTF-8","WINDOWS-1251",$xtext);
	$xtext = str_replace('[[br]]', "\r\n" , $xtext);
	file_put_contents('datas/export/'.$file.'.'.$extf, $xtext);

	if(!empty($file) && file_exists('datas/export/'.$file.'.'.$extf))
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public", false);
		header("Content-Description: File Transfer");
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment;filename="'.$file.date("_Ymd-Hi").'.'.$extf.'"');
		header("Content-Transfer-Encoding: binary");
		readfile('datas/export/'.$file.'.'.$extf);
		exit;
	}
}

$mskin = sed_skinfile(array('shop', 'tools'), true);
$t = new XTemplate($mskin);

if((int)$id > 0)
{
	$sql = sed_sql_query("SELECT * FROM $db_orders WHERE order_id = ".(int)$id." LIMIT 1");
	sed_die(sed_sql_numrows($sql)==0);
	$row = sed_sql_fetchassoc($sql);
	$sql2 = sed_sql_query("SELECT o.*, p.* FROM $db_orders_desc AS o
		JOIN $db_pages AS p ON o.od_pageid=p.page_id
		WHERE od_orderid = ".(int)$id);
	$jj = 0;
	while($row2 = sed_sql_fetcharray($sql2))
	{
		$jj++;
		$t->assign(sed_generate_pagetags($row2, 'SHOP_ROW_'));
		$t->assign(sed_generate_pagetags_currency($row2, 'SHOP_ROW_'));
		$t->assign(sed_generate_pagetags($row2, 'SHOP_ROW_'));
		$t->assign(array(
			'SHOP_ROW_COUNT' => $row2['od_count'],
			'SHOP_ROW_PRICE' => sed_format_money($row2['od_price'], $row['order_currency']),
			'SHOP_ROW_TOTAL' => sed_format_money($row2['od_total'], $row['order_currency']),
			'SHOP_ROW_PRICE_DEF' => sed_format_money($row2['od_price'], $shopdefcurr),
			'SHOP_ROW_TOTAL_DEF' => sed_format_money($row2['od_total'], $shopdefcurr),			
			'SHOP_ROW_ODDEVEN' => sed_build_oddeven($jj),
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

	$t->assign(sed_generate_usertags((int)$row['order_userid'], 'SHOP_PAYER_'));
	if($row['order_apply'] > 0)
	{
		$t->assign(sed_generate_usertags((int)$row['order_apply'], 'SHOP_VALIDATOR_'));
	}
	if($row['order_payed'] > 0)
	{
		$t->assign(sed_generate_usertags((int)$row['order_payed'], 'SHOP_VALIDATOR2_'));
	}

	$odstate = ($row['order_apply'] > 0) ? 1 : 0;
	$odstate = ($row['order_payed'] > 0) ? 2 : $odstate;
	$t->assign(array(
		'SHOP_PAYERNAME' => htmlspecialchars($row['order_payername']),
		'SHOP_PAYERPHONE' => htmlspecialchars($row['order_payerphone']),
		'SHOP_PAYEREMAIL' => htmlspecialchars($row['order_payeremail']),
		'SHOP_PAYERADDRESS' => htmlspecialchars($row['order_payeraddress']),
		'SHOP_PAYEROTHER' => htmlspecialchars($row['order_payerother']),
		'SHOP_PAYERTOTAL' => sed_format_money($row['order_total'], $row['order_currency']),
		'SHOP_PAYERTOTAL_DEF' => sed_format_money($row['order_total'], $shopdefcurr),
		'SHOP_DATE' => @date($cfg['formatyearmonthday'], $row['order_date'] + $usr['timezone'] * 3600),
		'SHOP_ID' => htmlspecialchars($row['order_id']),
		'SHOP_ORDERSTATE' => $odstate,
		'SHOP_DELETEURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&delid='.$row['order_id']),
		'SHOP_ACCEPTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&accid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_BOUGHTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&bid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_UNACCEPTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&unaccid='.$row['order_id'].'&id='.$row['order_id']),
		'SHOP_UNBOUGHTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&unbid='.$row['order_id'].'&id='.$row['order_id']),
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
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_orders WHERE $whereshop");
	$totallines = sed_sql_result($sql, 0, 0);

	$sql = sed_sql_query("SELECT * FROM $db_orders 
		WHERE $whereshop ORDER BY order_id DESC LIMIT $d,".$cfg['maxrowsperpage']);

	$jj = 0;
	while($row = sed_sql_fetchassoc($sql))
	{
		$jj++;
		$t->assign(sed_generate_usertags((int)$row['order_userid'], 'SHOP_ROW_PAYER_'));
		if($row['order_apply'] > 0)
		{
			$t->assign(sed_generate_usertags((int)$row['order_apply'], 'SHOP_ROW_VALIDATOR_'));
		}
		if($row['order_payed'] > 0)
		{
			$t->assign(sed_generate_usertags((int)$row['order_payed'], 'SHOP_ROW_VALIDATOR2_'));
		}
		$odstate = ($row['order_apply'] > 0) ? 1 : 0;
		$odstate = ($row['order_payed'] > 0) ? 2 : $odstate;
		$t->assign(array(
			'SHOP_ROW_PAYERNAME' => htmlspecialchars($row['order_payername']),
			'SHOP_ROW_PAYERPHONE' => htmlspecialchars($row['order_payerphone']),
			'SHOP_ROW_PAYEREMAIL' => htmlspecialchars($row['order_payeremail']),
			'SHOP_ROW_PAYERADDRESS' => htmlspecialchars($row['order_payeraddress']),
			'SHOP_ROW_PAYEROTHER' => htmlspecialchars($row['order_payerother']),
			'SHOP_ROW_PAYERTOTAL' => sed_format_money($row['order_total'], $row['order_currency']),
			'SHOP_ROW_PAYERTOTAL_DEF' => sed_format_money($row['order_total'], $shopdefcurr),
			'SHOP_ROW_DATE' => @date($cfg['formatyearmonthday'], $row['order_date'] + $usr['timezone'] * 3600),
			'SHOP_ROW_ORDERSTATE' => $odstate,
			'SHOP_ROW_ID' => htmlspecialchars($row['order_id']),
			'SHOP_ROW_DETAILSURL' => sed_url('plug', 'e=shop&m=tools&id='.$row['order_id']),
			'SHOP_ROW_DELETEURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&delid='.$row['order_id']),
			'SHOP_ROW_ACCEPTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&accid='.$row['order_id']),
			'SHOP_ROW_BOUGHTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&bid='.$row['order_id']),
			'SHOP_ROW_UNACCEPTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&unaccid='.$row['order_id']),
			'SHOP_ROW_UNBOUGHTURL' => sed_url('plug', 'e=shop&m=tools&filter='.$filter.'&unbid='.$row['order_id']),
			'SHOP_ROW_ODDEVEN' => sed_build_oddeven($jj),
			'SHOP_ROW_NUM' => $jj
		));
		$t->parse('MAIN.ORDERS.ROW');
	}

	if($jj == 0)
	{
		$t->parse('MAIN.ORDERS.NOROW');
	}

	$pagination = sed_pagination(sed_url('plug', 'e=shop&m=tools&&filter'.$filter), $d, $totallines, $cfg['maxrowsperpage']);
	list($pageprev, $pagenext) = sed_pagination_pn(sed_url('plug', 'e=shop&m=tools&filter='.$filter), $d, $totallines, $cfg['maxrowsperpage'], TRUE);

	$page_form_type = '<select name="rordertype" size="1">
		<option value="apply">'.$L['admin_autodel'][1].'</option>
		<option value="payed">'.$L['admin_autodel'][2].'</option>
		</select>';

	$t->assign(array(
		"SHOP_PAGINATION" => $pagination,
		"SHOP_PAGEPREV" => $pageprev,
		"SHOP_PAGENEXT" => $pagenext,
		"SHOP_FORM_URL" => sed_url('plug', 'e=shop&m=tools&a=autodel&filter='.$filter),
		"SHOP_FORM_SELECT" => $page_form_type,
		"SHOP_FORM_DATE" => sed_selectbox_date($sys['now_offset']+$usr['timezone']*3600 - 259200, 'short'),
		"SHOP_GENERATE_XML" => sed_url('plug', 'e=shop&m=tools&a=generate&filter='.$filter),
		"SHOP_GENERATE_INSTOCK_XML" => sed_url('plug', 'e=shop&m=tools&a=generate&instock='.$instock.'&filter='.$filter),
	));
	$t->parse('MAIN.ORDERS');
}

?>