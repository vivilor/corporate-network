<?php
session_start();

function print_array($array)
{
    foreach(array_keys($array) as $key1):
        print_r('<b>' . $key1 . " : " . '</b></br>');
        foreach (array_keys($array[$key1]) as $key2):
            print_r('>>>' . $key2 . " : " . $array[$key1][$key2] . '<br>');
        endforeach;
        echo '<br>';
    endforeach;
    echo '<br><br><br>';
}
print_array($_SESSION['order_positions']);/*
print_array($_SESSION['prices_storage']);
*/


?>