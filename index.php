<?php

if($_GET['quit'] == 1)
{
    if(isset($_SESSION))
    {
        foreach (array_keys($_SESSION) as $key):
            unset($_SESSION[$key]);
        endforeach;
    }
    require 'auth.php';
    exit();

}
else
{
	require 'start.php';
}
?>

