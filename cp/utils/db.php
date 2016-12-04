<?php
function mysql_dbconnect($username, $password, $dbname)
{
    try
    {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=' . $dbname,
            $username,
            $password
        );
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $pdo->exec('SET NAMES "utf8"');
        if(isset($_SESSION['started']))
            $_SESSION['PDO_' . $username] = $pdo;
        return array(
            "PDO" => $pdo,
            "PDOException" => null
        );
    }
    catch (PDOException $e)
    {
        return array(
            "PDO" => null,
            "PDOException" => $e->getMessage()
        );
    }
}

?>
