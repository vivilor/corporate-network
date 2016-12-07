<?php
include_once "db.php";

if(!empty($_GET['username']))
{
	$username = $_GET['username'];
}
if(isset($username))
{
	$priv_check_user = 'priv_check';
	$password = '4r4r4r4r_';
	$connection = mysql_dbconnect(
		$priv_check_user,
		$password,
		"cloudware"
	);
	$pdo = $connection['PDO'];
	if(!$pdo)
	{
		echo $connection['PDOException'];
		exit();
	}
	$mysql_responce = $pdo->query(
		"SELECT ugUserGroupName 
		 FROM `cloudware`.`user_group_relation`
		 WHERE ugUserAccountName = \"" . $username. "\";"
	);
	if(!$mysql_responce->fetchAll())
	{
		echo 0;
		exit();
	}
	else
	{
		echo 1;
		exit();
	}
	
}
?>
