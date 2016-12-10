<?php

require_once $doc_root . '/php/form.php';
require_once $doc_root . '/php/menu.php';
require_once $doc_root . '/php/page.php';
require_once $doc_root . '/php/db/db.php';

$includes = use_stylesheets(array(
    "/css/elements.css",
    "/css/common.css",
    "/css/fonts.css"
));

$title = 'Отчеты';

$head = pack_document_head($title, $includes);

$menu = pack_button_bar(0);

$side_bar = pack_side_bar(array(
    "add-report",
    "view"
));

$content = pack_form("index.php", "get",
    pack_text("Отчеты") .
    pack_text_field("rep_year", "Год") .
    pack_text_field("rep_month", "Month") .
    pack_form_btns(0)
    );
$body = pack_document_body($menu . $side_bar . $content);

echo pack_document($head . $body);
?>