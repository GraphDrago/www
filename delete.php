<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
$manager =  $_SESSION['Name_Manager'];
$CreateDate = date('Y-m-d H-i-s');

/* Если была нажата ссылка удаления, удаляем запись */
if(isset($_GET['id'])) {
    $del = (int)$_GET['id'];
    $q = mysql_query("select u.Login, u.Name from users  u where idUsers = $del ")
    or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    $user = mysql_fetch_array($q);
    $user_login = $user['Login'];
    $user_name = $user['Name'];

    $m = mysql_query("select Login from manager where Login = '$user_login'")
    or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    $users = mysql_fetch_array($m);
    $user_logins = $users['Login'];

    if(mysql_num_rows($m) == 0) {
        $query = "DELETE FROM users WHERE idUsers = $del";
        if($query){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', ' $manager','$user_name',N'Таблица пользователей',N'DELETE')");
        }

        /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
        $res = mysql_query($query) or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
        if ($res == 'true') {

            echo '<p align="center">Данные успешно удалены.</p>';

        } else {

            echo '<p align="center">Неудалось выполнить запрос.</p>';
        }
        header("Location: index.php");
    }else{
        echo '<p align="center">Невозможно удалить,за этим пользователем закреплен филиал.</p>';
    }
}


