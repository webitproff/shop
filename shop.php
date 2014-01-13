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

require(sed_langfile('shop'));

switch($m)
	{
	case 'checkout':
	require_once($cfg['plugins_dir'].'/shop/inc/shop.checkout.php');
	break;

	case 'tools':
	require_once($cfg['plugins_dir'].'/shop/inc/shop.tools.php');
	break;

	default:
	require_once($cfg['plugins_dir'].'/shop/inc/shop.cart.php');
	break;
	}
	
?>