<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Создание отчета</title>
    <link href='/css/elements.css' type='text/css' rel='stylesheet'>
    <link href='/css/common.css' type='text/css' rel='stylesheet'>
    <link href='/css/fonts.css' type='text/css' rel='stylesheet'>
</head>
<body>
    <?php
    $tiles = 0; /* for menu */
    $username = 'root';
    $password = '';
    $dbname = 'cloudware';
    include '../../elements/menu.php';
    if (!isset($_GET['r_year']) &&
        !isset($_GET['r_month']))
    {
        include '../utils/db.php';
        require 'input.html';
        exit();
    }
    else
    {
        ?>
        <div class='inline relative' align='center'>
            <div align="center" class='segoe-ui h20pt'>
                <h3>Отчет</h3>
                <br><br>
            </div>
        <table border="0" width="100%" class='segoe-ui h14pt'>
            <thead>
                <th align="center">Название услуги</th>
                <th align="center">Количество клиентов</th>
                <th align="center">Суммарная прибыль</th>
            </thead>
            <tbody>
                <?php
                require '../utils/db.php';
                $r_month = $_GET['r_month'];
                $r_year = $_GET['r_year'];
                $query_text = "SELECT reportServiceTitle,
                                      reportClientsQuantity,
                                      reportSummaryCost
                               FROM report
                               WHERE reportMonth = $r_month AND
                                     reportYear = $r_year";
                include '../utils/safe_query.php';
                if ($result_rows == 0)
                {
                    try
                    {
                        $q = $pdo->query("CALL `create_report`($r_month, $r_year);");
                    }
                    catch (PDOException $e) {
                        $output = "Ошибка при запуске процедуры" . $e->getMessage();
                        include '../utils/error.php';
                        exit();
                    }
                }
                include '../utils/safe_query.php';
                foreach ($query_result as $row):
                    ?>
                    <tr>
                        <td align="center">
                            <?php echo "$row[reportServiceTitle]"; ?>
                        </td>
                        <td align="center">
                            <?php echo "$row[reportClientsQuantity]"; ?>
                        </td>
                        <td align="center">
                            <?php echo "$row[reportSummaryCost]" . " руб."; ?>
                        </td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </tbody>
        </table>
        <br><br><br>
        </div>
        <?
    }?>
</body>
</html>
