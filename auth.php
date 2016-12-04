<?php
if($_GET['success'] == 1)
{
    require 'auth_success.html';
}
else
{
    $exit_btn_only = 1;
    require 'auth_form.php';
}
?>