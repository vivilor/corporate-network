<?php
    try
    {
        $result = $pdo->query($query_text);
    }
    catch (PDOException $e)
    {
        $output = "Ошибка при извлечении данных".$e->getMessage();
        include 'error.php';
        exit();
    }
    $report1 = $result->fetchAll();
    $rownumb1 = $result->rowCount();
?>