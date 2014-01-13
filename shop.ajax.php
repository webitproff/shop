<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
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

cot_sendheaders();
echo $SHOPCART;
ob_end_flush();

?>