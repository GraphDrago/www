<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
if($_SESSION['Role'] !='system Administrator' ) {
    header('Location: index.html');
}
$Manager_name =  $_SESSION['Name_Manager'];
$CreateDate = date('Y-m-d H-i-s');
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
               $("form").submit(function(event) {
                    event.preventDefault();
                    $.ajax({
                        url:     url,
                        type:     "POST",
                        dataType: "html",
                        data: jQuery("#"+formMain).serialize(),
                        success: function(response) {
                            document.getElementById(result_id).innerHTML = response;
                        },
                        error: function(jqXHR, exception) {
                            if (jqXHR.status === 0) {
                                alert('Not connect.\n Verify Network.');
                            } else if (jqXHR.status == 404) {
                                alert('Requested page not found. [404]');
                            } else if (jqXHR.status == 500) {
                                alert('Internal Server Error [500].');
                            } else if (exception === 'parsererror') {
                                alert('Requested JSON parse failed.');
                            } else if (exception === 'timeout') {
                                alert('Time out error.');
                            } else if (exception === 'abort') {
                                alert('Ajax request aborted.');
                            } else {
                                alert('Uncaught Error.\n' + jqXHR.responseText);
                            }
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
        <div class = "name_manager"><?echo$_SESSION['Name_Users'];?></div>
    </div>

    <ul class="menu">
        <li><a href="index.php">Интерфейс раздачи ролей</a></li>
        <li><a href="report.php">Итоговая форма</a> </li>
        <li class="current"><a href="action.php"> Администрирование</a></li>
        <li><a href="changes.php">Учет изменений</a></li>
    </ul>

</div>

<?
$Manager_name =  $_SESSION['Name_Manager'];
$CreateDate = date('Y-m-d H-i-s');
if($_POST['add_branch']){
    echo '<form action=" " method="post" name = "addBranch" id="addBranch">
        <table align="center">
         <tr><td>Округ</td></tr>
        <tr><td><select name="district" id="district" type="text"  class = "add">
                        <option value="">-- выберите округ --</option>';
    $result = mysql_query("SELECT * FROM district ORDER BY Name");
    while ($row = mysql_fetch_array($result)) {
        echo "<option value='" . $row['idDistrict'] . "' >" . $row['Name'] . "</option>";
    }

    echo '</select></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="" name = "branch_add" class = "add"></td></tr>

        <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin" name="branch_save"
         onclick="AjaxFormRequest(\'results\', \'addBranch\', \'manager.php\');">
        <a href="action.php"> <button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>

        </table>
        </form>';
}elseif($_POST['add_district']){
    echo '<form action=" " method="post" name = "addDistrict" id="addDistrict">
        <table align="center">
        <tr><td>Округ</td></tr>
        <tr><td><input type="text" value="" name = "district_add_name" class = "add" ></td></tr>
         <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin"
         onclick="AjaxFormRequest(\'results\', \'addDistrict\', \'manager.php\');">
        <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
}elseif($_POST['add_ckp']){
   // $r = mysql_query("SELECT * FROM district ORDER BY Name");
    echo '<form action=" " method="post" name = "addCCS" id="addCCS">
        <table align="center">
        <tr><td>Округ</td></tr>
         <tr><td><input type="text" value="" readonly = "readonly" name = "district_add" class = "add" id = "district_add" ></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><select name="branch_add_ckp" id="branch" class="select" >
                        <option value="">-- выберите филиал --</option>';
                        $result = mysql_query("SELECT b.Name, b.idBranch,d.Name as dis FROM branch b
                      INNER JOIN district d ON b.District_idDistrict = d.idDistrict ORDER BY Name");
                        while ($row = mysql_fetch_array($result)) {
                            echo "<option data-district = '".$row['dis']."' value='".$row['idBranch']."'>
                                        " . $row['Name'] . "</option>";
                        }
                    echo '</select></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="" name = "ckp_add" class = "add" ></td></tr>
        <tr><td>Город</td></tr>
        <tr><td><input type="text" value="" name = "city_add" class = "add"></td></tr>
        <tr><td>Адрес</td></tr>
        <tr><td><input type="text" value="" name = "address_add" class = "add"></td></tr>
        <tr><td>Контактное лицо</td></tr>
        <tr><td><input type="text" value="" name = "contact_add" class = "add"></td></tr>
        <tr><td>E-Mail</td></tr>
        <tr><td><input type="text" value="" name = "email_add" class = "add"></td></tr>
        <tr><td>Время работы</td></tr>
        <tr><td><input type="text" value="" name = "hour_add" class = "add"></td></tr>
        <tr><td>Телефон</td></tr>
        <tr><td><input type="text" value="" name = "phone_add" class = "add"></td></tr>
         <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin"
         onclick="AjaxFormRequest(\'results\', \'addCCS\', \'manager.php\');">
        <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
} elseif($_POST['add_manager']){
    echo '<form action="" method="post" name = "addManager" id="addManager" enctype="multipart/form-data">
        <table align="center">
        <tr><td>Контент-менеджер</td></tr>
        <tr><td><input type="text"  name = "managerAdd" class = "add" value=" " ></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><select name="branchAdd" id="branchAdd" type="text"  class = "add">
                        <option value="">-- выберите филиал --</option>';
    $result = mysql_query("SELECT * FROM branch ORDER BY Name");
    while ($row = mysql_fetch_array($result)) {
        echo "<option  value='" . $row['idBranch'] . "' >" . $row['Name'] . "</option>";
    }
    echo '</select></td></tr>

         <tr><td>Логин</td></tr>
        <tr><td><input type="text" value="" name = "login_add" class = "add" ></td></tr>
         <tr><td>Email</td></tr>
        <tr><td><input type="text" value="" name = "email_add" class = "add" ></td></tr>
         <tr><td><p align="center"><input type="submit" name = "manager_button" value="Сохранить" id = "admin"
          onclick="AjaxFormRequest(\'results\', \'addManager\', \'manager.php\');">
         <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
}elseif($_POST['edit_branch']) {
    $idBranch = $_POST['branch'];
    if($idBranch == ''){
        include 'table.php';
        echo '<p align="center">Выберите филиал данные которого нужно редактировать</p>';
    }else{
    $edit_branch = mysql_query("select b.Name as branch,d.Name as district,c.Name as ccs, m.Name as manager,b.idBranch,d.idDistrict  from branch b
INNER JOIN district d ON b.District_idDistrict = d.idDistrict
 INNER JOIN manager m ON m.Branch_idBranch = b.idBranch
 INNER JOIN ccs c ON c.Branch_idBranch = b.idBranch
 where idBranch = $idBranch");
    $data = mysql_fetch_assoc($edit_branch);
    echo '<form action=" " method="post" name = "editBranch" id = "editBranch">
        <table align="center">
         <tr><td>Округ</td></tr>
         <tr><td><select name="district_edit_id" id="district_add" class="select" >
         <option value="'.$data['idDistrict'].'" selected>'.$data['district'].'</option>
                    <option value="">-- выберите округ --</option>';

                    $result = mysql_query("SELECT * FROM district ORDER BY Name");
                    while ($row = mysql_fetch_array($result)) {
                        echo "<option value='".$row['idDistrict']."'>
                                        " . $row['Name'] . "</option>";
                    }

                echo'</select></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="' . $data['branch'] . '" name = "branch_edit_name" class = "add">
        <input type = "hidden" name = "idbranch" value = "' . $data['idBranch'] . '"></td></tr>
        <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin"
        onclick="AjaxFormRequest(\'results\', \'editBranch\', \'manager.php\');">
        <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>

        </table>
        </form>';
}
}elseif($_POST['edit_ckp']) {
    $idckp = $_POST['ckp'];
    if($idckp == ''){
        include 'table.php';
        echo '<p align="center">Выберите ЦКП данные которого нужно редактировать</p>';
    }else{
    $edit_ckp = mysql_query("select b.Name as branch,c.Name as ccs, m.Name as manager, City,
 Address,ContactPerson,`E-Mail` as email, Phone,OpeningHours,idCCS,d.Name as district,b.idBranch
 from ccs c
INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
INNER JOIN district d ON b.District_idDistrict = d.idDistrict
INNER JOIN manager m ON m.Branch_idBranch = b.idBranch
 where idCCS = $idckp");
    $data = mysql_fetch_assoc($edit_ckp);
    echo '<form action=" " method="post" name = "editCCS" id = "editCCS">
        <table align="center">
         <tr><td>Округ</td></tr>
        <tr><td><input type="text" value="' . $data['district'] . '" readonly = "readonly" name = "district_edit" class = "add" ></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><select name="branch_edit" id="branch_edit" type="text"  class = "add">
                        <option value="' . $data['idBranch'] . '">' . $data['branch'] . '</option>
                        <option value="">-- выберите филиал --</option>';
        $result = mysql_query("SELECT * FROM branch ORDER BY Name");
        while ($row = mysql_fetch_array($result)) {
            echo "<option  value='" . $row['idBranch'] . "' >" . $row['Name'] . "</option>";
        }
        echo '</select></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="' . $data['ccs'] . '" name = "ckp_add" class = "add" ></td></tr>
        <tr><td>Город</td></tr>
        <tr><td><input type="text" value="' . $data['City'] . '" name = "city_add" class = "add"></td></tr>
        <tr><td>Адрес</td></tr>
        <tr><td><input type="text" value="' . $data['Address'] . '" name = "address_add" class = "add"></td></tr>
        <tr><td>Контактное лицо</td></tr>
        <tr><td><input type="text" value="' . $data['ContactPerson'] . '" name = "contact_add" class = "add"></td></tr>
        <tr><td>E-Mail</td></tr>
        <tr><td><input type="text" value="' . $data['email'] . '" name = "email_add" class = "add"></td></tr>
        <tr><td>Время работы</td></tr>
        <tr><td><input type="text" value="' . $data['OpeningHours'] . '" name = "hour_add" class = "add"></td></tr>
        <tr><td>Телефон</td></tr>
        <tr><td><input type="text" value="' . $data['Phone'] . '" name = "phone_add" class = "add"></td></tr>
         <tr><td><p align="center"> <input type = "hidden" name = "idckp" value = "' . $data['idCCS'] . '">
         <input type="submit" value="Сохранить" name = "save_branch" id = "admin"
         onclick="AjaxFormRequest(\'results\', \'editCCS\', \'manager.php\');">
       <a href="action.php"> <button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
}
}elseif($_POST['edit_manager']) {
    $id = $_POST['manager'];
    if($id == ''){
        include 'table.php';
        echo '<p align="center">Выберите контент-менеджера данные которого нужно редактировать</p>';
    }else{
        $res =  mysql_query ("SELECT b.Name as branch, m.Login, m.idManager,b.idBranch, m.Name as manager,
d.Name as ckp,Login, EMail
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN ccs d ON d.Branch_idBranch = b.idBranch
                                          WHERE idManager = '".$id."' LIMIT 1");
        $data = mysql_fetch_assoc($res);
        echo '<form action=" " method="post" name = "addManager" id = "addManager">
        <table align="center">
        <tr><td>Контент-менеджер</td></tr>
        <tr><td><input type="text" value="'.$data['manager'].'" name = "manager_add" class = "add" ></td></tr>
        <tr><td>Филиал</td></tr>
         <tr><td><select name="branch_add" id="branch_add" type="text"  class = "add">
                        <option value="'.$data['idBranch'].'" >'.$data['branch'].'</option>
                        <option value="">-- выберите филиал --</option>';
        $result = mysql_query("SELECT * FROM branch ORDER BY Name");
        while ($row = mysql_fetch_array($result)) {
            echo "<option  value='" . $row['idBranch'] . "' >" . $row['Name'] . "</option>";
        }
        echo '</select></td></tr>
        <tr><td>Логин</td></tr>
        <tr><td><input type="text" value="'.$data['Login'].'" name = "login_add" class = "add" ></td></tr>
        <tr><td>E-mail</td></tr>
        <tr><td><input type="text" value="'.$data['EMail'].'" name = "email_add" class = "add" ></td></tr>
         <tr><td><p align="center"> <input type = "hidden" name = "idmanager" value = "'.$data['idManager'].'">
        <input type="submit" value="Сохранить" id = "admin"
         onclick="AjaxFormRequest(\'results\', \'addManager\', \'manager.php\');">
        <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
    }
}elseif($_POST['edit_district']) {
    $id = $_POST['district'];
    //echo $id;
    if($id == ''){
        include 'table.php';
        echo '<p align="center">Выберите округ данные которого нужно редактировать</p>';
    }else{
        $res =  mysql_query ("SELECT  d.Name,idDistrict
                                          FROM district d
                                          WHERE idDistrict = '".$id."' LIMIT 1");
        $data = mysql_fetch_assoc($res);
       // echo $data['idDistrict'];
        echo '<form action=" " method="post" name = "addDistrict" id = "addDistrict">
        <table align="center">
        <tr><td>Округ</td></tr>
        <tr><td><input type="text" value="'.$data['Name'].'" name = "district_add_update" class = "add" ></td></tr>
         <tr><td><p align="center"> <input type = "hidden" name = "iddistrict" value = "'.$data['idDistrict'].'">
        <input type="submit" value="Сохранить" id = "admin"
         onclick="AjaxFormRequest(\'results\', \'addDistrict\', \'manager.php\');">
        <a href="action.php"><button type="button" id = "admin">Вернуться назад</button></a></p></td></tr>
        </table>
        </form>';
    }
}elseif($_POST['delete'] ){
    $Manager_name =  $_SESSION['Name_Manager'];
    $CreateDate = date('Y-m-d H-i-s');
    $idBranch = $_POST['branch'];
    $idckp = $_POST['ckp'];
    $idManager = $_POST['manager'];
    $Name_manager = $_POST['manager_name'];
    $idDistrict = $_POST['district'];
    if($idBranch != ''){
        $CreateDate = date('Y-m-d H-i-s');
        $ckp = mysql_query("Select idCCS,c.Name, b.Name as branch from ccs c
INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
Where c.Branch_idBranch = '$idBranch'");
        $myrow = mysql_fetch_array($ckp);
        $ckp_name = $myrow['Name'];
        $branch_name = $myrow['branch'];
        $ckp_id = $myrow['idCCS'];

        $facts_id = mysql_query("select * FROM fact WHERE CCS_idCCS = $ckp_id");
        $myrow = mysql_fetch_array($facts_id);
        $facts = $myrow['idFacts'];

        $quer = mysql_query("DELETE FROM attachment WHERE Fact_idFacts = $facts");
        if($quer){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', ' $manager','$ckp_name', N'Таблица вложений',N'DELETE')");
        }
        while($myrow = mysql_fetch_array($ckp)){
            $fact = mysql_query("DELETE FROM fact WHERE CCS_idCCS = ".$myrow['idCCS']);
            $ckp_name = $myrow['Name'];
            if($fact){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'DELETE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                )));
            }
        }
        $manager = mysql_query("DELETE FROM manager WHERE Branch_idBranch = $idBranch");
        if($manager){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$Name_manager',N'Таблица контент-менеджера',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        $ccs = mysql_query("DELETE FROM ccs WHERE Branch_idBranch = $idBranch");
        if($ccs){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        $branch = mysql_query("DELETE FROM branch WHERE idBranch = $idBranch");
        if($branch){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$branch_name',N'Таблица Филиала',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
       /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
       if ($branch == 'true') {
           include 'table.php';
           echo '<p align="center">Данные успешно удалены.</p>';
        } else {
           include 'table.php';
            echo '<p align="center">Неудалось выполнить запрос.</p>';
        }
        //header("Location: data.php");
    }elseif($idckp !=''){
        $CreateDate = date('Y-m-d H-i-s');
        $ccs = mysql_query("select * FROM ccs WHERE idCCS = $idckp");
        $myrow = mysql_fetch_array($ckp);
        $ckp_name = $myrow['Name'];

        $facts_id = mysql_query("select * FROM fact WHERE CCS_idCCS = $idckp");
        $myrow = mysql_fetch_array($facts_id);
        $facts = $myrow['idFacts'];

        $quer = mysql_query("DELETE FROM attachment WHERE Fact_idFacts = $facts");
        if($quer){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', ' $manager','$ckp_name', N'Таблица вложений',N'DELETE')");
        }

        $fact = mysql_query("DELETE FROM fact WHERE CCS_idCCS = $idckp");
        if($fact){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        $ckp = mysql_query("DELETE FROM ccs WHERE idCCS = $idckp");
        if($ckp){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate_', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        if($ckp){
            include 'table.php';
            echo "<p align='center'>Данные успешно удалены!</p>";
        }else{
            include 'table.php';
            echo "<p align='center'>При удалении возникли ошибки. Попробуйте еще раз</p>";
        }
//        $fact_ckp = mysql_query("DELETE FROM fact WHERE idCCS_CCS = $idckp")
//        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
//        $branch_ckp = mysql_query("DELETE FROM ccs WHERE idCCS = $idckp")
//        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
//        $branch_ckp = mysql_query("DELETE FROM branch WHERE idCCS_CCS = $idckp")
//        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
    }elseif($idManager !=''){
       // echo "<script>confirm('Вы уверены, что хотите удалить данные?');</script>";
//        $fact = mysql_query("DELETE FROM fact WHERE Manager_idManager = $idManager")
//        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>'.mysql_error());
        $facts_id = mysql_query("select * FROM manager  WHERE idManager = $idManager");
        $myrow = mysql_fetch_array($facts_id);
        $manager_name = $myrow['Name'];

        $manager = mysql_query("DELETE FROM manager WHERE idManager = $idManager");
        if($manager){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$manager_name',N'Таблица контент-менеджера',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        if($manager){
            include 'table.php';
            echo "<p align='center'>Данные успешно удалены!</p>";
        }else{
            include 'table.php';
            echo "<p align='center'>При удалении возникли ошибки. Попробуйте еще раз</p>";
        }
    }elseif($idDistrict !=''){
        $CreateDate = date('Y-m-d H-i-s');
        // echo "<script>confirm('Вы уверены, что хотите удалить данные?');</script>";
        $facts_id = mysql_query("select * FROM district  WHERE idDistrict = $idDistrict");
        $myrow = mysql_fetch_array($facts_id);
        $dis_name = $myrow['Name'];

        $district = mysql_query("DELETE FROM district WHERE idDistrict = $idDistrict");
        if($manager){
            $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$dis_name',N'Таблица округа',N'DELETE')")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
            )));
        }
        if($district){
            include 'table.php';
            echo "<p align='center'>Данные успешно удалены!</p>";
        }else{
            include 'table.php';
            echo "<p align='center'>При удалении возникли ошибки. Попробуйте еще раз</p>";
        }
    }else{
        include 'table.php';
        echo '<p align="center">Вы ничего не выбрали для удаление. Выберите и попробуйте еще раз!</p>';
    }
}elseif($_POST['save_date']){

    $Date = $_POST['date'];
    $datetime = strtotime($Date);
    $month = $Date.'-'.date('t', $datetime);
    if($Date != ''){
        $q = mysql_query("INSERT INTO date (Date)
                              VALUES ('$month')") or die ("ошибка".mysql_error());;
        if ($q == "true") {
            include 'table.php';
            echo  "<p align='center'>Ограничения по дате установлены.</p>";
        } else {
            include 'table.php';
            echo "<p align='center'>Неудалось установить ограничение по дате.</p>";
        }
    }else{
        include 'table.php';
        echo '<p align="center">Вы не выбрали дату. Выберите и попробуйте еще раз!</p>';
    }
}
?>
<div id="results" align="center"></div>
</body>
<script>
    $('select#branch').change(function(){
        var district_id = $(this).children(':selected').attr('data-district');
        var    $district = $('#district_add').val(district_id);


    });
</script>
</html>