<?php

foreach(array_keys($_GET) as $key):
    echo '<div style="border: 5px solid #FFFF00; color: #0000FF">' .
        $key . '</div>';
endforeach;
?>