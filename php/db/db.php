<?php

$PASSWORDS = array(
    'accountant' => '1q1q1q1q_',
    'operator' => '2w2w2w2w_',
    'admin' => '3e3e3e3e_'
);

function mysql_dbconnect($username, $password, $dbname)
{
    try
    {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=' . $dbname,
            $username,
            $password
        );
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $pdo->exec('SET NAMES "utf8"');
        return array(
            "PDO" => $pdo,
            "PDOException" => null
        );
    }
    catch (PDOException $e)
    {
        return array(
            "PDO" => null,
            "PDOException" => $e->getMessage()
        );
    }
}

function find_role($username, $password)
{
    $priv_check_user = 'priv_check';
	$priv_check_password = '4r4r4r4r_';
	$connection = mysql_dbconnect(
		$priv_check_user,
		$priv_check_password,
		"cloudware"
	);
	$pdo = $connection['PDO'];
	if(!$pdo)
	{
		return array(
		    'error' => 1,
		    'error-msg' => $connection['PDOException']
		);
	}
    $q = $pdo->prepare('
        SELECT ugUserGroupName,
               ugPrivManagement,
               ugPrivOrders,
               ugPrivReports,
               ugPrivStat
        FROM `cloudware`.`user_group_relation`
        WHERE ugUserAccountName = ? AND
              ugUserAccountPassword = ?;');
    $q->execute(array($username, $password));
    $usrGroup = $q->fetch();
    return $usrGroup;
}
?>
