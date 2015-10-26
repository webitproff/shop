<?php

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2010 esclkm
 */
defined('COT_CODE') or die('Wrong URL');


$error = cot_import('error', 'G', 'TXT') ? true : false;
$cancel = cot_import('cancel', 'G', 'TXT') ? true : false;

if(!$error)
{
	unset($shop);
	unset($_SESSION['shop']);
	$_SESSION['shop']['currency'] = $shopcurr;
	$transfered = true;
}
$mskin = cot_tplfile(array('shop', 'completion'), 'plug');

$t = new XTemplate($mskin);

if($error)
{
	$t->parse('MAIN.ERROR');	
}
elseif($cancel)
{
	$t->parse('MAIN.CANCEL');
}
else
{
	$t->parse('MAIN.SUCCESS');
}