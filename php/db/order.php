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


function positions_shift(&$array, $index)
{
    $array_len = count($array);
    for($i = $index; $i < ($array_len - 1); $i++)
    {
        $array[$i] = $array[$i + 1];
    }
    unset($array[$array_len - 1]);
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


function store_prices($pdo)
{
    $_SESSION['prices_storage'] = array(
        "0" => array(),
        "1" => array()
    );

    /* Obtaining equip prices */

    $query_text = "SELECT `equipDesc`, `equipCost` FROM `equip`;";
    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
        return array('PDOException' => $result['PDOException']);
    $equip_prices = $result['PDOStatement']->fetchAll();

    /* Obtaining service prices */

    $query_text = "SELECT `serviceTitle`, `serviceCost` FROM `service`;";
    $result = execute_query($pdo, $query_text);
    if(isset($result['PDOException']))
    return array('PDOException' => $result['PDOException']);

    $service_prices = $result['PDOStatement']->fetchAll();

    foreach($equip_prices as $price):
        $_SESSION['prices_storage']['1'][$price['equipDesc']]
            = $price['equipCost'];
    endforeach;

    foreach($service_prices as $price):
        $_SESSION['prices_storage']['0'][$price['serviceTitle']]
            = $price['serviceCost'];
    endforeach;

    return array("success" => "true");
}


function append_position($type, $name, $quantity="1", $technitian="")
{
    $_SESSION['order_positions'][count($_SESSION['order_positions'])] =
        array($type, $name, $quantity, $technitian);
}

function change_position_cost($index, $new_quantity)
{
    $type = $_SESSION['order_positions'][$index][0];
    $name = $_SESSION['order_positions'][$index][1];
    $quantity = $new_quantity - $_SESSION['order_positions'][$index][2];
    $_SESSION['order_cost'] =
        $_SESSION['order_cost'] + $quantity *
            $_SESSION['prices_storage'][$type][$name];
}

function get_order_form_options($pdo, $type)
{
    if( isset($_SESSION['options'][$type]) )
        return array(
            'type' => $type,
            'options' => $_SESSION['options'][$type]
        );
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

    if( !isset($_SESSION['options']) )
        $_SESSION['options'] = array($type => $options);

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
               `client`.`clientSurname`,
               `client`.`clientSex`,
               `client`.`clientFunds`,
               `client`.`clientPhoneNumber`,
               `client`.`clientEMail`
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
    $_SESSION['order_cost'] = 0;
    $_SESSION['order_positions'] = array();
    echo json_encode(array(
        "data" =>
            pack_order_client_info($client_info) .
            pack_order_positions_list(),
        "clientID" => $client_info["clientID"],
        "clientSex" => $client_info["clientSex"],
        "clientName" => $client_info["clientName"],
        "clientEMail" => $client_info["clientEMail"],
        "clientFunds" => $client_info["clientFunds"],
        "clientSurname" => $client_info["clientSurname"],
        "clientPhoneNumber" => $client_info["clientPhoneNumber"],
        "clientPassportSerial" => $passport_serial,
        "clientPassportNumber" => $passport_number

    ));
    exit();
}

elseif( isset($_POST['remove_all']) )
{
    $_SESSION['order_positions'] = array();
    $_SESSION['order_cost'] = 0;
    exit();
}
elseif( isset($_POST['changed_index']) )
{
    $index = $_POST['changed_index'];
    if( isset($_POST['changed_type']) )
    {
        $_SESSION['order_positions'][$index][3] = "";
        $_SESSION['order_positions'][$index][2] = "";
        $_SESSION['order_positions'][$index][1] = "";
        $_SESSION['order_positions'][$index][0]
            = $_POST['changed_type'];
        change_position_cost($index, 0);
    }
    elseif( isset($_POST['changed_quantity']))
    {
        change_position_cost($index, $_POST['changed_quantity']);
    }
}
elseif( isset($_POST['store_prices']) )
{
    $pdo = establish_connection_for_role();
    store_prices($pdo);
}

/*
    Append new position to $_SESSION['order_positions']
    << 'selected_name', 'selected_type'
    >> 'selected_item_price'
*/
elseif( isset($_POST['selected_name']) &&
        isset($_POST['selected_type']) )
{
    if( isset($_POST['selected_quantity']) &&
        isset($_POST['selected_technitian']) )
    {
        append_position(
            $_POST['selected_name'],
            $_POST['selected_type'],
            $_POST['selected_quantity'],
            ($_POST['selected_technitian'] == '-1' ?
                "1" :
                $_POST['selected_technitian'])
        );
        echo json_decode(array(
            "selected_item_price" =>
            $_SESSION
                [$_POST['selected_type']]
                    [$_POST['selected_name']]
        ));
        exit();
    }
    else
    {
        append_position(
            $_POST['selected_name'],
            $_POST['selected_type']);
        echo json_decode(array(
            "selected_item_price" =>
            $_SESSION['prices_storage']
                [$_POST['selected_type']]
                    [$_POST['selected_name']]
        ));
        exit();
    }
}

elseif( isset($_POST['removed_index']) )
{
    $multiplier = $_SESSION['order_positions']
                    [$_POST['removed_index']]
                        [2];
    $name = $_SESSION['order_positions']
                    [$_POST['removed_index']]
                        [1];
    $type = $_SESSION['order_positions']
                    [$_POST['removed_index']]
                        [0];
    $cost = $_SESSION['prices_storage']
                [$type]
                    [$name];
    if($multiplier == '')
        $multiplier = 1;
    positions_shift($_SESSION['order_positions'], $_POST['removed_index']);
    $_SESSION['order_cost'] -= ($multiplier * $cost);
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
