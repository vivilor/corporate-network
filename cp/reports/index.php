<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Создание отчета</title>
    <link href='/css/elements.css' type='text/css' rel='stylesheet'>
    <link href='/css/common.css' type='text/css' rel='stylesheet'>
    <link href='/css/fonts.css' type='text/css' rel='stylesheet'>
</head>
<body>
    <?php
    if (!isset($_GET['r_year']) &&
        !isset($_GET['r_month']))
    {
        $db_user = 'root';
        $db_password = '';
        include 'db_connect.php';
        include 'menu.php';
        require 'input.html';
    }
    else
    {
        require 'start.html';
    }

    ?>

</body>
</html>