<?php
include_once 'php/form.php';
include_once 'php/menu.php';
include_once 'php/page.php';

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

$head = pack_document_head("Авторизация", $includes);

$menu = pack_button_bar(1);

$error_msg = isset($pdo_error) ? pack_msg($pdo_error, "pop-up-error") : "";

$content = pack_form("auth.php", "post",
    pack_text("Авторизация") .
    //pack_upper_text("активна", 1) .
    pack_form_tip(
        "Используйте логин и пароль, выданные Вам администратором системы."
    ) .
    pack_text_field("usr-name", "Логин") .
    pack_text_field("usr-pswd", "Пароль", "", "", 1)  .
    pack_form_btns(1),
    " hidden form"
);

$body = pack_document_body($menu . $error_msg . $content);

echo pack_document($head . $body);

?>