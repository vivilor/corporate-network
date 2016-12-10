<?php
if (isset($_POST['usr-name']) &&
    isset($_POST['usr-pswd']))
{
    $username = $_POST['usr-name'];
    $password = $_POST['usr-pswd'];
    include_once 'php/db/db.php';
    $usrinfo = find_role($username, $password);
    if(isset($usrinfo['error']))
    {
        $pdo_error = $usrinfo['error-msg'];
        require 'auth_form.php';
        exit();
    }
    $username = $usrinfo['ugUserGroupName'];
    $mysql = mysql_dbconnect($username, $PASSWORDS[$username], "cloudware");
    if($mysql['PDO'] == null)
    {
        $pdo_error = $mysql['PDOException'];
        require 'auth_form.php';
        exit();
    }

    /* Starting session */

    session_start();
    session_id();
    $_SESSION['started'] = 1;
    $_SESSION['current_user'] = array(
        'username' => $username,
        'password' => $PASSWORDS[$username]
    );
    $_SESSION['priv'] = array(
        'management' => $usrinfo['ugPrivManagement'],
        'orders' => $usrinfo['ugPrivOrders'],
        'reports' => $usrinfo['ugPrivReports'],
        'stat' => $usrinfo['ugPrivStat']
    );
    $_SESSION['root'] = $_SERVER['DOCUMENT_ROOT'];
    require 'auth_success.html';
    exit();
}
else
{
    $exit_btn_only = 1;
    require 'auth_form.php';
    exit();
}
?>