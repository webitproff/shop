<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=list
File=shop.list.query
Hooks=list.query2
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

/**
 * shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (c) 2008-2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL');
$shop_sale = sed_import('shop_sale','G','INT');
$list_url_path['shop_sale'] = $shop_sale;
if ($shop_sale)
{
	$shopcat = sed_structure_children($c);
	$shopcat = (count($shopcat)) ? " AND page_cat IN ('". implode("', '", $shopcat) ."') " : '';
	
	$catwhere = $shopcat;
	$where .=" AND {$shopcfg['instock']} > 0 AND ({$shopcfg['price_old']} > 0)";
}
?>