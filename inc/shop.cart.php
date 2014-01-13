<?php
/**
 * Shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (с) 2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL');

$mskin = sed_skinfile(array('shop', 'cart'), true);
$t = new XTemplate($mskin);

if ($a == 'clear')
{
	unset($shop);
	unset($_SESSION['shop']);
}
if ($a == 'update')
{
	$shopcount = sed_import('shopcount', 'P', 'ARR');
	foreach($shop['shopping'] as $key => $row)
	{
		$t_count = (int)sed_import($shopcount[$key], 'D', 'INT');
		if($t_count < 1)
		{
			unset($shop['shopping'][$key]);
		}
		else
		{
			$shop['shopping'][$key]['count'] = $t_count;
			$shop['shopping'][$key]['total'] = $t_count * (float)$shop['shopping'][$key]['price'];
		}
	}
	$shop['count'] = 0;
	$shop['total'] = 0;
	foreach($shop['shopping'] as $shopping)
	{
		$shop['count'] += $shopping['count'];
		$shop['total'] += $shopping['total'];
	}
	$_SESSION['shop'] = $shop;
}
$jj = 0;
if ((int)$shop['count'] > 0)
{
	foreach ($shop['shopping'] as $key => $row)
	{
		$shoparray[]=(int)$key;
	}

	$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id IN ('".implode("','", $shoparray)."')");

	while ($row = sed_sql_fetchassoc($sql))
	{
		$jj++;
		$t->assign(sed_generate_pagetags($row, 'SHOP_ROW_'));
		$t->assign(sed_generate_pagetags_currency($row, 'SHOP_ROW_'));
		$t->assign(array(
			'SHOP_ROW_DELETE_URL' =>  sed_url('plug', 'e=shop&unbuyid='.$row['page_id']),
			'SHOP_ROW_COUNT' => $shop['shopping'][$row['page_id']]['count'],
			'SHOP_ROW_COUNT_INPUT' => '<input type="text" class="text" name="shopcount['.$row['page_id'].']" value="'.$shop['shopping'][$row['page_id']]['count'].'" size="5" maxlength="11" />',
			'SHOP_ROW_PRICE' => sed_format_money($shop['shopping'][$row['page_id']]['price'], $shopcurr),
			'SHOP_ROW_TOTAL' => sed_format_money($shop['shopping'][$row['page_id']]['total'], $shopcurr),
			'SHOP_ROW_PRICE_DEF' => sed_format_money($shop['shopping'][$row['page_id']]['price'], $shopdefcurr),
			'SHOP_ROW_TOTAL_DEF' => sed_format_money($shop['shopping'][$row['page_id']]['total'], $shopdefcurr),
			'SHOP_ROW_ODDEVEN' => sed_build_oddeven($jj),
			'SHOP_ROW_NUM' => $jj
		));
		$t->parse('MAIN.SHOP.ROW');


	}
	$t->assign(array(
		'SHOP_UPDATE_URL' => sed_url('plug', 'e=shop&a=update'),
		'SHOP_CLEARCART_URL' => sed_url('plug', 'e=shop&a=clear'),
		'SHOP_CHECKOUT_URL' => sed_url('plug', 'e=shop&m=checkout'),
		'SHOP_COUNT' => $shop['count'],
		'SHOP_TOTAL' => sed_format_money($shop['total'], $shopcurr),
		'SHOP_TOTAL_DEF' => sed_format_money($shop['total'], $shopdefcurr)
	));
	$t->parse('MAIN.SHOP');
}
elseif ($jj == 0)
{
	$t->parse('MAIN.EMPTYCART');
}


?>