<?php
$hostname = "localhost";
$username = "root";
$database = "ckpreports";


$db_server = mysql_connect($hostname, $username);
if(!$db_server)
    die ("Невозможно подключиться к MySQL");
mysql_query("SET NAMES 'utf8'")

or die("Не удалось установить кодировку. Возможно, данные будут неверно отображаться");
mysql_select_db ($database)
or die ("Невозможно подключиться к базе данных");
?>