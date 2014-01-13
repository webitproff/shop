<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=standalone
File=shop
Hooks=standalone
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

$mskin = sed_skinfile(array('shop', 'checkout'), true);
$t = new XTemplate($mskin);

$transfered = false;
$error_string .= ( $shop['count'] < 1) ? $L['error_emptycart'] . '<br />' : '';

if ($a == 'add')
{
	$payername = sed_import('payername', 'P', 'TXT');
	$payerphone = sed_import('payerphone', 'P', 'TXT');
	$payeraddress = sed_import('payeraddress', 'P', 'TXT');
	$payeremail = sed_import('payeremail', 'P', 'TXT');
	$payerother = sed_import('payerother', 'P', 'TXT');

	$sql = sed_sql_query("SELECT banlist_reason, banlist_email FROM $db_banlist WHERE banlist_email!=''");

	while ($row = sed_sql_fetcharray($sql))
	{
		if (mb_strpos($row['banlist_email'], $payeraddress) !== false)
		{
			$bannedreason = $row['banlist_reason'];
		}
	}
	$error_string .= ( !empty($bannedreason)) ? $L['aut_emailbanned'] . $bannedreason . "<br />" : '';
	$error_string .= ( empty($payername)) ? $L['error_emptyname'] . '<br />' : '';
	$error_string .= ( empty($payerphone)) ? $L['error_emptyphone'] . '<br />' : '';
	$error_string .= ( empty($payeraddress)) ? $L['error_emptyaddress'] . '<br />' : '';
	$error_string .= ( empty($payeremail) || !preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$#i', $payeremail)) ? $L['aut_emailtooshort'] . '<br />' : '';

	if (empty($error_string))
	{
		if ($usr['id'] < 1 && !$cfg['plugin']['shop']['testmode'])
		{
			$sql = sed_sql_query("SELECT * FROM $db_users WHERE user_email = '" . sed_sql_prep($payeremail) . "' LIMIT 1");
			$row = sed_sql_fetchassoc($sql);
			if ($row['user_id'] > 0)
			{
				$userid = $row['user_id'];
			}
			else
			{
				$defgroup = ($cfg['regnoactivation']) ? 4 : 2;
				$rusername = $payeremail;
				$rpassword1 = rand(10001, 99999);
				$mdpass = md5($rpassword1);

				$validationkey = md5(microtime());
				sed_shield_update(20, "Registration");

				$ssql = "INSERT into $db_users
							(user_name,	user_password, user_maingrp, user_email, user_hideemail, user_pmnotify, user_skin,
							user_theme, user_lang, user_regdate, user_logcount, user_lostpass, user_birthdate, user_lastip)
							VALUES
							('" . sed_sql_prep($payeremail) . "', '$mdpass', " . (int)$defgroup . ", '" . sed_sql_prep($payeremail) . "', 1, 0, '" . $cfg['defaultskin'] . "',
							'" . $cfg['defaulttheme'] . "', '" . $cfg['defaultlang'] . "', " . (int)$sys['now_offset'] . ", 0, '$validationkey', '0000-00-00', '" . $usr['ip'] . "')";
				$sql1 = sed_sql_query($ssql);
				$userid = sed_sql_insertid();
				$sql1 = sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$defgroup . ")");

				/* === Hook for the plugins === */
				$extp = sed_getextplugins('users.register.add.done');
				if (is_array($extp))
				{
					foreach ($extp as $pl)
					{
						include_once($cfg['plugins_dir'] . '/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
					}
				}

				/* ===== */

				if ($cfg['regrequireadmin'])
				{
					$rsubject = $cfg['maintitle'] . " - " . $L['aut_regrequesttitle'];
					$rbody = sprintf($L['aut_regrequest'], $rusername, $rpassword1);
					$rbody .= "\n\n" . $L['aut_contactadmin'];
					sed_mail($payeraddress, $rsubject, $rbody);

					$rsubject = $cfg['maintitle'] . " - " . $L['aut_regreqnoticetitle'];
					$rinactive = $cfg['mainurl'] . '/' . sed_url('users', 'gm=2&s=regdate&w=desc', '', true);
					$rbody = sprintf($L['aut_regreqnotice'], $rusername, $rinactive);
					sed_mail($cfg['adminemail'], $rsubject, $rbody);
				}
				else
				{
					$rsubject = $cfg['maintitle'] . " - " . $L['Registration'];
					$ractivate = $cfg['mainurl'] . '/' . sed_url('users', 'm=register&a=validate&v=' . $validationkey . '&y=1', '', true);
					$rdeactivate = $cfg['mainurl'] . '/' . sed_url('users', 'm=register&a=validate&v=' . $validationkey . '&y=0', '', true);
					$rbody = sprintf($L['aut_emailreg'], $rusername, $rpassword1, $ractivate, $rdeactivate);
					$rbody .= "\n\n" . $L['aut_contactadmin'];
					sed_mail($payeraddress, $rsubject, $rbody);
				}
			}
		}
		else
		{
			$userid = $usr['id'];
		}
		if (!$cfg['plugin']['shop']['testmode'])
		{
			$ssql = "INSERT into $db_orders
				(order_userid,	order_date, order_total, order_apply, order_payed, order_payername,
				order_payerphone, order_payeremail, order_payeraddress, order_payerother, order_currency)
				VALUES
				('" . (int)$userid . "', " . (int)$sys['now_offset'] . ", '" . (float)$shop['total'] . "', 0, 0, '" . sed_sql_prep($payername) . "',
				'" . sed_sql_prep($payerphone) . "', '" . sed_sql_prep($payeremail) . "', '" . sed_sql_prep($payeraddress) . "', '" . sed_sql_prep($payerother) . "', '" . sed_sql_prep($shopcurr) . "')";
			$sql1 = sed_sql_query($ssql);
			$orderid = sed_sql_insertid();
			foreach ($shop['shopping'] as $key => $row)
			{
				$shoparray[] = (int)$key;
			}
			$jj = 0;
			$sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id IN ('" . implode("','", $shoparray) . "')");

			while ($row = sed_sql_fetchassoc($sql))
			{
				$ssql = "INSERT into $db_orders_desc
				(od_orderid, od_pageid, od_title, od_price, od_count, od_total)
				VALUES
				('" . (int)$orderid . "', " . (int)$row['page_id'] . ", '" . sed_sql_prep($row['page_title']) . "',
				'" . (float)$shop['shopping'][$row['page_id']]['price'] . "', '" . (int)$shop['shopping'][$row['page_id']]['count'] . "',
				'" . (float)$shop['shopping'][$row['page_id']]['total'] . "')";
				$sql1 = sed_sql_query($ssql);
			}

			///
			$rsubject = $cfg['maintitle'] . " - " . $L['new_order_title'];
			$rlink = $cfg['mainurl'] . '/' . sed_url('admin', 'm=tools&p=shop&id=' . $orderid, '', true);
			$rbody = sprintf($L['new_order'], $payername, $shop['total'], $payeremail, $payeraddress, $payerphone, $payerother, $rlink);
			sed_mail(!empty($cfg['plugin']['shop']['email']) ? $cfg['plugin']['shop']['email'] : $cfg['adminemail'], $rsubject, $rbody);

			$rsubject = $cfg['maintitle'] . " - " . $L['user_order_title'];
			$rbody = sprintf($L['user_order'], $payername, $shop['total'], $payeremail, $payeraddress, $payerphone, $payerother);
			sed_mail($payeremail, $rsubject, $rbody);
		}
		///
		unset($shop);
		unset($_SESSION['shop']);
		$_SESSION['shop']['currency'] = $shopcurr;
		$transfered = true;
	}
}
if (!empty($error_string))
{
	$t->assign(array(
		'SHOP_ERROR' => $error_string
	));
	$t->parse('MAIN.ERROR');
}
if ($transfered == false)
{
	if ($usr['id'] > 0)
	{
		$payername = (!empty($payername) || empty($shopcfg['payernamefld'])) ? $payername : $usr['profile'][$shopcfg['payernamefld']];
		$payerphone = (!empty($payerphone) || empty($shopcfg['payerphonefld'])) ? $payerphone : $usr['profile'][$shopcfg['payerphonefld']];
		$payeraddress = (!empty($payeraddress) || empty($shopcfg['payeraddressfld'])) ? $payeraddress : $usr['profile'][$shopcfg['payeraddressfld']];
		$payeremail = (!empty($payeremail)) ? $payeremail : $usr['profile']['user_email'];
	}
	$t->assign(array(
		'SHOP_ORDER_URL' => sed_url('plug', 'e=shop&m=checkout&a=add'),
		'SHOP_ORDER_PAYERNAME' => '<input type="text" class="text" name="payername" value="' . htmlspecialchars($payername) . '" size="56" maxlength="255" />',
		'SHOP_ORDER_PAYERPHONE' => '<input type="text" class="text" name="payerphone" value="' . htmlspecialchars($payerphone) . '" size="56" maxlength="255" />',
		'SHOP_ORDER_PAYERADDRESS' => '<input type="text" class="text" name="payeraddress" value="' . htmlspecialchars($payeraddress) . '" size="56" maxlength="255" />',
		'SHOP_ORDER_PAYEREMAIL' => '<input type="text" class="text" name="payeremail" value="' . htmlspecialchars($payeremail) . '" size="56" maxlength="255" />',
		'SHOP_ORDER_PAYEROTHER' => '<textarea name="payerother" rows="24" cols="120">' . htmlspecialchars($payerother) . '</textarea>',
		'SHOP_ORDER_PAYERTOTAL' => sed_format_money($shop['total'], $shopcurr),
		'SHOP_ORDER_PAYERTOTAL_DEF' => sed_format_money($shop['total'], $shopdefcurr)
	));
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