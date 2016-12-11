<?php

$months_list = array(
    "1"  => array("January" ,   "Январь"),
    "2"  => array("February" ,  "Февраль"),
    "3"  => array("March" ,     "Март"),
    "4"  => array("April" ,     "Апрель"),
    "5"  => array("May" ,       "Май"),
    "6"  => array("June" ,      "Июнь"),
    "7"  => array("July" ,      "Июль"),
    "8"  => array("August" ,    "Август"),
    "9"  => array("September" , "Сентябрь"),
    "10" => array("October" ,   "Октябрь"),
    "11" => array("November" ,  "Ноябрь"),
    "12" => array("December" ,  "Декабрь")
);

require_once 'packer.php';
require_once 'form.php';


function pack_year_choose()
{
    $username = $_SESSION['current_user']['username'];
    $password = $_SESSION['current_user']['password'];
    $pdo_error = "";
    $query_text = "
        SELECT DISTINCT YEAR(clientServicingStart) FROM `client`";
    $responce = mysql_dbconnect($username, $password, "cloudware");
    if(isset($result['PDOException']))
    {
         return array('PDOException' => $responce['PDOException']);
    }
    $pdo = $responce["PDO"];
    $result = $pdo->query($query_text);
    $raw_years = $result->fetchAll();
    $years = array();
    foreach ($raw_years as $year):
        array_push($years, $year['YEAR(clientServicingStart)']);
    endforeach;
    return $years;
}

function pack_column($content, $number)
{
    return pack_in_paired_tag(
        "div",
        array(
            "class" => "clndr-column inline center v-top"
        ),
        pack_in_paired_tag(
            "div",
            array(
                "class" => "column-caption segoe-ui small"
            ),
            $number . " квартал"
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "class" => "month-column"
            ),
            $content
        )
    );
}     

function pack_cell($month_index, $exist, $id="", $class="")
{
    global $months_list;
    return pack_in_paired_tag(
        "div",
        array(
            "id" => $id,
            "class" => "clndr-cell left " . $class
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
            $months_list[$month_index][0]
        ) .
        pack_in_paired_tag(
            "div",
            array(
                "class" => "medium clndr-month-name-ru"
            ),
            $months_list[$month_index][1]
        )
    );
}

function pack_calendar($columns)
{
    $calendar = "";
    foreach ($columns as $column):
        $calendar .= $column;
    endforeach;
    return pack_in_paired_tag(
        "div",
        array(
            "id" => "calendar",
            "class" => "calendar"
        ),
        $calendar
    );
}
