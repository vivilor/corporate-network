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
    session_start();
    $_SESSION['current_user'] = array(
        $username,
        $PASSWORDS[$username]
    );
    $_SESSION['priv'] = array(
        'ugPrivManagement' => $usrinfo['ugPrivManagement'],
        'ugPrivOrders' => $usrinfo['ugPrivOrders'],
        'ugPrivReports' => $usrinfo['ugPrivReports'],
        'ugPrivStat' => $usrinfo['ugPrivStat']
    );
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