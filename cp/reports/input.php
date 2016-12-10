<?php

require_once $doc_root . '/php/form.php';
require_once $doc_root . '/php/menu.php';
require_once $doc_root . '/php/page.php';
require_once $doc_root . '/php/db/db.php';
require_once $doc_root . '/php/calendar.php';

$includes = use_stylesheets(array(
    "/css/elements.css",
    "/css/common.css",
    "/css/fonts.css"
));

$include .= use_scripts(array(
    "/js/jquery-3.1.1.min.js",
    "/js/report.js",
    "/js/pop-up.js"
));

$title = 'Отчеты';

$head = pack_document_head($title, $includes);



$menu = pack_button_bar(0);

$fetched_years = pack_year_choose();
$year_select = "";

if(isset($fetched_years['PDOException']))
{
    $error_msg = pack_msg($fetched_years['PDOException'], "pop-up-error");
}
else
{
    $year_select .= pack_select_field($fetched_years, "", "years");
}

$content = $year_select;


/*
$content = pack_in_paired_tag(
    "div",
    array(
        "style" => "position: absolute; top: 70px; left: 100px;"
    ),
    pack_cell("01", 1) . pack_cell("04", 0)
);
*/
/*pack_form("index.php", "get",
    pack_text("Отчеты") .
    pack_text_field("rep_year", "Год") .
    pack_text_field("rep_month", "Month") .
    pack_form_btns(0));*/
$body = pack_document_body($menu . $error_msg . $content);

echo pack_document($head . $body);
?>