<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
if($_SESSION['Role'] !='system Administrator' ) {
    header('Location: index.html');
}

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Интерфейс раздачи ролей</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css" />
        <link href="css/style_adm.css" rel="stylesheet" type="text/css" />

        <script src="js/jquery-1.7.1.min.js" type="text/javascript"  ></script>
        <script type="text/javascript" src="js/sorttable.js"></script>
        <script type="text/javascript">
            function AjaxFormRequest(result_id,formMain,url) {
                jQuery.ajax({
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
            }


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
                <li class="current"><a href="index.php">Интерфейс раздачи ролей</a> </li>
                <li><a href="report.php">Итоговая форма</a> </li>
                <li><a href="action.php">Администрирование</a></li>
                <li><a href="changes.php">Учет изменений</a></li>
            </ul>

        </div>
<?php

    $result =  mysql_query ("SELECT u.idUsers,r.idRole,u.Name as Name_User,u.EMail as EMail,r.Name as Name_Role,u.Role_idRole FROM users u
                            INNER JOIN role r ON u.Role_idRole = r.idRole")
    or die ("<p align='center'><b>Ошибка в обращении к БД.Невозможно получить список пользователей!<br>
Обновите страницу или обратитесь к администратору</b> </p>");
    if(($num_rows =  mysql_num_rows($result)) != 0) {
        $table ="
        <form action='' id='users' name='users' class = 'users' method='post' enctype='multipart/form-data' style='margin:20px;'>
        <table width='69%' class='sortable' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center'>
             <thead>";
            $table.='<tr>
                <th >Имя</th>
                <th >E-Mail</th>
                <th >Текущая роль</th>
                <th >Задать роль</th>
                <th >Удалить</th>
            </tr> </thead><tbody>';
            while($myrow = mysql_fetch_array($result)){
                $table.='<tr> ';
                    $table.='<td name="Name_'.$myrow['idUsers'].'">'.$myrow['Name_User'].'</td>';
                    $table.='<td name="email_'.$myrow['idUsers'].'">'.$myrow['EMail'].'</td>';
                    $table.='<td name="role">'.$myrow['Name_Role'].'</td>';
                $table.='<td >
                    <select name = "role'.$myrow['idUsers'].'" ><option value="'.$myrow['Role_idRole'].'">Выберите роль</option>';
                $res =  mysql_query ("SELECT * FROM role ORDER BY Name")
                or die ("<b>Ошибка в обращении к серверу.Невозможно получить список ролей!</b> ");
                while ($row = mysql_fetch_array($res)){
                    $table.='<option value="'.$row['idRole'].'" >'.$row['Name'].'</option>';
                }
                $table.='</select></td>';
                $table.= '<td><a href="delete.php?id='.$myrow['idUsers'].'"
                 onClick="return window.confirm(\'Вы уверены, что хотите удалить пользователя?\')">Удалить</a></td>';
                $table.='</tr>';
            }
            $table.='</tbody> <tfoot></tfoot></table>
           <p align="center"> <input type="button" value="Изменить" onclick="AjaxFormRequest(\'results\', \'users\', \'role.php\')" /></p>
            </form>	';
            echo $table;
    }
?>

 </div>
    </body>
</html>
<?php
//}else{
//    header('Location: index.html');
//    echo '<p align="center">У вас нет прав для доступа к этой странице!</p>';
//}


