<?php

function pack_form($action, $method, $content)
{
    return pack_in_paired_tag(
        "form",
        array(
            "class" => "form center segoe-ui h14pt",
            "action" => $action,
            "method" => $method
        ),
        $content
    );
}


function pack_form_tip($tip_text, $style)
{
    return pack_in_paired_tag(
        "span",
        array("class" => "center"),
        $tip_text
    );
}


function pack_text_field($name, $placeholder, $classes, $label="",
    $pswd=0)
{
    return pack_in_paired_tag(
        "div",
        array("class" => "input-element " . $classes),
        $label != "" ?
            pack_in_paired_tag(
                "label",
                array(
                    "for" => $name,
                    "class" => "left relative segoe-ui h14pt"
                ),
                $label
            ) :
            "" . 
        pack_in_single_tag(
            "input",
            array(
                "id" => $name,
                "name" => $name,
                "type" => ($pswd ? "password" : "text"),
                "class" => "input-text relative clear segoe-ui h14pt",
                "placeholder" => $placeholder
            )
        )
    );
}


function pack_form_btns()
{
    return pack_in_paired_tag(
        "div",
        array( "class" => "form-btns relative center", ),
        pack_in_single_tag(
            "input",
            array(
                "type" => "reset",
                "class" => "btn segoe-ui h14pt relative",
                "value" => "Очистить"
            )
        ) .
        pack_in_single_tag(
            "input",
            array(
                "type" => "submit",
                "class" => "btn segoe-ui h14pt relative leftfloat",
                "value" => "Продолжить"
            )
        )
    );
}
?>