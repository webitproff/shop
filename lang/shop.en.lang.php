<?php

/**
 * Shop Plugin for Cotonti CMF (English Localization)
 *
 * @version 3.00
 * @author esclkm
 * @copyright (c) 2008-2010 esclkm
 */

defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */

$L['cfg_email'] = array('E-mail for notifications','(leave empty to use the system E-mail)');
$L['cfg_cat'] = array('Shop category code');
$L['cfg_conversion'] = array('Exchange rates');
$L['cfg_testmode'] = array('Testmode','(turn off order placement)');
$L['cfg_noprice'] = array('Работать в режиме каталога', 'У товаров не обязательны цены - обязательны описания');

/**
 * 000 separator
 */

$cfg['plugin']['shop']['thousands'] = ',';

/**
 * Navigation
 */

$L['shop_catalog'] = "Catalog";
$L['shop_catalog_title'] = "Open shop catalog";
$L['shop_special'] = "Special offers";
$L['shop_special_title'] = "See all special offers";
$L['shop_brands'] = "Brands";
$L['shop_brands_title'] = "View all brands";
$L['shop_faq'] = "FAQ";
$L['shop_faq_title'] = "Frequently asked questions";
$L['shop_contact'] = "Contact";
$L['shop_contact_title'] = "Send us a message";
$L['shop_rss'] = "RSS";
$L['shop_rss_title'] = "RSS subscription";

/**
 * Globals
 */

$L['shop_addtocart'] = "Add to cart";
$L['shop_addtowishlist'] = "Add to wishlist";
$L['shop_byr'] = "USD";
$L['shop_discount'] = "Discount";
$L['shop_goods_total'] = "for a total of";
$L['shop_latest'] = "New arrivals";
$L['shop_order'] = "Order";
$L['shop_popular'] = "Popular";
$L['shop_price'] = "Price";
$L['shop_price_old'] = "Price before discount";
$L['shop_product_info'] = "Item info";
$L['shop_product_images'] = "Images";
$L['shop_product_specs'] = "Specifications";
$L['shop_product_similar'] = "Similar items";

$Ls['shop_byr'] = array('dollars','dollar');
$Ls['shop_goods'] = array('items','item');

$L['shop_instock'] = "In stock";

/**
 * Brands
 */

$L['shop_brand'] = "Brand";
$L['shop_brands'] = "Brands";
$L['shop_brand_inshop'] = "in our store";
$L['shop_item'] = "Item";
$L['shop_items'] = "Items";
$L['shop_itemcat'] = "Catalog";
$L['shop_itemcats'] = "Catalogs";
$L['shop_nobrands'] = "No such brands -- sorry!";

/**
 * Cart
 */

$L['shop_back'] = "Return to the store";
$L['shop_cart_empty'] = "Your cart is empty";
$L['shop_checkout'] = "Make an order";
$L['shop_clear_cart'] = "Clear cart";
$L['shop_misc'] = "Misc";
$L['shop_qty'] = "Quantity";
$L['shop_total'] = "Total";
$L['shop_your_cart'] = "Your cart";

$L['shop_thanks'] = "Your request has been submitted successfully. Our managers will contact you shortly. Thanks for buying with us!";

$L['error_emptyaddress'] = "Address missing";
$L['error_emptycart'] = "Your cart is empty";
$L['error_emptyemail'] = "E-mail missing";
$L['error_emptyname'] = "Name missing";
$L['error_emptyphone'] = "Phone missing";

/**
 * Tools
 */

$L['shop_eshop'] = "E-Store";

$L['admin_autodel'] = array(1=>'Unconfirmed', 2=>'Unpaid');

$L['shop_filter_pending'] = "unconfirmed";
$L['shop_filter_unpaid'] = "unpaid";
$L['shop_orders_before'] = "orders placed before";

$L['shop_name'] = "Name";
$L['shop_phone'] = "Phone";
$L['shop_address'] = "Address";

$L['shop_order_state0'] = "Order not confirmed";
$L['shop_order_state1'] = "Order confirmed";
$L['shop_order_state2'] = "Order paid";

$L['shop_confirm'] = "Confirm";
$L['shop_confirmed'] = "Confirmed";
$L['shop_unconfirm'] = "Remove confirmation";

$L['shop_paid'] = "Paid";
$L['shop_unpaid'] = "Unpaid";

$L['shop_buyer_info'] = "Buyer Info";
$L['shop_order_info'] = "Order Info";

$L['shop_config'] = "E-Store settings";

/**
 * Messages
 */

$L['new_order_title'] = 'New order';
$L['new_order'] = '
New order submitted
Buyer: %1$s
Total: %2$s dollars
E-mail: %3$s
Address: %4$s
Phone: %5$s
Notes: %6$s
More: %7$s
';

$L['user_order_title'] = 'Your order';
$L['user_order'] = '
Your order has been submitted. Order info:
Buyer: %1$s
Total: %2$s
E-mail: %3$s
Address: %4$s
Phone: %5$s
Notes: %6$s.
Thanks for buying with us!
';

?>