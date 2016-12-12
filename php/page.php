<?php
include_once 'packer.php';

function pack_page_content($content)
{
    return pack_in_paired_tag(
        "div",
        array(
            "class" => "page-content"
        ),
        $content
    );
}


function pack_msg($content, $class="", $id="0")
{
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "srv-msg" . $id,
            "class" => "pop-up inline absolute segoe-ui small hidden " . $class
        ),
        pack_in_paired_tag(
            "div",
            array(
                "class" => "pop-up-icon inline relative v-center "
            ),
            ""
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "pop-up-msg" . $id,
                "class" => "pop-up-msg inline relative segoe-ui small "
            ),
            $content
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "id" => "pop-up-btn" . $id,
                "class" => "pop-up-btn absolute "
            ),
            ""
        )
    );
}