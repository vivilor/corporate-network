<?php
include_once 'packer.php';

function pack_form($action, $method, $content, $class="")
{
    return pack_in_paired_tag(
        "form",
        array(
            "class" => "form segoe-ui small " . $class,
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
function pack_form_tip($tip_text, $class="")
{
    return pack_in_paired_tag(
        "div",
        array("class" => "form-tip v-center relative medium" . $class),
        $tip_text
    );
}


function pack_text_field($name, $placeholder, $class="", $label="",
    $pswd=0)
{
    return pack_in_paired_tag(
        "div",
        array("class" => "input-element " . $class),
        $label != "" ?
            pack_in_paired_tag(
                "label",
                array(
                    "for" => $name,
                    "class" => "left relative segoe-ui small"
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
                "class" => "input-text relative clear segoe-ui small",
                "placeholder" => $placeholder,
                "required" => ""
            )
        )
    );
}


function pack_upper_text($content, $active, $id="", $class="")
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "upper-text segoe-ui tiny " .
                ($active ? " turned-on " : " turned-off ").
                $class
        ),
        $content
    );
}

function pack_text($content, $id="", $class="")
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "text segoe-ui big inline" . $class
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
                "class" => "btn segoe-ui small relative leftfloat",
                "value" => "Очистить"
            )
        ) .
        pack_in_single_tag(
            "input",
            array(
                "id" => "btn-submit",
                "type" => "submit",
                "class" => "btn segoe-ui small relative rightfloat",
                "value" => "Продолжить",
                ($disabled ? "disabled" : "") => ""
            )
        )
    );
}
?>