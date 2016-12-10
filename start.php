<?php

include_once 'php/packer.php';

$includes = use_stylesheets(array(
    "css/elements.css",
    "css/common.css",
    "css/fonts.css"
));

$includes .= use_scripts(array(
    'js/pop-up.js'
));

$head = pack_document_head("СУБД Cloudware", $includes);

$body = pack_document_body(
    pack_in_paired_tag(
        "div",
        array(
            "class" => "center",
        ),
        pack_in_paired_tag(
            "div",
            array(
                "id" => "start",
                "class" => "logo"
            ),
            ""
        ) .
        pack_in_paired_tag(
            "a",
            array(
                "href" => "auth.php",
                "class" => "btn inline relative segoe-ui small"
            ),
            "Войти"
        )
    )
);

echo pack_document($head . $body);
?>

