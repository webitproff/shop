<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=ajax
File=shop.ajax
Hooks=ajax
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

sed_sendheaders();
echo $SHOPCART;
ob_end_flush();

?>