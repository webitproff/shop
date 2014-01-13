<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=global
File=shop.global
Hooks=global
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
require_once(sed_langfile('shop'));

//Plug config
$db_orders = $db_x . 'orders';
$db_orders_desc = $db_x . 'orders_desc';

$shopcfg['price'] = (!empty($cfg['plugin']['shop']['price'])) ? $cfg['plugin']['shop']['price'] : 'page_shop_price';
$shopcfg['price_old'] = (!empty($cfg['plugin']['shop']['price_old'])) ? $cfg['plugin']['shop']['price_old'] : 'page_shop_price_old';
$shopcfg['instock'] = (!empty($cfg['plugin']['shop']['instock'])) ? $cfg['plugin']['shop']['instock'] : 'page_shop_instock';

$shopcfg['payernamefld'] = (!empty($cfg['plugin']['shop']['payernamefld'])) ? $cfg['plugin']['shop']['payernamefld'] : 'user_usr_name';
$shopcfg['payerphonefld'] = (!empty($cfg['plugin']['shop']['payerphonefld'])) ? $cfg['plugin']['shop']['payerphonefld'] : 'user_usr_phone';
$shopcfg['payeraddressfld'] = (!empty($cfg['plugin']['shop']['payeraddressfld'])) ? $cfg['plugin']['shop']['payeraddressfld'] : 'user_usr_address';
$shopcfg['cachetime'] = 3600;

$shopcfg['thousands'] = (!empty($cfg['plugin']['shop']['thousands'])) ? $cfg['plugin']['shop']['thousands'] : ' ';
$shopcfg['decsep'] = (!empty($cfg['plugin']['shop']['decsep'])) ? $cfg['plugin']['shop']['decsep'] : '.';

// end plug config

function sed_format_money($money, $currency)
{
	global $L, $shopconv, $shopdefcurr, $shopcfg;

	$currency = (!isset($shopconv[$currency])) ? $shopdefcurr : $currency;

	$pre = isset($L['shop_' . $currency . '_pre']) ? $L['shop_' . $currency . '_pre'] : $shopconv[$currency]['pretext'];
	$post = isset($L['shop_' . $currency . '_post']) ? $L['shop_' . $currency . '_post'] : $shopconv[$currency]['posttext'];
	$price_curr = ($currency != 'NO' && isset($L['shop_' . $currency . '_name'])) ? $L['shop_' . $currency . '_name'] : $shopconv[$currency]['name'];

	if ($shopconv[$currency]['xchrate']['multiply'] != $shopconv[$currency]['xchrate']['devide'])
	{
		$money = $money * $shopconv[$currency]['xchrate']['multiply'] / $shopconv[$currency]['xchrate']['devide'];
	}

	return $pre . number_format($money, $shopconv[$currency]['decimals'], $shopcfg['decsep'], $shopcfg['thousands']) . $post;
}

function sed_generate_pagetags_currency($rpage_array, $tag_prefix = '')
{
	global $L, $db_pages, $shop, $shopcfg, $shopcurr, $shopdefcurr, $shopconv;

	if (is_int($rpage_array))
	{
		$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id = '" . (int)$rpage_array . "' LIMIT 1");
		$rpage_array = sed_sql_fetchassoc($sql);
	}

	if ($rpage_array['page_id'] > 0)
	{
		$page_urlp = empty($rpage_array['page_alias']) ? 'id=' . $rpage_array['page_id'] : 'al=' . $rpage_array['page_alias'];
		$id = $rpage_array['page_id'];

		$price = ((float)$rpage_array[$shopcfg['price']] > 0) ? (float)$rpage_array[$shopcfg['price']] : 0;
		$price_old = (float)$rpage_array[$shopcfg['price_old']];

		$sale = ($price_old > 0 && $price < $price_old) ? round((($price_old - $price) / ($price_old / 100))) : 0;

		$return_array = array(
			$tag_prefix . 'SHOP_PRICE' => sed_format_money($price, $shopcurr),
			$tag_prefix . 'SHOP_PRICE_OLD' => sed_format_money($price_old, $shopcurr),
			$tag_prefix . 'SHOP_PRICE_DEF' => sed_format_money($price, $shopdefcurr),
			$tag_prefix . 'SHOP_PRICE_OLD_DEF' => sed_format_money($price_old, $shopdefcurr),
			$tag_prefix . 'SHOP_SALE' => $sale,
			$tag_prefix . 'SHOP_HAVESALE' => (($sale > 0) || ($price_old > 0)),
			$tag_prefix . 'SHOP_BUYURL' => ($rpage_array[$shopcfg['instock']]) ? sed_url('page', $page_urlp . '&buyid=' . $id) : '',
			$tag_prefix . 'SHOP_UNBUYURL' => ($rpage_array[$shopcfg['instock']]) ? sed_url('page', $page_urlp . '&unbuyid=' . $id) : '',
			$tag_prefix . 'SHOP_BUYURL_AJAX' => ($rpage_array[$shopcfg['instock']]) ? sed_url('plug', 'r=shop&action=cart&buyid=' . $id) : '',
			$tag_prefix . 'SHOP_UNBUYURL_AJAX' => ($rpage_array[$shopcfg['instock']]) ? sed_url('plug', 'r=shop&action=cart&unbuyid=' . $id) : '',
			$tag_prefix . 'SHOP_INCART' => (int)$shop['shopping'][$id]['count']
		);
	}
	return $return_array;
}

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
	sed_cache_store('shopconv', $shopconv, $shopcfg['cachetime']);
}
//end Currency Exchange table

$buyid = sed_import('buyid', 'G', 'INT');
$unbuyid = sed_import('unbuyid', 'G', 'INT');
$scount = sed_import('scount', 'G', 'INT');
$scount = ((int)$scount > 0) ? $scount : 1;

$shop = &$_SESSION['shop'];

reset($shopconv);
$shopdefcurr = array_keys($shopconv);
$shopdefcurr = $shopdefcurr[0];

$shopxcurr = sed_import('shopcurr', 'G', 'ALP');
$shopxcurr = ($shopxcurr) ? $shopxcurr : sed_import('shopcurr', 'P', 'ALP');

$shop['currency'] = ($shopxcurr) ? $shopxcurr : $shop['currency'];
$shopcurr = ($shop['currency'] && isset($shopconv[$shop['currency']])) ? $shop['currency'] : $shopdefcurr;

$shopchanged = false;
if ((int)$buyid > 0)
{
	$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id = '" . (int)$buyid . "' LIMIT 1");
	$rpage_array = sed_sql_fetchassoc($sql);
	if ($rpage_array['page_id'] > 0 && $rpage_array[$shopcfg['instock']])
	{
		$price = (float)$rpage_array[$shopcfg['price']];
		$price = ((float)$price > 0) ? $price : 0;

		$shop['shopping'][$buyid]['count'] = (isset($shop['shopping'][$buyid])) ? $shop['shopping'][$buyid]['count'] + $scount : $scount;
		$shop['shopping'][$buyid]['price'] = $price;
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

$cartt = new XTemplate(sed_skinfile(array('shop', 'global'), true));
$cartt->assign(array(
	'SHOP_COUNT' => $SHOPCOUNT,
	'SHOP_TOTAL' => $SHOPTOTAL,
	'SHOP_COUNTDEC' => sed_declension($SHOPCOUNT, $Ls['shop_goods']),
	'SHOP_TOTALDEC' => sed_format_money($SHOPTOTAL, $shopcurr),
	'SHOP_TOTALDEC_DEF' => sed_format_money($SHOPTOTAL, $shopdefcurr),
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
	$SHOP_CURRENCYSELECTOR = sed_efselectbox($shopcurr, 'shopcurr', $xk, $xv, false, 'onchange="this.form.submit()"');
}

$cartt->parse('MAIN');
$SHOPCART = $cartt->text('MAIN');
?>