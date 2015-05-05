<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
include 'auth.php';

/* Если была нажата ссылка удаления, удаляем запись */
if(isset($_GET['id'])) {
    $del = (int)$_GET['id'];
    $quer = mysql_query("DELETE FROM attachment WHERE Fact_idFacts = $del");
    $query = "DELETE FROM fact WHERE idFacts = $del";
    /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
    $res = mysql_query($query) or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    if ($res == 'true') {

        echo '<script> history.back();alert("Данные успешно удалены!");</script>';

    } else {

        echo '<script> history.back();alert("Не удалось удалить данные!");</script> ';
    }
    //header("Location: records.php");
}


