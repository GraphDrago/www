<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
if (isset($_POST)) {


    $res = mysql_query("Select u.idUsers,r.idRole, u.Name as Name_User,u.EMail as EMail,r.Name as Name_Role, u.Role_idRole
      from users u INNER JOIN role r ON u.Role_idRole = r.idRole")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    while($row = mysql_fetch_array($res))
    {

        $idUsers = $row['idUsers'];
        $idRole = $_POST['role' . $idUsers];
        $update = mysql_query("UPDATE users SET Role_idRole = '$idRole' Where idUsers='$idUsers'")
        or die ('<p align="center">Ошибка в обращении к БД. Невозможно обновить роль. Попробуйте обновить страницу.</p>');
        if ($update == 'true') {
            $status = '<p align="center">Роли изменены!</p>';
        }else{
            $status = '<p align="center">Ошибка.Попробуйте еще раз!</p>';
        }
    }
    include 'index.php';
    echo $status;
}

