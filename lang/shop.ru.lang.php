<?php

/**
 * Shop Plugin for Cotonti CMF (Russian Localization)
 *
 * @version 2.00
 * @author Seditio.by
 * @copyright (c) 2008-2010 Seditio.by
 */

defined('SED_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */

$L['cfg_email'] = array('E-mail для уведомлений','(оставить пустым для использования системного E-mail\'а)');
$L['cfg_cat'] = array('Код категории магазина');
$L['cfg_conversion'] = array('Обменные курсы');
$L['cfg_testmode'] = array('Включить тестовый режим','(отключить размещение заказов)');

/**
 * Navigation
 */

$L['shop_catalog'] = "Каталог товаров";
$L['shop_catalog_title'] = "Открыть каталог товаров";
$L['shop_special'] = "Спецпредложения";
$L['shop_special_title'] = "Открыть все спецпредложения";
$L['shop_brands'] = "Бренды";
$L['shop_brands_title'] = "Просмотреть список производителей товаров";
$L['shop_faq'] = "ЧАВО";
$L['shop_faq_title'] = "Список ЧАсто задаваемых ВОпросов";
$L['shop_contact'] = "Контакты";
$L['shop_contact_title'] = "Связаться с нами";
$L['shop_rss'] = "RSS";
$L['shop_rss_title'] = "Подписаться на RSS-поток";

/**
 * Globals
 */

$L['shop_addtocart'] = "В корзину";
$L['shop_addtowishlist'] = "В любимые товары";
$L['shop_byr'] = "USD";
$L['shop_discount'] = "Скидка";
$L['shop_goods_total'] = "на сумму";
$L['shop_latest'] = "Новые поступления";
$L['shop_order'] = "Под заказ";
$L['shop_popular'] = "Популярные товары";
$L['shop_price'] = "Цена";
$L['shop_price_old'] = "Цена до скидки";
$L['shop_product_info'] = "Информация о товаре";
$L['shop_product_images'] = "Изображения товара";
$L['shop_product_specs'] = "Характеристики товара";
$L['shop_product_similar'] = "Похожие товары";

$Ls['shop_byr'] = array('доллар','доллара','долларов');
$Ls['shop_goods'] = array('товар','товара','товаров');

$L['shop_instock'] = "В наличии";

/**
 * Brands
 */

$L['shop_brand'] = "Бренд";
$L['shop_brands'] = "Бренды";
$L['shop_brand_inshop'] = "в нашем магазине";
$L['shop_item'] = "Товар";
$L['shop_items'] = "Товары";
$L['shop_itemcat'] = "Раздел товаров";
$L['shop_itemcats'] = "Разделы товаров";
$L['shop_nobrands'] = "Такие бренды отсутствуют";

/**
 * Cart
 */

$L['shop_back'] = "Назад в каталог товаров";
$L['shop_cart_empty'] = "Ваша корзина пуста";
$L['shop_checkout'] = "Оформить заказ";
$L['shop_clear_cart'] = "Очистить корзину";
$L['shop_misc'] = "Прочее";
$L['shop_qty'] = "Количество";
$L['shop_total'] = "Итог";
$L['shop_your_cart'] = "Ваша корзина";

$L['shop_thanks'] = "Ваша заявка передана к рассмотрению. Ближайшее время наши менеджеры свяжутся с вами! Спасибо за заказ.";

$L['error_emptyaddress'] = "Введите адрес";
$L['error_emptycart'] = "Ваша корзина пуста";
$L['error_emptyemail'] = "Введите E-mail";
$L['error_emptyname'] = "Введите имя";
$L['error_emptyphone'] = "Введите телефон";

/**
 * Tools
 */

$L['shop_eshop'] = "Интернет-магазин";

$L['admin_autodel'] = array(1=>'Неподтвержденные', 2=>'Неоплаченные');

$L['shop_filter_pending'] = "неподтвержденные";
$L['shop_filter_unpaid'] = "неоплаченные";
$L['shop_orders_before'] = "заказы, оформленные до";

$L['shop_name'] = "Ф.И.О.";
$L['shop_phone'] = "Телефон";
$L['shop_address'] = "Адрес";

$L['shop_order_state0'] = "Заказ не принят";
$L['shop_order_state1'] = "Заказ принят";
$L['shop_order_state2'] = "Заказ оплачен";

$L['shop_confirm'] = "Подтвердить";
$L['shop_confirmed'] = "Подтверждено";
$L['shop_unconfirm'] = "Снять отметку подтверждено";

$L['shop_paid'] = "Оплачено";
$L['shop_unpaid'] = "Снять отметку оплачено";

$L['shop_buyer_info'] = "Информация о покупателе";
$L['shop_order_info'] = "Информация о заказе";

$L['shop_config'] = "Настройки магазина";

/**
 * Messages
 */

$L['new_order_title'] = 'Новый заказ';
$L['new_order'] = '
Поступил новый заказ
Заказчик: %1$s
Сумма: %2$s долларов
E-mail: %3$s
Адрес: %4$s
Телефон: %5$s
Дополнительно: %6$s
Подробно: %7$s
';

$L['user_order_title'] = 'Ваш заказ';
$L['user_order'] = '
Ваш заказ принят к рассмотрению. Информация о заказе:
Заказчик: %1$s
Сумма: %2$s р.
E-mail: %3$s
Адрес: %4$s
Телефон: %5$s
Дополнительно: %6$s.
Спасибо за покупку!
';

?>