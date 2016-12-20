<?php

session_start();

if($_GET['exit'] == 1)
{
    if(isset($_SESSION))
    {
        foreach (array_keys($_SESSION) as $key):
            unset($_SESSION[$key]);
        endforeach;
        session_destroy();
    }
    require 'auth.php';
    exit();

}
else
{
	require 'start.php';
}
?>

