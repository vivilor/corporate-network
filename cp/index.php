<?php

session_start();
session_id();

if (!isset($_SESSION['current_user']))
{
    header("Location:../auth.php?auth_needed=1");
}

$root = $_SERVER['DOCUMENT_ROOT'];

require $root . "/php/views/cp.php";

exit();

?>
