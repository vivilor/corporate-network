<?php
    if( ($_GET['back'] == 1) || !$_GET)
    {
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <link href="/css/fonts.css" type="text/css" rel="stylesheet">
            <link href="/css/common.css" type="text/css" rel="stylesheet">
            <link href="/css/controls.css" type="text/css" rel="stylesheet">
            <title>Панель управления</title>
        </head>
        <body class="bkg-default">
            <div class="relative">
                <? require 'menu.php';?>
                <span id="cp-home" class="relative center segoe-ui h14pt txt-default block">
                    Для начала работы
                    <br>
                    воспользуйтесь меню
                </span>
            </div>
        </body>
        </html>
    <?php
    }
?>
