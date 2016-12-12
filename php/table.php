<?php

include_once "packer.php";

function pack_table($content, $col_names=array(), $id="", $class="")
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
            "class" => "table-head light"
        ),
        $table_col_names
    );

    foreach($content as $row):
        for($i = 0; $i < count($col_names); $i++)
        {
            $table_row_cells .= pack_in_paired_tag(
                "td",
                array(
                    "class" => "table-cell"
                ),
                $row[$i]
            );
        }
        $table_rows .= pack_in_paired_tag(
            "tr",
            array(
                "class" => "table-row"
            ),
            $table_row_cells
        );
        $table_row_cells = "";
    endforeach;

    return pack_in_paired_tag(
        "table",
        array(
            "id" => $id,
            "class" => "table segoe-ui small" . $class
        ),
        $table_head . $table_rows
    );
}

?>