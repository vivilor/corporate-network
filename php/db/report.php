<?php
session_start();

require_once "safe_query.php";
require_once "db.php";
require_once "../calendar.php";
require_once "../table.php";




function get_report($pdo, $month, $year)
{
    $query_text = "
        SELECT reportServiceTitle,
               reportClientsQuantity,
               reportSummaryCost
        FROM `report`
        WHERE reportMonth = $month AND
              reportYear = $year";
    $result = execute_query($pdo, $query_text);

    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);
    return $result['PDOStatement']->fetchAll();
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


if( isset($_POST['year']) &&
    isset($_POST['month']) )
{
    $year = $_POST['year'];
    $month = $_POST['month'];
}
else
{
    echo json_encode(array(
        "error" => "AJAX Query failed. Recieved POST array is empty"
    ));
    exit();
}


$username = $_SESSION['current_user']['username'];
$password = $_SESSION['current_user']['password'];
$response = mysql_dbconnect($username, $password, 'cloudware');

if(isset($responce["PDOException"]))
{
    echo json_encode(array(
        "error" => $responce["PDOException"]
    ));
    exit();
}

$pdo = $response['PDO'];

$query_text = "
    SELECT DISTINCT reportMonth FROM `cloudware`.`report`
    WHERE reportYear = ". $year . ";";

$result = $pdo->query($query_text);
$raw_created_reports = $result->fetchAll();

$created_reports = array();

foreach($raw_created_reports as $created_report):
    $index = $created_report['reportMonth'];
    $created_reports[$index] = $index;
endforeach;

if(!isset($created_reports[$month]))
    make_report($pdo, $month, $year);

echo json_encode(array(
    "data" => pack_report_view(
            pack_table(
                get_report($pdo, $month, $year),
                array(
                "Назавание услуги",
                "Кол-во клиентов",
                "Суммарная рибыль"
                )
            )
        )
    )
);
exit();

?>
