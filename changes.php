<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
if($_SESSION['Role'] !='system Administrator' ) {
    header('Location: index.html');
}
//if($_SESSION['Role']=='Администратор' ){

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Учет изменений в базе</title>
    <link href="css/menu.css" rel="stylesheet" type="text/css" />
    <link href="css/style_adm.css" rel="stylesheet" type="text/css" />

    <script src="js/jquery-1.7.1.min.js" type="text/javascript"  ></script>
    <script type="text/javascript" src="js/sorttable.js"></script>

    <script type="text/javascript">
        $(function() {
            $(window).scroll(function() {
                if($(this).scrollTop() != 0) {
                    $('#toTop').fadeIn();
                } else {
                    $('#toTop').fadeOut();
                }
            });
            $('#toTop').click(function() {
                $('body,html').animate({scrollTop:0},800);
            });
        });
    </script>

</head>
<body>

<div id = "results">

    <div align="center" style = "padding-top: 20px; ">
        <div class = "heading" >
            <label style="float: left;" class = "img_logo"> <img src="img/logo.png" style="width:130px;height:100px;"></label>
            <label style="" class="headers">Интерфейс раздачи ролей</label>
            <label  class="logoff"> <a href="logoff.php?mode=logoff"
                                       onclick="return window.confirm('Вы уверены что хотите покинуть страницу?');">
                    <label class="log" > Выход</label> <img src="img/logout.png" style="float: right" ></a></label>
            <div class = "name_manager"><?echo $_SESSION['Name_Users'];?></div>
        </div>


        <ul class="menu">
            <li ><a href="index.php">Интерфейс раздачи ролей</a> </li>
            <li><a href="report.php">Итоговая форма</a> </li>
            <li><a href="action.php">Администрирование</a></li>
            <li class="current"><a href="changes.php">Учет изменений</a></li>
        </ul>

    </div>
    <?php

    $result =  mysql_query ("SELECT * FROM logging ORDER BY Date DESC")
    or die ("<p align='center'><b>Ошибка в обращении к БД.Невозможно получить список пользователей!<br>
Обновите страницу или обратитесь к администратору</b> </p>");
    if(($num_rows =  mysql_num_rows($result)) != 0) {
        $table ="
        <form action='' id='users' name='users' class = 'users' method='post' enctype='multipart/form-data' style='margin:20px;'>
        <table width='69%' class='sortable' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center'>
             <thead>";
        $table.='<tr>
                <th >Дата</th>
                <th >Пользователь</th>
                <th >Внесенные изменения</th>
                <th >Измененный объект</th>
                <th >Вид изменений</th>

            </tr> </thead><tbody>';
        while($myrow = mysql_fetch_array($result)){
            $table.='<tr> ';
            $table.='<td name="Name">'.$myrow['Date'].'</td>';
            $table.='<td name="manager">'.$myrow['Manager'].'</td>';
            $table.='<td name="ccs">'.$myrow['CCS'].'</td>';
            $table.='<td name="table">'.$myrow['Table_Change'].'</td>';
            $table.='<td name="actions">'.$myrow['Actions'].'</td>';
            $table.='</tr>';
        }
        $table.='</tbody> <tfoot></tfoot></table>
            </form>	';
        echo $table;
    }
    ?>

</div>
<div ID = "toTop" ><img src="img/up.png"> </div>
</body>
</html>