<?php

$month = array(
    "01" => array("January" , "Январь"),
    "02" => array("February" , "Февраль"),
    "03" => array("March" , "Март"),
    "04" => array("April" , "Апрель"),
    "05" => array("May" , "Май"),
    "06" => array("June" , "Июнь"),
    "07" => array("July" , "Июль"),
    "08" => array("August" , "Август"),
    "09" => array("September" , "Сентябрь"),
    "10" => array("October" , "Октябрь"),
    "11" => array("November" , "Тоябрь"),
    "12" => array("December" , "Декарь"),
);


function pack_select_field($content, $class="", $id="")
{
    $rows = "";
    foreach ($content as $row):
        $rows .= pack_in_paired_tag(
            "option",
            array(
                "class" => "segoe-ui small",
                "value" => $id . "-" . $row,
            ),
            $row
        );
    endforeach;
    return pack_in_paired_tag(
        "select",
        array(
            "id" => $id,
            "class" => "select-field segoe-ui small" . $class
        ),
        $rows
    );
}


function pack_year_choose()
{
    $username = $_SESSION['current_user']['username'];
    $password = $_SESSION['current_user']['password'];
    $pdo_error = "";
    $query_text = "
        SELECT DISTINCT YEAR(clientServicingStart) FROM `client`";
    $result = mysql_dbconnect($username, $password, "cloudware");
    if(isset($result['PDOException']))
    {
         return array('PDOException' => $result['PDOException']);
    }
    $pdo = $result["PDO"];
    $responce = $pdo->query($query_text);
    $raw_years = $responce->fetchAll();
    $years = array();
    foreach ($raw_years as $year):
        array_push($years, $year['YEAR(clientServicingStart)']);
    endforeach;
    return $years;
}


function pack_cell($month_index, $exist, $id="", $class="")
{
    global $month;
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "clndr-cell " . $class
        ),
        pack_in_paired_tag(
            "div",
            array(
                "class" => "large segoe-ui clndr-month-index regular inline "
            ),
            $month_index
        ) .
        pack_upper_text(
            ($exist ? "" : "не ") . "создан",
            $exist,
            "",
            "clndr-caption inline"
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "class" => "small clndr-month-name-en"
            ),
            $month[$month_index][0]
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "class" => "medium clndr-month-name-ru"
            ),
            $month[$month_index][1]
        )
    );
}