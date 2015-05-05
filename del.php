<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';
$_SESSION['id'];
if (isset($_GET['p'])) {               #Проверяем существует ли переменная (p)
    $id = $_GET['p'];

  $f = mysql_query("Select * from attachment Where PathFile = '$id'");
    $file = mysql_fetch_array($f);
    $dir = '/var/www/html/uploads/';

    if($f){
        $f = mysql_query("Delete from attachment Where PathFile = '$id'");
       $u = unlink($dir.$file['PathFile']);
        if($u){
            echo "
        <script>history.back();

                alert('Файл успешно удален.')
                </script>";
        }else{
            echo "<script>history.back();
                alert('Неудалось удалить файл')</script>";
        }
        //echo $file['PathFile'];

    }
//
//    while($file = mysql_fetch_array($f)){
//        $f = mysql_query("Delete from attachment Where Fact_idFacts = $id");
//        unlink($file['PathFile']);
//    }
    //echo "<script>history.back();</script>";
//    if ($_GET['p'] == 'del') {         #Создаем адрес в виде (p=del)
//        $file = 'file.zip';            #Заносим имя файла в переменную
//
//        unlink($file);                 #Удаляем архив
//        header("location: index.php"); #Переходим на главную страницу
//    }
}