<?php
    try
    {
        $pdo=new PDO ('mysql:host=localhost;dbname=cloudware', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET NAMES "utf8"');
    }
    catch (PDOException $e)
    {
        $output = 'Невозможно подключиться к серверу БД. <br>  '. $e->getMessage();
        include 'error.php';
        exit();
    }
?>
