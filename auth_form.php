<?php
include_once 'elements/menu.php';

echo "
<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <meta charset=\"utf-8\">";
    
echo use_stylesheets(array(
        "css/elements.css",
        "css/common.css",
        "css/fonts.css"
    ));
echo use_scripts(array(
        'js/jquery-3.1.1.min.js',
        'js/db/validation.js'
    ));
echo "</head><body>";
echo pack_button_bar(1);
echo pack_form(
    "auth.php", "get",
    pack_form_tip(
        "Используйте логин и пароль, выданные Вам администратором системы.",
    "") .
    pack_text_field("usr-name", "Логин", "center inline") .
    pack_text_field("usr-pswd", "Пароль", "center iniine", "", 1) .
    pack_form_btns()
);
echo "
</body>
</html>
";