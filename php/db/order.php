<?php


session_start();


require_once "../engine/order.php";
require_once "../engine/table.php";
require_once "safe_query.php";
require_once "db.php";


function append_to($src, &$dest)
{
    foreach(array_keys($src) as $key):
        if(strlen($key) > 2)
            $dest[$key] = $src[$key];
    endforeach;
}


function get_order_info($pdo, $id)
{
    $order_info = array();

    /* Order info */

    $query_text = "
        SELECT `orderID`,
               `orderType`,
               `orderClientID`,
               `orderItemID`,
               `orderCost`,
               `orderAmount`,
               `orderTechnitianID`,
               DAY(`orderDate`),
               MONTH(`orderDate`),
               YEAR(`orderDate`),
        FROM `cloudware`.`order`
        WHERE `orderID` = " . $id . ";";
    
    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);

    $data = $result['PDOStatement']->fetch();

    append_to($data, $order_info);

    /* Client info */

    $query_text = "
        SELECT `clientName`,
               `clientSurname`
        FROM `client`
        WHERE `clientID` = " . $data['orderClientID'] . ";";

    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);

    $data = $result['PDOStatement']->fetch();

    append_to($data, $order_info);

    if($order_info['orderType'])
    {
        $query_text = "
            SELECT `equip`.`equipDesc`
            FROM `cloudware`.`equip`
            WHERE `equipID` = " . $order_info['orderItemID'] . ";";
            
        $result = execute_query($pdo, $query_text);
        if(isset($result['PDOException']))
            return array('PDOException' => $result['PDOException']);
    
        $data = $result['PDOStatement']->fetch();
        
        $order_info['itemDesc'] = $data['equipDesc'];

        $query_text = "
            SELECT `technitian`.`technitianName`,
                   FROM `cloudware`.`technitian`;

            WHERE `equipID` = " . $order_info['orderTechnitianID'] . ";";
            
        $result = execute_query($pdo, $query_text);
        if(isset($result['PDOException']))
            return array('PDOException' => $result['PDOException']);

        $data = $result['PDOStatement']->fetch();

        $order_info['technitianName'] = $data['technitianName'];
    }
    else
    {
        $query_text = "
            SELECT `service`.`serviceTitle`,
            FROM `cloudware`.`service`
            WHERE `serviceID` = " . $order_info['orderItemID'] . ";";

        $result = execute_query($pdo, $query_text);
        if(isset($result['PDOException']))
            return array('PDOException' => $result['PDOException']);

        $data = $result['PDOStatement']->fetch();

        $order_info['itemDesc'] = $data['serviceTitle'];
    }

    return $order_info;
}


function get_order_form_options($pdo, $type)
{
    if($type)
    {
        $query_text = "
            SELECT `equipDesc`
            FROM `equip`;";
    }
    else
    {
        $query_text = "
            SELECT `serviceTitle`
            FROM `service`;";
    }
    $result = execute_query($pdo, $query_text);
        if(isset($result['PDOException']))
            return array('PDOException' => $result['PDOException']);

    $data = $result['PDOStatement']->fetchAll();
    $options = array();

    foreach($data as $row):
        array_push($options, $row[0]);
    endforeach;

    return array(
        'type' => $type,
        'options' => $options
    );
}


function set_client_for_order($pdo, $passport_serial, $passport_number)
{
    $query_text = "
        SELECT `client`.`clientID`,
               `client`.`clientName`,
               `client`.`clientSurname`
        FROM `cloudware`.`client`
        WHERE `clientPassportSerial` = " . $passport_serial . " AND 
              `clientPassportNumber` = " . $passport_number . ";";

    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);

    $data = $result['PDOStatement']->fetch();

    $client_info = array();
    append_to($data, $client_info);
    return $client_info;
}


if( isset($_POST['passport_serial']) &&
    isset($_POST['passport_number']) )
{
    $passport_serial = $_POST['passport_serial'];
    $passport_number = $_POST['passport_number'];
    $pdo = establish_connection_for_role();
    $client_info = set_client_for_order(
        $pdo, $passport_serial, $passport_number
    );
    //$_SESSION['current_order_client_id'] = $client_info['clientID'];

    echo json_encode(array(
        "data" =>
            pack_order_client_info($client_info) .
            pack_order_positions_list()
    ));
    exit();
}
elseif( isset($_POST['add_position']) )
{
    echo json_encode(array(
        "data" => pack_order_position()
    ));
    exit();
}
elseif( isset($_POST['check']) )
{
    if( isset($_SESSION['current_order_client_id']) )
    { 
        echo $_SESSION['current_order_client_id'];
        exit();
    }
    else
    {
        echo "0";
        exit();
    }
}
elseif( isset($_POST['retrieve_client_search_form']) )
{
    echo json_encode(array(
        "data" => pack_order_client_search()
    ));
    exit();
}
elseif( isset($_POST['form_type']) )
{
    $type = $_POST['form_type'];
    $pdo = establish_connection_for_role();
    echo json_encode(array(
        "data" => pack_order_position_fields(
            get_order_form_options($pdo, $type)
        )
    ));
    exit();
}
else
{
    echo json_encode(array(
        "error" => "AJAX Query failed. Recieved POST array is empty"
    ));
    exit();
}

?>
