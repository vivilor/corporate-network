<?php

include_once "packer.php";

function pack_table( $content, $col_names=array(),$class="")
{
    $table_rows = "";
    $table_row_cells = "";
    $table_col_names = "";
    foreach($col_names as $col_name):
        $table_col_names .= pack_in_paired_tag(
            "th",
            array(
                "class" => "table-col-name"
            ),
            $col_name
        );
    endforeach;
    $table_head = pack_in_paired_tag(
        "thead",
        array(
            "class" => "table-head"
        ),
        ""
    );
    foreach($content as $row):
        foreach($row as $cell):
            $table_row_cells .= pack_in_paired_tag(
                "td",
                array(
                    "class" => "table-cell"
                ),
                $content[$row][$cell]
            );
        endforeach;
        $table_rows .= pack_in_paired_tag(
            "tr",
            array(
                "class" => "table-row"
            ),
            ""
        );
    endforeach;
    return pack_in_paired_tag(
        "table",
        array(
            "class" => "table"
        ),
        $table_head . $table_rows
    );
}


?>