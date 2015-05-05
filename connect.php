<?php
    ini_set("default_charset","UTF-8");
    ini_set('display_errors', 'Off');
    $hostname = "172.31.253.46";
    $username = "ckpreports";
    $database = "ckpreports";
    $pass ="nMmoDPnw9";

    $db_server = mysql_connect($hostname, $username,$pass);
    if(!$db_server)
        die ("Невозможно подключиться к MySQL");
    mysql_query("SET NAMES 'utf8'")
        or die("Не удалось установить кодировку. Возможно, данные будут неверно отображаться");
    mysql_select_db ($database)
        or die ("Невозможно подключиться к базе данных");
?>