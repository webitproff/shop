<?php

/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=shop
Part=page
File=shop.page.tags
Hooks=page.tags
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

/**
 * Shop
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (c) 2008-2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL');

$t->assign(sed_generate_pagetags_currency($pag, 'PAGE_'));

?>