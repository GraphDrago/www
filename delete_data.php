<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
include 'auth.php';

/* Если была нажата ссылка удаления, удаляем запись */
if(isset($_GET['id'])) {
    $del = (int)$_GET['id'];

    $query = "DELETE FROM fact WHERE idFacts = $del";
    /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
    $res = mysql_query($query) or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    if ($res == 'true') {

        echo '<p align="center">Данные успешно удалены.</p>';

    } else {

        echo '<p align="center">Неудалось выполнить запрос.</p>';
    }
    header("Location: data.php");
}


