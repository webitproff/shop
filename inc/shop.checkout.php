<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2010 esclkm
 */
defined('COT_CODE') or die('Wrong URL');

$mskin = cot_tplfile(array('shop', 'checkout'), 'plug');
$t = new XTemplate($mskin);

require_once cot_incfile('users', 'module');

$transfered = false;
($shop['count'] < 1) && cot_error('error_emptycart');

if ($a == 'add')
{
	$payername = cot_import('payername', 'P', 'TXT');
	$payerphone = cot_import('payerphone', 'P', 'TXT');
	$payeraddress = cot_import('payeraddress', 'P', 'TXT');
	$payeremail = cot_import('payeremail', 'P', 'TXT');
	$payerother = cot_import('payerother', 'P', 'TXT');
	$payerdesc = cot_import('payerdesc', 'P', 'TXT');	
	$payerpayment = cot_import('payerpayment', 'P', 'INT');
	$payerdelivery = cot_import('payerdelivery', 'P', 'INT');
	
	if(cot_plugin_active('banlist'))
	{
		$sql = $db->query("SELECT banlist_reason, banlist_email FROM $db_banlist WHERE banlist_email!=''");

		while ($row = $sql->fetch())
		{
			if (mb_strpos($row['banlist_email'], $payeraddress) !== false)
			{
				$bannedreason = $row['banlist_reason'];
			}
		}
	}
	empty($bannedreason) || cot_error($L['aut_emailbanned'].$bannedreason);
	empty($payername) && cot_error('error_emptyname');
	empty($payerphone) && cot_error('error_emptyphone');
	empty($payeraddress) && cot_error('error_emptyaddress');
	(empty($payeremail) || !preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$#i', $payeremail)) && cot_error('aut_emailtooshort');

	if ($db->query("SELECT COUNT(*) FROM $db_orders_delivery")->fetchColumn() > 0)
	{
		$delivery = $db->query("SELECT d.*, dp.* FROM $db_orders_delivery AS d
			LEFT JOIN (SELECT * FROM $db_orders_delivery_prices WHERE (oddp_mintotal <= ".(float)$shop['total'].") ORDER BY oddp_price ASC LIMIT 1) 
				AS dp ON dp.oddp_oddid = d.odd_id
			WHERE odd_id=".(int)$payerdelivery." LIMIT 1")->fetch();

		if((float)$delivery['oddp_type'] > 0 && (float)$delivery['oddp_price'] > 0)
		{
			$delivery['oddp_price'] = round((float)$shop['total'] * (float)$delivery['oddp_price'] / 100, 0);
		}
		empty($delivery) && cot_error('error_emptydelivery');
	}
	if ($db->query("SELECT COUNT(*) FROM $db_orders_payments")->fetchColumn() > 0)
	{
		$payment = $db->query("SELECT * FROM $db_orders_payments WHERE odp_id = ".(int)$payerpayment." LIMIT 1")->fetch();
		empty($payment) && cot_error('error_emptypayment');
	}
	if (!cot_error_found())
	{
		if ($usr['id'] < 1 && !$cfg['plugin']['shop']['testmode'])
		{
			$sql = $db->query("SELECT * FROM $db_users WHERE user_email = '".$db->prep($payeremail)."' LIMIT 1");
			$row = $sql->fetch();
			if ($row['user_id'] > 0)
			{
				$userid = $row['user_id'];
			}
			else
			{
				/* === Hook for the plugins === */
				foreach (cot_getextplugins('users.register.add.first') as $pl)
				{
					include $pl;
				}
				/* ===== */

				$ruser['user_name'] = cot_import('payeremail', 'P', 'TXT', 64, TRUE);
				$ruser['user_email'] = cot_import('payeremail', 'P', 'TXT', 64, TRUE);

				$ruser['user_country'] = cot_import('rcountry', 'P', 'TXT');
				$ruser['user_timezone'] = cot_import('rtimezone', 'P', 'TXT', 5);
				$ruser['user_timezone'] = is_null($ruser['user_timezone']) ? $cfg['defaulttimezone'] : (float)$ruser['user_timezone'];
				$ruser['user_gender'] = cot_import('rusergender', 'P', 'TXT');
				$ruser['user_email'] = mb_strtolower($ruser['user_email']);


				// Extra fields
				foreach ($cot_extrafields[$db_users] as $row)
				{
					$ruser['user_'.$row['field_name']] = cot_import_extrafields('ruser'.$row['field_name'], $row);
				}
				$ruser['user_birthdate'] = (int)cot_import_date('ruserbirthdate', false);

				$res1 = $db->query("SELECT COUNT(*) FROM $db_users WHERE user_name='".$db->prep($ruser['user_name'])."'")->fetchColumn();
				$res2 = $db->query("SELECT COUNT(*) FROM $db_users WHERE user_email='".$db->prep($ruser['user_email'])."'")->fetchColumn();

				if (preg_match('/&#\d+;/', $ruser['user_name']) || preg_match('/[<>#\'"\/]/', $ruser['user_name']))
					cot_error('aut_invalidloginchars', 'rusername');
				if (mb_strlen($ruser['user_name']) < 2)
					cot_error('aut_usernametooshort', 'rusername');

				if (mb_strlen($ruser['user_email']) < 4 || !preg_match('#^[\w\p{L}][\.\w\p{L}\-]+@[\w\p{L}\.\-]+\.[\w\p{L}]+$#u', $ruser['user_email']))
					cot_error('aut_emailtooshort', 'ruseremail');
				if ($res1 > 0)
					cot_error('aut_usernamealreadyindb', 'rusername');
				if ($res2 > 0)
					cot_error('aut_emailalreadyindb', 'ruseremail');

				/* === Hook for the plugins === */
				foreach (cot_getextplugins('users.register.add.validate') as $pl)
				{
					include $pl;
				}
				/* ===== */

				if (!cot_error_found())
				{
					$userid = cot_add_user($ruser);

					/* === Hook for the plugins === */
					foreach (cot_getextplugins('users.register.add.done') as $pl)
					{
						include $pl;
					}
					/* ===== */
				}
			}
		}
		else
		{
			$userid = $usr['id'];
		}
		if (!$cfg['plugin']['shop']['testmode'])
		{
			
			$order = array(
				'order_userid' => (int)$userid,
				'order_date' => (int)$sys['now'],
				'order_total' => (float)$shop['total'],
				'order_apply' => 0,
				'order_payed' => 0,
				'order_payername' => $payername,
				'order_payerphone' => $payerphone,
				'order_payeremail' => $payeraddress,
				'order_payeraddress' => $payeraddress,
				'order_currency' => $shopcurr,
				'order_payerother' => $payerother,
				'order_desc' => $payerdesc,
				'order_delivery_type' => (int)$delivery['odd_id'],
				'order_delivery_desc' => $delivery['odd_title'],
				'order_delivery_price' => (float)$delivery['oddp_price'],
				'order_transaction_type' => (int)$payment['odp_id'],
				'order_transaction_desc' => $payment['odp_title']			
				);

			$db->insert($db_orders, $order);
			$orderid = $db->lastInsertId();
			foreach ($shop['shopping'] as $key => $row)
			{
				$shoparray[] = (int)$key;
			}

			$sql = $db->query("SELECT * FROM $db_pages WHERE page_id IN ('".implode("','", $shoparray)."')");
			$ordermailinfo = "";
			$i = 0;
			while ($row = $sql->fetch())
			{
				$i++;
				$orderinfo = array(
					'od_orderid' => (int)$orderid,
					'od_pageid' => (int)$row['page_id'],
					'od_title' => $row['page_title'],
					'od_price' => (float)$shop['shopping'][$row['page_id']]['price'],
					'od_count' => (int)$shop['shopping'][$row['page_id']]['count'],
					'od_total' => (float)$shop['shopping'][$row['page_id']]['total'],
					'od_desc' => $shop['shopping'][$row['page_id']]['desc'],
				);
				$db->insert($db_orders_desc, $orderinfo);
				
				$ordermailinfo .= cot_rc('user_order_info', array(
					'num' => $i,
					'title'  => $row['page_title'],
					'price' => cot_format_money($shop['shopping'][$row['page_id']]['price'], $shopdefcurr),
					'count' => (int)$shop['shopping'][$row['page_id']]['count'],
					'total' => cot_format_money($shop['shopping'][$row['page_id']]['total'], $shopdefcurr),
				));
			}

			///
			$orderMailData = array(
				'payername' => $payername,
				'payeremail' => $payeremail,
				'payeraddr' => $payeraddress,
				'payerphone' => $payerphone,
				'payerother' => $payerother,
				'orderinfo' => $ordermailinfo,
				'total' => cot_format_money($shop['total'],$shopdefcurr),
				'payment' => $payment['odp_title'],
				'delivery' => $delivery['odd_title'],
				'delivery_count' => cot_format_money($delivery['oddp_price'],$shopdefcurr),
				'id' => $orderid,
				'link' => $cfg['mainurl'].'/'.cot_url('admin', 'm=tools&p=shop&id='.$orderid, '', true),		
			);

			cot_mail(!empty($cfg['plugin']['shop']['email']) ? $cfg['plugin']['shop']['email'] : $cfg['adminemail'], $L['new_order_title'],
				cot_rc('new_order_mail', $orderMailData));

			cot_mail($payeremail, $L['user_order_title'], cot_rc('user_order_mail', $orderMailData));
			
			/* === Hook === */
			foreach (cot_getextplugins('shop.checkout.added') as $pl)
			{
			    include $pl;
			}
			/* ===== */
			unset($shop);
			unset($_SESSION['shop']);
			$_SESSION['shop']['currency'] = $shopcurr;
			$transfered = true;			
			if(cot_plugin_active($payment['odp_driver']))
			{
				require_once cot_incfile($payment['odp_driver'], 'plug');
				$make_payment = 'cot_shop_make_payment_'.$payment['odp_driver'];
				if(function_exists($make_payment))
				$make_payment($orderid);
			}
		}
		///
		unset($shop);
		unset($_SESSION['shop']);
		$_SESSION['shop']['currency'] = $shopcurr;
		$transfered = true;
	}
}

cot_display_messages($t);

if ($transfered == false)
{
	if ($usr['id'] > 0)
	{
		$payername = (!empty($payername) || empty($shopcfg['payernamefld'])) ? $payername : $usr['profile'][$shopcfg['payernamefld']];
		$payerphone = (!empty($payerphone) || empty($shopcfg['payerphonefld'])) ? $payerphone : $usr['profile'][$shopcfg['payerphonefld']];
		$payeraddress = (!empty($payeraddress) || empty($shopcfg['payeraddressfld'])) ? $payeraddress : $usr['profile'][$shopcfg['payeraddressfld']];
		$payeremail = (!empty($payeremail)) ? $payeremail : $usr['profile']['user_email'];
	}
	$i = 0;
	$payments = $db->query("SELECT * FROM $db_orders_payments ORDER BY odp_id ASC")->fetchAll();
	foreach ($payments as $row)
	{
		$i++;
		$t->assign(array(
			'SHOP_PAYMENT_ID' => $row['odp_id'],
			'SHOP_PAYMENT_RADIO' => cot_radiobox($i == 1, 'payerpayment', array($row['odp_id']), array($row['odp_title'])),
			'FORM_PAYMENT_DESC' => cot_parse($row['odp_desc'], true, 'html'),
		));
		$t->parse('MAIN.FORM.PAYMENTS.ROWS');
	}
	if(!count($payments))
	{
		$t->parse('MAIN.FORM.PAYMENTS.NOROWS');
	}
	$t->parse('MAIN.FORM.PAYMENTS');
	
	$i = 0;
	$deliveries = $db->query("SELECT d.*, dp.* FROM $db_orders_delivery AS d
		LEFT JOIN (SELECT * FROM $db_orders_delivery_prices WHERE (oddp_mintotal <= ".(float)$shop['total'].") ORDER BY oddp_price ASC LIMIT 1) 
			AS dp ON dp.oddp_oddid = d.odd_id
		ORDER BY odd_title ASC")->fetchAll();
	
	foreach ($deliveries as $row)
	{
		$i++;
		$price = (float)$row['oddp_price'];
		
		if((float)$row['oddp_type'] > 0 && $price > 0)
		{
			$price = (float)$shop['total'] * $price / 100;
			$price = round($price, 0);
		}
		$t->assign(array(
			'SHOP_DELIVERY_ID' => $row['odd_id'],
			'SHOP_DELIVERY_RADIO' => cot_radiobox($i == 1, 'payerdelivery', array($row['odd_id']), array($row['odd_title'])),
			'SHOP_DELIVERY_DESC' => cot_parse($row['odd_desc'], true, 'html'),
			'SHOP_DELIVERY_PRICE_NUM' => $price,
			'SHOP_DELIVERY_PRICE' => cot_format_money($price, $shopcurr),
			'SHOP_DELIVERY_PRICE_DEF' => cot_format_money($price, $shopdefcurr),
			'SHOP_DELIVERY_PERCENT' => ((float)$row['oddp_type'] > 0 && $price > 0) ? 0 : (float)$row['oddp_type'],
		));
		$t->parse('MAIN.FORM.DELIVERIES.ROWS');
	}
	if(!count($deliveries))
	{
		$t->parse('MAIN.FORM.DELIVERIES.NOROWS');
	}	

	$t->parse('MAIN.FORM.DELIVERIES');
	
	$t->assign(array(
		'SHOP_ORDER_URL' => cot_url('plug', 'e=shop&m=checkout&a=add'),
		'SHOP_ORDER_PAYERNAME' => cot_inputbox('text', 'payername', $payername),
		'SHOP_ORDER_PAYERPHONE' => cot_inputbox('text', 'payerphone', $payerphone),
		'SHOP_ORDER_PAYERADDRESS' => cot_inputbox('text', 'payeraddress', $payeraddress),
		'SHOP_ORDER_PAYEREMAIL' => cot_inputbox('text', 'payeremail', $payeremail),
		'SHOP_ORDER_PAYEROTHER' => cot_inputbox('text', 'payerother', $payerother),
		'SHOP_ORDER_PAYERDESC' => cot_inputbox('text', 'payerdesc', $payerdesc),
		'SHOP_ORDER_PAYERTOTAL' => cot_format_money($shop['total'], $shopcurr),
		'SHOP_ORDER_PAYERTOTAL_DEF' => cot_format_money($shop['total'], $shopdefcurr)
	));
	
	/* === Hook === */
	foreach (cot_getextplugins('shop.checkout.tags') as $pl)
	{
	    include $pl;
	}
	/* ===== */	
	
	$t->parse('MAIN.FORM');
}
elseif ($transfered && $cfg['plugin']['shop']['testmode'])
{
	$t->parse('MAIN.TESTMODE');
}
else
{
	$t->parse('MAIN.SUCCESS');
}
?>