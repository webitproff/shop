<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.query
[END_COT_EXT]
==================== */

/**
 * shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2008-2010 esclkm
 */

defined('COT_CODE') or die('Wrong URL');

$shop_sale = cot_import('shop_sale','G','INT');
$list_url_path['shop_sale'] = $shop_sale;
if ($shop_sale)
{
	$shopcat = cot_structure_children('page', $c);
	$shopcat = (count($shopcat)) ? " AND page_cat IN ('". implode("', '", $shopcat) ."') " : '';
	
	$where['cat'] = $shopcat;
	$where['instock'] ="{$shopcfg['instock']} > 0 AND ({$shopcfg['price_old']} > 0)";
}
?>