<?php

$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/php/engine/form.php';
require_once $root . '/php/engine/menu.php';
require_once $root . '/php/engine/page.php';
require_once $root . '/php/engine/order.php';
require_once $root . '/php/engine/calendar.php';
require_once $root . '/php/db/db.php';

$includes = use_stylesheets(array(
    "/css/elements.css",
    "/css/common.css",
    "/css/fonts.css"
));

$includes .= use_scripts(array(
    "/js/jquery-3.1.1.min.js",
    "/js/db/order.js",
    "/js/pop-up.js"
));

$title = 'Заказы';

$head = pack_document_head($title, $includes);

$menu = pack_button_bar(0);

$msg = pack_msg("", "pop-up-error");

$side_bar = pack_side_bar(array(
    "add-order",
    "view-order",
    ),
    "relative leftfloat"
);

$page_title = pack_content_title($title);

/* Content */

// TODO: add session storage check for previously selected user

$order_view_content = pack_order_client();

$order_view = pack_order_view(
    $order_view_content
);

$receipt_view = pack_receipt_view(
    pack_receipt_content() .
    pack_receipt_positions_header()
);

$content = pack_order_entities(
    $order_view . $receipt_view
) . pack_order_search_entities();

/* End content */

$page_content = pack_page_content(
    $menu .
    $msg .
    $page_title .
    $side_bar .
    $content
);

$body = pack_document_body($page_content);

echo pack_document($head . $body);
?>

