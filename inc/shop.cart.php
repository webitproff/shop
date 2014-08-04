<?php
/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (с) 2010 esclkm
 */

defined('COT_CODE') or die('Wrong URL');

$mskin = cot_tplfile(array('shop', 'cart'), 'plug');
$t = new XTemplate($mskin);

if ($a == 'clear')
{
	unset($shop);
	unset($_SESSION['shop']);
}
if ($a == 'update')
{
	$shopcount = cot_import('shopcount', 'R', 'ARR');
	$shopdesc = cot_import('shopdesc', 'R', 'ARR');
	foreach($shop['shopping'] as $key => $row)
	{
		$t_count = (int)cot_import($shopcount[$key], 'D', 'INT');
		$t_desc = (int)cot_import($shopdesc[$key], 'D', 'TXT');

		/* === Hook === */
		foreach (cot_getextplugins('shop.changecount') as $pl)
		{
			include $pl;
		}
		/* ===== */

		if($t_count < 1)
		{
			unset($shop['shopping'][$key]);
		}
		else
		{

			$shop['shopping'][$key]['count'] = $t_count;
			$shop['shopping'][$key]['desc'] = $t_desc;
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

	$sql = $db->query("SELECT * FROM $db_pages WHERE page_id IN ('".implode("','", $shoparray)."')");

	while ($row = $sql->fetch())
	{
		$jj++;
		$t->assign(cot_generate_pagetags($row, 'SHOP_ROW_'));
		$t->assign(array(
			'SHOP_ROW_DELETE_URL' =>  cot_url('plug', 'e=shop&unbuyid='.$row['page_id']),
			'SHOP_ROW_COUNT' => $shop['shopping'][$row['page_id']]['count'],
			'SHOP_ROW_COUNT_INPUT' => cot_inputbox('text', 'shopcount['.$row['page_id'].']', $shop['shopping'][$row['page_id']]['count'], 'size="5" maxlength="11"'),
			'SHOP_ROW_DESC_INPUT' => cot_inputbox('text', 'shopdesc['.$row['page_id'].']', $shop['shopping'][$row['page_id']]['desc']),
			'SHOP_ROW_PRICE' => cot_format_money($shop['shopping'][$row['page_id']]['price'], $shopcurr),
			'SHOP_ROW_TOTAL' => cot_format_money($shop['shopping'][$row['page_id']]['total'], $shopcurr),
			'SHOP_ROW_PRICE_DEF' => cot_format_money($shop['shopping'][$row['page_id']]['price'], $shopdefcurr),
			'SHOP_ROW_TOTAL_DEF' => cot_format_money($shop['shopping'][$row['page_id']]['total'], $shopdefcurr),
			'SHOP_ROW_ODDEVEN' => cot_build_oddeven($jj),
			'SHOP_ROW_NUM' => $jj
		));
		$t->parse('MAIN.SHOP.ROW');


	}
	$t->assign(array(
		'SHOP_UPDATE_URL' => cot_url('plug', 'e=shop&a=update'),
		'SHOP_CLEARCART_URL' => cot_url('plug', 'e=shop&a=clear'),
		'SHOP_CHECKOUT_URL' => cot_url('plug', 'e=shop&m=checkout'),
		'SHOP_COUNT' => $shop['count'],
		'SHOP_TOTAL' => cot_format_money($shop['total'], $shopcurr),
		'SHOP_TOTAL_DEF' => cot_format_money($shop['total'], $shopdefcurr)
	));
	$t->parse('MAIN.SHOP');
}
elseif ($jj == 0)
{
	$t->parse('MAIN.EMPTYCART');
}


?>