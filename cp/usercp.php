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
            <link href="/css/elements.css" type="text/css" rel="stylesheet">
            <title>Панель управления</title>
        </head>
        <body class="bkg-default">
            <div class="relative">
                <?require 'menu.html';?>
                <span id="cp-home" class="relative center russia h14pt txt-default">
                    Для начала работы,
                    воспользуйтесь панелью навигации слева
                </span>
            </div>
        </body>
        </html>
    <?php
    }
?>
