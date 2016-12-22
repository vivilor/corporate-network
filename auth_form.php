<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/php/engine/form.php';
include_once $root . '/php/engine/menu.php';
include_once $root . '/php/engine/page.php';

$includes = use_stylesheets(array(
    "css/elements.css",
    "css/common.css",
    "css/fonts.css"
));

$includes .= use_scripts(array(
    'js/jquery-3.1.1.min.js',
    'js/db/validation.js',
    'js/pop-up.js'
));
$title = "Авторизация";

$page_title = pack_content_title($title);

$head = pack_document_head($title, $includes);

$menu = pack_button_bar(1);

$msg = "";
if(isset($warning)) $msg = pack_msg($warning, "pop-up-warning", '0');
if(isset($pdo_error)) $msg =  pack_msg($pdo_error, "pop-up-error", '0');

$content = pack_form("auth.php", "post",
    pack_form_tip(
        "Используйте логин и пароль, выданные Вам администратором системы."
    ) .
    pack_text_field("usr-name", "Логин", "v-space") .
    pack_text_field("usr-pswd", "Пароль", "v-space", "", 1)  .
    pack_form_btns(1),
    " hidden form"
);

$body = pack_document_body(
    pack_page_content(
        $menu . $msg . $page_title . $content
    )
);

echo pack_document($head . $body);

?>