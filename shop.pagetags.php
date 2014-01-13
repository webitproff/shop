<?php

/* ====================
  [BEGIN_COT_EXT]
  Hooks=pagetags.main
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

global $shop, $shopcfg, $shopcurr, $shopdefcurr, $shopconv;

$price = ((float)$page_data[$shopcfg['price']] > 0) ? (float)$page_data[$shopcfg['price']] : 0;
$price_old = (float)$page_data[$shopcfg['price_old']];

$sale = ($price_old > 0 && $price < $price_old) ? round((($price_old - $price) / ($price_old / 100))) : 0;
$p_url = (empty($page_data['page_alias'])) ? 'c='.$page_data['page_cat'].'&id='.$page_data['page_id'] : 'c='.$page_data['page_cat'].'&al='.$page_data['page_alias'];

$temp_array['SHOP_PRICE'] = cot_format_money($price, $shopcurr);
$temp_array['SHOP_PRICE_OLD'] = cot_format_money($price_old, $shopcurr);
$temp_array['SHOP_PRICE_DEF'] = cot_format_money($price, $shopdefcurr);
$temp_array['SHOP_PRICE_OLD_DEF'] = cot_format_money($price_old, $shopdefcurr);
$temp_array['SHOP_SALE'] = $sale;
$temp_array['SHOP_HAVESALE'] = (($sale > 0) || ($price_old > 0));
$temp_array['SHOP_BUYURL'] = ($page_data[$shopcfg['instock']]) ? cot_url('page', $p_url.'&buyid='.$page_data['page_id']) : '';
$temp_array['SHOP_UNBUYURL'] = ($page_data[$shopcfg['instock']]) ? cot_url('page', $p_url.'&unbuyid='.$page_data['page_id']) : '';
$temp_array['SHOP_BUYURL_AJAX'] = ($page_data[$shopcfg['instock']]) ? cot_url('plug', 'r=shop&action=cart&buyid='.$page_data['page_id']) : '';
$temp_array['SHOP_UNBUYURL_AJAX'] = ($page_data[$shopcfg['instock']]) ? cot_url('plug', 'r=shop&action=cart&unbuyid='.$page_data['page_id']) : '';
$temp_array['SHOP_INCART'] = (int)$shop['shopping'][$page_data['page_id']]['count'];

?>