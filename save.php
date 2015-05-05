<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';
header('Content-Type: application/json');
header('Charset: utf-8');

if (isset($_POST)){

    $CreateDate = date('Y-m-d H-i-s');
    $count = $_POST['count'];
    $managers =  $_SESSION['manager'];
    $manager =  $_POST['managers_name'];

    $dates = $_POST['dates'];
    $datetime = strtotime($dates);
    $date= $dates.'-'.date('t', $datetime);

    $branch= $_POST['branch'];
    $district= $_POST['district'];

    //$ckp = ($_POST['ckp']);
    $ckp_id = ($_POST['ckp']);
    $city = $_POST['city'];
    $ckp_name = $_POST['ckp_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $phone= $_POST['phone'];
    $openingHours = $_POST['openingHours'];

    $res =  mysql_query ("SELECT b.Name, m.Login, m.idManager, b.idBranch
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                                          WHERE b.Name = '".$branch."' and m.Login = $manager")
    or (json_encode(array('response' => 'Ошибка при обращении к БД.
            Попробуйте обновить страницу или обратится к администратору.23'
    )));

    $num = mysql_num_rows($res);
   // echo json_encode(array('add' => $_POST));
    echo json_encode($_POST);


    //echo json_encode(array('add' => $branch));


}