<?php
include_once 'elements/form.php';
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
        'js/db/validation.js',
        'js/auth.js'
    ));
echo "</head><body>";
echo pack_button_bar(1);
echo pack_form("auth.php", "get", " hidden",
    pack_form_tip(
        "Используйте логин и пароль, выданные Вам администратором системы.",
        ""
    ) .
    pack_text_field("usr-name", "Логин", "") .
    pack_text_field("usr-pswd", "Пароль", "", "", 1)  .
    pack_form_btns(1)
);
echo "
</body>
</html>
";