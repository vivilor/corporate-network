<html>
<head>
    <title>Просмотр отчета</title>
    <meta charset=utf-8>
    <link href='/css/elements.css' type='text/css' rel='stylesheet'>
    <link href='/css/common.css' type='text/css' rel='stylesheet'>
    <link href='/css/fonts.css' type='text/css' rel='stylesheet'>
</head>
<body>
<div style='vertical-align: top; display: inline-block'>
    <?php
    include 'menu.php';
    ?>
</div>
<div class='inline' style='margin-left:300px'>
    <div align="center" class='txt-default'>
        <h3>Отчет</h3>
        <br><br>
    </div>
    <table border="1" width="100%" class='txt-default'>
        <thead>
        <th align="center">Название услуги</th>
        <th align="center">Количество клиентов</th>
        <th align="center">Суммарная прибыль</th>
        </thead>
        <tbody>
            <?php
            require 'db_connect.php';
            $r_month=$_GET['r_month'];
            $r_year=$_GET['r_year'];
            $query_text = "SELECT reportServiceTitle,
                                  reportClientsQuantity,
                                  reportSummaryCost
                           FROM report
                           WHERE reportMonth = $r_month AND
                                 reportYear = $r_year";
            include 'safe_query.php';
            if ($rownumb1 == 0)
            {
                try
                {
                    $q = $pdo->query("CALL `create_report`($r_month, $r_year);");
                }
                catch (PDOException $e) {
                    $output = "Ошибка при запуске процедуры" . $e->getMessage();
                    include 'error.php';
                    exit();
                }
            }
            include 'safe_query.php';
            foreach ($report1 as $record):
                ?>
                <tr >
                    <td align="center">
                        <?php echo "$record[reportServiceTitle]"; ?>
                    </td>
                    <td align="center">
                        <?php echo "$record[reportClientsQuantity]"; ?>
                    </td>
                    <td align="center">
                        <?php echo "$record[reportSummaryCost]" . " руб."; ?>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table><br><br><br>
    <div align="center" class='txt-default'>
        <a href="/index.php" class='btn-default inline'>
            Выйти
        </a>
    </div>
</div>
</body>
</html>