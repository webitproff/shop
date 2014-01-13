<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=global
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


//Plug config
require_once cot_langfile('shop');
require_once cot_incfile('shop', 'plug', 'settings');

// end plug config

// 
//Currency Exchange table
if (!$shopconv)
{
	$cfg['plugin']['shop']['conversion'] = (!empty($cfg['plugin']['shop']['conversion'])) ? $cfg['plugin']['shop']['conversion'] : 'NO|1/1';
	$set = explode("\n", str_replace("\r\n", "\n", $cfg['plugin']['shop']['conversion']));

	foreach ($set as $val)
	{
		$val = explode('|', $val);
		$val[4] = (float)(trim($val[4]));
		$val[1] = explode('/', $val[1]);
		$val[0] = trim($val[0]);

		$shopconv[$val[0]] = array(
			'code' => $val[0],
			'xchrate' => array('multiply' => ((float)$val[1][0] > 0) ? (float)$val[1][0] : 1, 'devide' => ((float)$val[1][1]) ? (float)$val[1][1] : 1),
			'pretext' => $val[3],
			'desc' => trim($val[2]),
			'decimals' => ($val[4] > 0) ? $val[4] : 0,
			'posttext' => $val[5]
		);
	}
	$cache && $cache->db->store('shopconv', $shopconv, 'system', $shopcfg['cachetime']);
}
//end Currency Exchange table

$buyid = cot_import('buyid', 'G', 'INT');
$unbuyid = cot_import('unbuyid', 'G', 'INT');
$scount = cot_import('scount', 'R', 'INT');
$scount = ((int)$scount > 0) ? $scount : 1;
$sdesc = cot_import('sdesc', 'R', 'TXT');

$shop = &$_SESSION['shop'];

reset($shopconv);
$shopdefcurr = array_keys($shopconv);
$shopdefcurr = $shopdefcurr[0];

$shopxcurr = cot_import('shopcurr', 'G', 'ALP');
$shopxcurr = ($shopxcurr) ? $shopxcurr : cot_import('shopcurr', 'P', 'ALP');

$shop['currency'] = ($shopxcurr) ? $shopxcurr : $shop['currency'];
$shopcurr = ($shop['currency'] && isset($shopconv[$shop['currency']])) ? $shop['currency'] : $shopdefcurr;

$shopchanged = false;
if ((int)$buyid > 0)
{
	require_once cot_incfile('page', 'module');
	$sql = $db->query("SELECT * FROM $db_pages WHERE page_id = '" . (int)$buyid . "' LIMIT 1");
	$rpage_array = $sql->fetch();
	if ($rpage_array['page_id'] > 0 && $rpage_array[$shopcfg['instock']])
	{
		$price = (float)$rpage_array[$shopcfg['price']];
		$price = ((float)$price > 0) ? $price : 0;

		$shop['shopping'][$buyid]['count'] = (isset($shop['shopping'][$buyid])) ? $shop['shopping'][$buyid]['count'] + $scount : $scount;
		$shop['shopping'][$buyid]['price'] = $price;
		$shop['shopping'][$buyid]['desc'] = $sdesc;
		$shop['shopping'][$buyid]['total'] += $price * $scount;
		$shopchanged = true;
	}
}
if ((int)$unbuyid > 0)
{
	if ((int)($shop['shopping'][$unbuyid]['count'] - $scount > 0))
	{
		$shop['shopping'][$unbuyid]['count'] = $shop['shopping'][$unbuyid]['count'] - $scount;
		$shop['shopping'][$buyid]['total'] -= $shop['shopping'][$buyid]['price'] * $scount;
	}
	else
	{
		unset($shop['shopping'][$unbuyid]);
	}
	$shopchanged = true;
}
if ($shopchanged == true)
{
	$shop['count'] = 0;
	$shop['total'] = 0;
	foreach ($shop['shopping'] as $shopping)
	{
		$shop['count'] += $shopping['count'];
		$shop['total'] += $shopping['total'];
	}
}

$SHOPCOUNT = ((int)$shop['count'] > 0) ? $shop['count'] : 0;
$SHOPTOTAL = ((float)$shop['total'] > 0) ? $shop['total'] : 0;

$cartt = new XTemplate(cot_tplfile(array('shop', 'global'), 'plug'));
$cartt->assign(array(
	'SHOP_COUNT' => $SHOPCOUNT,
	'SHOP_TOTAL' => $SHOPTOTAL,
	'SHOP_COUNTDEC' => cot_declension($SHOPCOUNT, $Ls['shop_goods']),
	'SHOP_TOTALDEC' => cot_format_money($SHOPTOTAL, $shopcurr),
	'SHOP_TOTALDEC_DEF' => cot_format_money($SHOPTOTAL, $shopdefcurr),
	'SHOP_ADDINCART' => ($buyid > 0) ? 'buy' : ($unbuyid > 0) ? 'unbuy' : ''
));

// shop currency selector
if (count($shopconv) > 1)
{
	reset($shopconv);
	foreach ($shopconv as $k => $v)
	{
			
		$xk[] = $k;
		$xv[] = isset($L['shop_' . $k]) ? $L['shop_' . $k] : $v['desc'];
		
	}
	$SHOP_CURRENCYSELECTOR_URL = $_SERVER['REQUEST_URI'];
	$SHOP_CURRENCYSELECTOR = cot_efselectbox($shopcurr, 'shopcurr', $xk, $xv, false, 'onchange="this.form.submit()"');
}

$cartt->parse('MAIN');
$SHOPCART = $cartt->text('MAIN');
?>