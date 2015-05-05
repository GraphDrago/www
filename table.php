<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
$CreateDate = date('Y-m-d H-i-s');
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Администрирование</title>
    <link href="css/menu.css" rel="stylesheet" type="text/css" />
    <link href="css/style_adm.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery-1.7.1.min.js" type="text/javascript" charset="utf-8"></script>
    <link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
    <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="bootstrap-datepicker-master/js/locales/bootstrap-datepicker.ru.js" type="text/javascript" charset="UTF-8"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $( "#monthToday" ).datepicker({
                format: "yyyy-mm",
                minViewMode: 1,
                language: "ru",
                autoclose: true
            })

        });
    </script>
    <script type="text/javascript">
        function Ftest (obj)
        {
            if (this.ST) return; var ov = obj.value;
            var ovrl = ov.replace (/\d*\d*/, '').length; this.ST = true;
            if (ovrl > 0) {obj.value = obj.lang; Fshowerror (obj); return}
            obj.lang = obj.value; this.ST = null;
        }
        function Fshowerror (obj)
        {
            if (!this.OBJ)
            {this.OBJ = obj; obj.style.backgroundColor = 'pink'; this.TIM = setTimeout (Fshowerror, 50)}
            else
            {this.OBJ.style.backgroundColor = ''; clearTimeout (this.TIM); this.ST = null; Ftest (this.OBJ); this.OBJ = null}
        }
    </script>

</head>

<body>
<form action = "adminka.php" method="post"  id = "formAdmin" enctype="multipart/form-data">
    <table class = "admin" align="center" style = "width: 57%" >
        <tr>
            <td>Округ</td>
            <td><select name="district" id="district" class="select" >
                    <option value="">-- выберите округ --</option>
                    <?
                    $result = mysql_query("SELECT * FROM district ORDER BY Name");
                    while ($row = mysql_fetch_array($result)) {
                        echo "<option value='".$row['idDistrict']."'>
                                        " . $row['Name'] . "</option>";
                    }
                    ?>
                </select></td>
            <td><input type = "submit" name = "add_district" value = "Добавить" id="admin"></td>
            <td><input type = "submit" name = "delete" value = "Удалить" id="admin"
                       onClick="confirm('Вы уверены, что хотите удалить данные?')"></td>
            <td><input type = "submit" name = "edit_district" value = "Редактировать" id="admin"></td>
        </tr>
    <tr>
        <td>Филиал</td>
        <td><select name="branch" id="branch" class="select" >
                        <option value="">-- выберите филиал --</option>
<?
                        $result = mysql_query("SELECT * FROM branch ORDER BY Name");
                        while ($row = mysql_fetch_array($result)) {
                            echo "<option value='".$row['idBranch']."'>
                                        " . $row['Name'] . "</option>";
                        }
?>
</select></td>
<td><input type = "submit" name = "add_branch" value = "Добавить" id="admin"></td>
<td><input type = "submit" name = "delete" value = "Удалить" id="admin"
           onClick="confirm('Вы уверены, что хотите удалить данные?')"></td>
<td><input type = "submit" name = "edit_branch" value = "Редактировать" id="admin"></td>
</tr>
<tr>
    <td>ЦКП</td>
    <td> <select name="ckp" id="ckp" class="select">
            <option value="">-- выберите ЦКП --</option>
            <?
            $result = mysql_query("SELECT * FROM ccs ORDER BY Name");
            while ($row = mysql_fetch_array($result)) {

                echo '<option value="' . $row['idCCS'] . '">' . $row['Name'] . '</option>';
            }
            ?>
        </select></td>
    <td><input type = "submit" name = "add_ckp" value = "Добавить" id="admin"></td>
    <td><input type = "submit" name = "delete" value = "Удалить" id="admin"
               onClick="confirm('Вы уверены, что хотите удалить данные?')"></td>
    <td><input type = "submit" name = "edit_ckp" value = "Редактировать" id="admin"></td>
</tr>
<tr>
    <td>Контент-менеджер</td>
    <td><select name="manager" id="manager" class="select" >
            <option value="">-- выберите Контент-менеджера --</option>
            <?
            $result = mysql_query("SELECT * FROM manager ORDER BY Name");
            while ($row = mysql_fetch_array($result)) {

                echo '<option value="' . $row['idManager'] . '">' . $row['Name'] . '
               </option>
                ';
                '<input type= "hidden" value ="' . $row['manager_name'] . '" name = "manager_name" >';
            }
            ?>
        </select></td>
    <td><input type = "submit" name = "add_manager" value = "Добавить" id="admin"></td>
    <td><input type = "submit" name = "delete" value = "Удалить" id="admin"
               onClick="confirm('Вы уверены, что хотите удалить данные?')"></td>
    <td><input type = "submit" name = "edit_manager" value = "Редактировать" id="admin"></td>
</tr>
        <tr>
            <td colspan="5">Ограничение на ввод даты на форме </td>
            </tr>
        <tr>
            <td colspan="1">Выберите дату доступную для ввода</td>
            <td> Текущая доступная дата ввода <?
                $date = mysql_query("Select  DATE_FORMAT(Date,'%Y-%m')  as Date from date ORDER BY idDate DESC LIMIT 1");
                $d = mysql_fetch_assoc($date);
                $id_date = $d['Date'];
                echo  $id_date;?></td>
            <td colspan="2">
                <input name="date" id="monthToday" type="text" class=" form-control"
                       value="" oninput="Ftest (this)"
                       onpropertychange="if ('v' == '\\v' &&
                        parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>

            <td><input type = "submit" name = "save_date" value = "Сохранить" id="admin"
                       onClick="confirm('Вы уверены, что хотите  внести огрничения по дате?')"></td>
        </tr>
        <tr></tr>
</table></form>
</body>
</html>