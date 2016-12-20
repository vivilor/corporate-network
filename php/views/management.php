<?php

$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/php/engine/form.php';
require_once $root . '/php/engine/menu.php';
require_once $root . '/php/engine/page.php';
require_once $root . '/php/engine/calendar.php';
require_once $root . '/php/db/db.php';

/* Stylesheets */

$includes = use_stylesheets(array(
    "/css/elements.css",
    "/css/common.css",
    "/css/fonts.css"
));

/* Scripts */

$includes .= use_scripts(array(
    "/js/jquery-3.1.1.min.js",
    "/js/pop-up.js"
));

$title = 'Управление и менеджмент';
$head = pack_document_head($title, $includes);

/* Controls */

$menu           = pack_button_bar(0);
$error_msg      = pack_msg("", "pop-up-error");

$page_title     = pack_content_title($title);

$content = "";

$page_content   = pack_page_content(
    $menu .
    $error_msg .
    $page_title .
    $content
);
$body           = pack_document_body($page_content);

echo pack_document($head . $body);
?>