<?php
if(isset($_GET['username'])) $username = $_GET['username'];
if(isset($_GET['password'])) $password = $_GET['password'];
if(isset($_GET['dbname']))   $dbname = $_GET['dbname'];

if (isset($username) &&
    isset($password) &&
    isset($dbname))
{
    try
    {
        $pdo = new PDO ('mysql:host=localhost;dbname=' . $dbname,
                                                         $username,
                                                         $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
                           PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET NAMES "utf8"');
        $_SESSION['PDO_' . $username] = $pdo;
    }
    catch (PDOException $e)
    {
        if(isset($from_ajax) && $from_ajax == 1)
        {
            echo 'DB_ERROR';
        }
        $output = 'Невозможно подключиться к серверу БД. <br>  ' . $e->getMessage();
        include 'error.php';
        exit();
    }
}
else
{
    $output = 'Ошибка направления команд скрипту. <br>';
    include 'error.php';
    exit();
}
?>
