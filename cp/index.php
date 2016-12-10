<?php
/*
if( ($_GET['back'] == 1) || !$_GET)
{
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <link href="/css/fonts.css" type="text/css" rel="stylesheet">
        <link href="/css/common.css" type="text/css" rel="stylesheet">
        <link href="/css/elements.css" type="text/css" rel="stylesheet">
        <title>Панель управления</title>
    </head>
    <body>
            <?
            require '../php/menu.php';
            echo pack_button_bar(1);
            echo pack_tile_bar();
            ?>
    </body>
    </html>
*/

session_start();
$content = "";

if (!isset($_SESSION['current_user']))
{
    header("Location:../auth.php");
}

require_once '../php/form.php';
require_once '../php/menu.php';
require_once '../php/page.php';
require_once '../php/db/db.php';

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
$body = pack_document_body($menu . $pop_up . $tiles);

echo pack_document($head . $body);

?>
