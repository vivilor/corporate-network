<?php

if( ($_GET['back'] == 1) || !$_GET)
{
    require 'usercp.html';
}

if($_GET['report'] == 1) {
    require 'report_view.php';
}

if($_GET['report'] == 2) {
    require 'report_create.php';
}


