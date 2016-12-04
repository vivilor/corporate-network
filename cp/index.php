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
    <body>
            <?
            require '../elements/menu.php';
            echo pack_button_bar(1);
            echo pack_tile_bar();
            ?>
    </body>
    </html>
<?php
}
?>
