<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';

/* Если была нажата ссылка удаления, удаляем запись */
if(isset($_GET['id'])) {
    $CreateDate = date('Y-m-d H-i-s');
    $del = (int)$_GET['id'];
    $manager =  $_SESSION['Name_Manager'];
    $sql = mysql_query("Select c.Name as ckp From fact f
 INNER JOIN ccs c ON c.idCCS = f.CCS_idCCS
    Where CCS_idCCS='$del'");
    $data = mysql_fetch_assoc($sql);
    $ckp_name = $data['ckp'];
    $quer = mysql_query("DELETE FROM attachment WHERE Fact_idFacts = $del");
    if($quer){
        $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$ckp_name',N'Таблица вложений',N'DELETE')");
    }
    $query = "DELETE FROM fact WHERE idFacts = $del";
//    /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
   $res = mysql_query($query) or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    if($res){
        $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$ckp_name',N'Таблица фактов',N'DELETE')");
    }
    if ($res == 'true') {
echo $manager;
echo  $ckp_name;
        echo '<script> history.back();alert("Данные успешно удалены!"); </script>';

    } else {

        echo '<script> history.back();alert("Не удалось удалить данные!");</script> ';
    }
   // echo '<script> history.back();</script>';
//    echo "<script> history.back();</script>";
//    header("Location: admin.php");
}


