<?php
include_once 'packer.php';
function pack_form($action, $method, $class, $content)
{
    return pack_in_paired_tag(
        "form",
        array(
            "class" => "form segoe-ui h14pt " . $class,
            "action" => $action,
            "method" => $method
        ),
        $content
    );
}
/*
function pack_form_row($content, $class)
{
    return pack_in_paired_tag(
        "div",
        array( "class" => "form-row v-center relative" . $class ),
        $content
    );
}
*/
function pack_form_tip($tip_text, $class)
{
    return pack_in_paired_tag(
        "div",
        array("class" => "form-tip v-center relative" . $class),
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
                "placeholder" => $placeholder,
                "required" => ""
            )
        )
    );
}


function pack_upper_text($id, $class, $content, $active)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "upper-text relative segoe-ui h10pt inline v-top " .
                ($active ? " turned-on" : " turned-off").
                $class
        ),
        $content
    );
}

function pack_text($id, $class, $content)
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "text segoe-ui h16pt inline" . $class
        ),
        $content
    );
}
function pack_form_btns($disabled)
{
    return pack_in_paired_tag(
        "div",
        array( "class" => "form-row t-margin relative center", ),
        pack_in_single_tag(
            "input",
            array(
                "id" => "btn-reset",
                "type" => "reset",
                "class" => "btn segoe-ui h14pt relative leftfloat",
                "value" => "Очистить"
            )
        ) .
        pack_in_single_tag(
            "input",
            array(
                "id" => "btn-submit",
                "type" => "submit",
                "class" => "btn segoe-ui h14pt relative rightfloat",
                "value" => "Продолжить",
                ($disabled ? "disabled" : "") => ""
            )
        )
    );
}
?>