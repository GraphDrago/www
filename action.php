<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
$CreateDate = date('Y-m-d H-i-s');
//include 'auth.php';
if($_SESSION['Role'] !='system Administrator' ) {
    header('Location: index.html');
}

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Администрирование</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css" />
        <link href="css/style_adm.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"  ></script>
        <script type="text/javascript">
            function AjaxFormRequest(result_id,formMain,url) {
                $("#").submit(function(event) {
                    event.preventDefault();
                    $.ajax({
                        url:     url,
                        type:     "POST",
                        dataType: "html",
                        data: jQuery("#"+formMain).serialize(),
                        success: function(response) {
                            document.getElementById(result_id).innerHTML = response;
                        },
                        error: function(response) {
                            document.getElementById(result_id).innerHTML = "<p>Возникла ошибка при отправке формы. Попробуйте еще раз</p>";
                        }
                    });
                });
            }


        </script>

    </head>
<body>

<div align="center" style = "padding-top: 20px; ">

    <div class = "heading" >
        <label style="float: left;" class = "img_logo"> <img src="img/logo.png" style="width:130px;height:100px;"></label>
        <label style="" class="headers">Администрирование</label>
        <label  class="logoff"> <a href="logoff.php?mode=logoff"
                                   onclick="return window.confirm('Вы уверены что хотите покинуть страницу?');">
                <label class="log" > Выход</label> <img src="img/logout.png" style="float: right" ></a></label>
        <div class = "name_manager"><?echo $_SESSION['Name_Users'];?></div>
    </div>

    <ul class="menu">
        <li><a href="index.php">Интерфейс раздачи ролей</a></li>
        <li><a href="report.php">Итоговая форма</a> </li>
        <li class="current"><a href="action.php"> Администрирование</a></li>
        <li><a href="changes.php">Учет изменений</a></li>
    </ul>
</div>
<?
include 'table.php';
?>

<div id = "results" align="center"></div>
</body>
    </html>
<?
//onclick="AjaxFormRequest('results', 'admin', 'adminka.php');"


