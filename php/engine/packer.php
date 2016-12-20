<?php
function use_stylesheets($filenames)
{
    $blocks = "";
    foreach($filenames as $filename):
        $blocks .= ("<link href=\"" . $filename . "\" rel=\"stylesheet\" type=\"text/css\">");
    endforeach;
    return $blocks;
}


function use_scripts($scripts)
{
    $blocks = "";
    foreach($scripts as $script):
        $blocks .= ("<script src=\"". $script . "\"></script>");
    endforeach;
    return $blocks;
}


function pack_document_head($title, $includes)
{
    return "
        <head>
            <title>" . $title . "</title>
            <meta charset=\"utf-8\">" .
            $includes .
        "</head>";
}


function pack_document_body($content)
{
    return
        "<body>"
            . $content .
        "</body>";
}

function pack_document($content)
{
    return
        "<!DOCTYPE html>
        <html>"
            . $content .
        "</html>";
}


function pack_in_paired_tag($tag, $attributes, $content)
{
    $new_content = "<" . $tag . " ";
    foreach(array_keys($attributes) as $attr)
        $new_content.= ($attr . "=\"" . $attributes[$attr] . "\" ");
    $new_content .= (">" . $content . "</" . $tag . ">");
    return $new_content;
}


function pack_in_single_tag($tag, $attributes)
{
    $new_content = "<" . $tag . " ";
    foreach(array_keys($attributes) as $attr)
        $new_content.= ($attr . "=\"" . $attributes[$attr] . "\" ");
    $new_content .= ">";
    return $new_content;
}
?>

