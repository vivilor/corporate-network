<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/php/engine/form.php';
require_once $root . '/php/engine/menu.php';
require_once $root . '/php/engine/page.php';
require_once $root . '/php/db/db.php';

$includes = use_stylesheets(array(
    "/css/elements.css",
    "/css/common.css",
    "/css/fonts.css"
    ));
    
$includes .= use_scripts(array(
    '/js/jquery-3.1.1.min.js',
    '/js/pop-up.js'
    ));

$pop_up_msg = "Ваши привилегии";

if($_SESSION['current_user']['username'] == 'admin')
{
    $pop_up_msg .= " неограничены!";
    $pop_up_type = "pop-up-warning";
}
else
{
    $pop_up_msg .= " ограничены администратором!";
    $pop_up_type = "pop-up-info";
}

$head = pack_document_head("Панель управления", $includes);
$pop_up = pack_msg($pop_up_msg, $pop_up_type);
$menu = pack_button_bar(0);
$tiles = pack_tile_bar();
$body = pack_document_body(pack_page_content($menu . $pop_up . $tiles));

echo pack_document($head . $body);

?>