<?php

/* ====================
  [BEGIN_COT_EXT]
  Hooks=standalone
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

require_once cot_langfile('shop', 'plug');
require_once cot_incfile('page', 'module');
require_once cot_incfile('forms');

if(!in_array($m, array('cart', 'checkout', 'tools', 'completion')))
{
	$m = 'cart';
}
require_once cot_incfile('shop', 'plug', $m);
