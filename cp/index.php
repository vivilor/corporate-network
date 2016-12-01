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
            $menu_element = $_SERVER['DOCUMENT_ROOT'] . '/elements/menu.php';
            $exit_btn_only = 1;
            require $menu_element;
            $tiles = 1;
            require $menu_element;
            ?>
    </body>
    </html>
<?php
}
?>
