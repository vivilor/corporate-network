<?php
if(isset($_GET['query_text'])) $query_text = $_GET['query_text'];
if(isset($_GET['username']))   $username = $_GET['username'];
if(isset($query_text) &&
   isset($username))
try
{
    /* Type: PDOStatement */
    $pdo = $_SESSION['PDO_' . $username];

    $query_buffer = $pdo->query($query_text);

    $query_result = $query_buffer->fetchAll();
    $result_rows = $query_buffer->rowCount();   
}
catch (PDOException $e)
{
    $output = "Ошибка при извлечении данных".$e->getMessage();
    include 'error.php';
    exit();
}

?>