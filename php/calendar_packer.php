<?php

session_start();

require_once 'calendar.php';
require_once 'db/db.php';
require_once 'db/safe_query.php';
require_once 'packer.php';

$quartals = array("I","II","III","IV");

if(isset($_POST['year']))
{
    $year = $_POST['year'];
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
    SELECT DISTINCT MONTH(clientServicingStart) FROM `cloudware`.`client`
    WHERE YEAR(clientServicingStart) = " . $year . ";";

$result = $pdo->query($query_text);
$raw_months = $result->fetchAll();
$months = array();
foreach($raw_months as $month):
    $index = $month['MONTH(clientServicingStart)'];
    $months[$index] = $index;
endforeach;


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


$i = 0;
$columns = array();
$column = "";

foreach(array_keys($months_list) as $cur_month):

    global $months_list;
    $is_created = 0;
    if(isset($months[$cur_month]))
    {
        if(isset($created_reports[$cur_month]))
            $is_created = 1;
        $column .= pack_cell($cur_month, $is_created);
    }
    else
        $column .= pack_in_paired_tag(
            "div",
            array(
                "class" => "clndr-cell"
            ),
            ""
        );
    $i++;
    if(!($i % 3))
    {
        $index = $i / 3;
        array_push(
            $columns,
            pack_column($column, $quartals[((integer) $index) - 1])
        );
        $column = "";
    }
endforeach;


$calendar = pack_calendar($columns);

echo json_encode(array("data" => $calendar));
exit();
?>