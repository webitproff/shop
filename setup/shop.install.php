<?php

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2010 esclkm
 */
defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('page', 'module');
require_once cot_incfile('shop', 'plug', 'settings');
global $R, $L, $db_pages;

// Extrafields setup
$fieldname = str_replace('page_', '', $shopcfg['price']);
$L['page_'.$fieldname.'_title'] = (isset($L['page_'.$fieldname.'_title'])) ? $L['page_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_pages, $fieldname, 'double', $R['input_text'], '', '', 0, 'HTML', $L['page_'.$fieldname.'_title']);

$fieldname = str_replace('page_', '', $shopcfg['price_old']);
$L['page_'.$fieldname.'_title'] = (isset($L['page_'.$fieldname.'_title'])) ? $L['page_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_pages, $fieldname, 'double', $R['input_text'], '', '', 0, 'HTML', $L['page_'.$fieldname.'_title']);

$fieldname = str_replace('page_', '', $shopcfg['instock']);
$L['page_'.$fieldname.'_title'] = (isset($L['page_'.$fieldname.'_title'])) ? $L['page_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_pages, $fieldname, "checkbox", $R['input_checkbox'], '', '', 0, 'HTML', $L['page_'.$fieldname.'_title']);


$fieldname = str_replace('user_', '', $shopcfg['payernamefld']);
$L['user_'.$fieldname.'_title'] = (isset($L['user_'.$fieldname.'_title'])) ? $L['user_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_users, $fieldname, 'input', $R['input_text'], '', '', 0, 'HTML', $L['user_'.$fieldname.'_title']);

$fieldname = str_replace('user_', '', $shopcfg['payerphonefld']);
$L['user_'.$fieldname.'_title'] = (isset($L['user_'.$fieldname.'_title'])) ? $L['user_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_users, $fieldname, 'input', $R['input_text'], '', '', 0, 'HTML', $L['user_'.$fieldname.'_title']);

$fieldname = str_replace('user_', '', $shopcfg['payeraddressfld']);
$L['user_'.$fieldname.'_title'] = (isset($L['user_'.$fieldname.'_title'])) ? $L['user_'.$fieldname.'_title'] : '';
cot_extrafield_add($db_users, $fieldname, 'input', $R['input_text'], '', '', 0, 'HTML', $L['user_'.$fieldname.'_title']);

?>