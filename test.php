<?php

function print_array($array)
{
    foreach(array_keys($array) as $key):
        print_r($key . " : ");
        print_r($array[$key]);
        echo '<br>';
    endforeach;
    echo '<br><br><br>';
}

function array_shift_(&$array, $index)
{
    print_r('At start ' . count($array) . '<br>');
    $array_len = count($array);
    for($i = $index; $i < ($array_len - 1); $i++)
    {
        $array[$i] = $array[$i + 1];
    }
    unset($array[$array_len - 1]);
    print_r('At finish ' . count($array) . '<br>');
}

$data = array(array("LIMIT C"),
              array("Маршрутизатор", "2", "4"),
              array("Коммутатор B", "1", "9"),
              array("LIMIT A"),
              array("Маршрутизатор", "2", "4"),
              array("Коммутатор G", "1", "9"),
              array("LIMIT D"));
print_array($data);


$a1 = array("992" => "10", "99" => "11", "994" => "12");
$a2 = array();

function append_to($src, &$dest)
{
    foreach(array_keys($src) as $key):
        if(strlen($key) > 2)
            $dest[$key] = $src[$key];
    endforeach;
}

array_shift_($data, 2);

print_array($data);

$data[count($data)] = array("TV B");

print_array($data);


?>