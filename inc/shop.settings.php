<?php

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2010 esclkm
 */
defined('COT_CODE') or die('Wrong URL');

//Plug config
global $db_orders, $db_orders_desc, $db_x;
$db_orders = (isset($db_orders)) ? $db_orders : $db_x . 'orders';
$db_orders_desc = (isset($db_orders_desc)) ? $db_orders_desc : $db_x . 'orders_desc';

$shopcfg['price'] = (!empty($cfg['plugin']['shop']['price'])) ? $cfg['plugin']['shop']['price'] : 'page_shop_price';
$shopcfg['price_old'] = (!empty($cfg['plugin']['shop']['price_old'])) ? $cfg['plugin']['shop']['price_old'] : 'page_shop_price_old';
$shopcfg['instock'] = (!empty($cfg['plugin']['shop']['instock'])) ? $cfg['plugin']['shop']['instock'] : 'page_shop_instock';

$shopcfg['payernamefld'] = (!empty($cfg['plugin']['shop']['payernamefld'])) ? $cfg['plugin']['shop']['payernamefld'] : 'user_usr_name';
$shopcfg['payerphonefld'] = (!empty($cfg['plugin']['shop']['payerphonefld'])) ? $cfg['plugin']['shop']['payerphonefld'] : 'user_usr_phone';
$shopcfg['payeraddressfld'] = (!empty($cfg['plugin']['shop']['payeraddressfld'])) ? $cfg['plugin']['shop']['payeraddressfld'] : 'user_usr_address';
$shopcfg['cachetime'] = 3600;

$shopcfg['thousands'] = (isset($cfg['plugin']['shop']['thousands'])) ? $cfg['plugin']['shop']['thousands'] : ' ';
$shopcfg['decsep'] = (isset($cfg['plugin']['shop']['decsep'])) ? $cfg['plugin']['shop']['decsep'] : '.';


function cot_format_money($money, $currency)
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
?>