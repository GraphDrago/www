<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';

if (isset($_GET['p'])) {               #Проверяем существует ли переменная (p)
    $id = $_GET['p'];
    $f = mysql_query("Select * from attachment Where PathFile = '$id'");
    $file = mysql_fetch_array($f);
    $dir = '/var/www/html/uploads/';
    if($f){
        $delete = mysql_query("Delete from attachment Where PathFile = '$id'");
        $u = unlink($dir.$file['PathFile']);
        if($u){
            echo "Файл успешно удален.";
        }else{
            echo "Неудалось удалить файл";
        }

    }
}