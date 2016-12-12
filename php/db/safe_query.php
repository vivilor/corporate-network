<?php

function execute_query($pdo, $query_text)
{
    try
    {
        $result = $pdo->query($query_text);
    }
    catch(PDOException $e)
    {
        $error_msg = $e->getMessage();
    }
    return array(
        'PDOException' => $error_msg,
        'PDOStatement' => $result
    );
}

?>