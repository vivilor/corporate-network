<?php
require_once "safe_query.php";

function get_report($pdo, $month, $year)
{
    $query_text = "
        SELECT reportServiceTitle,
               reportClientsQuantity,
               reportSummaryCost
        FROM report
        WHERE reportMonth = $month AND
              reportYear = $year";
    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);
    return $result->fetchAll();
}


function make_report($pdo, $month, $year)
{
    $query_text = "
        CALL `create_report`($month, $year)";
    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);
    return 1;
}
?>

