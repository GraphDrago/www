


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Форма отчета о работе Центра консультационной поддержки</title>
    <link href="css/menu.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery-1.7.1.min.js" type="text/javascript" charset="utf-8"></script>
    <link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
    <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="bootstrap-datepicker-master/js/locales/bootstrap-datepicker.ru.js" type="text/javascript" charset="UTF-8"></script>
    <script type="text/javascript" src="jquery.form.js"></script>
    <!--    <script type="text/javascript" src="http://scriptjava.net/source/scriptjava/scriptjava.js"></script>-->
    <script type="text/javascript">




        var formValidators = function(){
            var is_valid = true;
            $('[type=text]').each(function() {
                var CountCallWithOpening = $('#CountCallWithOpening').val();
                var CountCallCurrentMonth = $('#CountCallCurrentMonth').val();
                var CountPeopleWIthOpening = $('#CountPeopleWIthOpening').val();
                var CountPeopleCurrentMonth = $('#CountPeopleCurrentMonth').val();
                var CountPresentationWithOpening = $('#CountPresentationWithOpening').val();
                var CountPresentationCurrentMonth = $('#CountPresentationCurrentMonth').val();
                var CountLettersWithOpening = $('#CountLettersWithOpening').val();
                var CountLettersCurrentMonth = $('#CountLettersCurrentMonth').val();
                if(CountCallWithOpening < CountCallCurrentMonth){
                    $('#CountCallCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(CountPeopleWIthOpening < CountPeopleCurrentMonth){
                    $('#CountPeopleCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(CountPresentationWithOpening < CountPresentationCurrentMonth){
                    $('#CountPresentationCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(CountLettersWithOpening < CountLettersCurrentMonth){
                    $('#CountLettersCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(!$(this).val().length) {
                    event.preventDefault();
                    $(this).css('border', '1px solid red');
                    is_valid = false;
                }else{
                    $('[type=text]').blur(function() {
                        if($(this).val().length == 0) {
                            $(this).css('border', '1px solid red');
                            is_valid = false;
                        }
                    });
                    $('[type=text]').focus(function() {
                        $(this).css('border', '1px solid #8699a4');
                    });
                }
            });
            return is_valid;
        };
        function SendFile(result_id,formreg,url) {

            $('#' + formreg).off('submit').on('submit', function(e) {
                e.preventDefault(); // prevent native submit
                $(this).ajaxSubmit({
                    url : url,
                    dataType: 'json',
                    success: function (data) {
                        if(!data.add) {
                            $('#' + result_id).html(data.response);

                        } else {
                            // history.back();
                            $('#' + result_id).html(data.add);

                        }



                    }
                });
            });
            if (formValidators() && confirm('Вы уверены, что хотите сохранить данные?') ) {
                $('#' + formreg).submit();



            }
        }
    </script>
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
            var ovrl = ov.replace (/\d*\.?\d*/, '').length; this.ST = true;
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

<body >
<div style="width:100px;height:76px; float:right;right:60px;top:15px; z-index: 1000;  background-image: url(img/logo.png); background-repeat: no-repeat">

</div>
<div style="padding-top:20px;" align="center">

    <div class = "heading" >Внесение данных о работе Центра консультационной поддержки
        <a href="index.php"> <img src="img/logout.jpg" ></a>
    </div>
</div>

<form class="formreg"  name = "formreg" id = "formreg" action="save.php" method="post" enctype="multipart/form-data" onkeypress="if(event.keyCode == 13) return false;">

    <table align="center">
        <tr> <label class = "txt"><td>период к которому относятся вводимые данные</td>
                <td><input tabindex="24" name="date" id="monthToday" type="text" class="fld form-control" value="" oninput="Ftest (this)"
                           onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
            </label></tr>
        <tr>

            <label class = "txt"><td width = "200px">наименование филиала</td>
                <td width = "400px"> <input  type = "text" name="branch" class="fld" id="branch"  style = "background-color:white;" value = "Московский РЦ"/></td>
            </label></tr>
        <tr>
            <label class = "txt"><td>федеральный округ</td>
                <td>
                    <input type = "text" name="district" class="fld" id="district" disabled="disabled" style = "background-color:white;" value = "Центральный Федеральный Округ"/></td>
            </label></tr>
        <tr>
            <td> <label class = "title">информация о ЦКП</label></td></tr>
        <tr>
            <label class = "txt"><td>название ЦКП</td>
                <td><select tabindex="1" name="ckp" id="ckp" type="text" class="fld"  style="height: 28px">
                        <option value=\'\'>-- выберите ЦКП --</option>
                        <option data-city="Москва" data-address="Москва, ул. Академика Королёва, д.15 корп.2 " data-contact="Елена Платонова"
                                data-email="ckp_moskva@rtrn.ru " data-openingHours="пн-пт 9.00-20.00, сб 10.00-17.00 " data-phone="+7(495)926-6161 "
                                data-CountPeopleWIthOpening="" value="64" >ЦКП-Москва</option>                </select></td>
            </label></tr>
        <tr>
            <label class = "txt"><td>город</td>
                <td><input tabindex="2" name="city" id="city" type="text" class="fld ckp" value=""  ></td></label></tr>
        <tr>
            <label class = "txt"><td>адрес</td>
                <td> <input tabindex="3" name="address" id="address" type="text" class="fld"
                            value=""></td></label></tr>
        <tr>
            <label class = "txt"><td>контактное лицо</td>
                <td> <input tabindex="4" name="contact" id="contact" type="text" class="fld ckp" value="" ></td></label></tr>
        <tr> <label class = "txt"><td>e-mail</td>
                <td><input tabindex="5" name="email" id="email" type="text" class="fld ckp" value=""  ></td></label></tr>
        <tr> <label class = "txt"><td>время работы</td>
                <td><input tabindex="6" name="openingHours" id="openingHours" type="text" class="fld"
                           value=""></td></label></tr>
        <tr> <label class = "txt"> <td>телефон</td>
                <td><input type="text" tabindex="7" name="phone" id="phone"  class="fld"
                           value=""></td></label></tr>



        <tr><td><label class = "title">к-во посетителей ЦКП</label></td></tr>
        <tr><label class = "txt"><td>за весь период работы с момента открытия</td>
                <td> <input tabindex="10" name="CountPeopleWIthOpening" id="CountPeopleWIthOpening" type="text"
                            class="fld count" value=""  maxlength="6"oninput="Ftest (this)"
                            onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
            </label></tr>
        <tr> <label class = "txt"><td> в т ч текущем месяце</td>
                <td><input tabindex="11" name="CountPeopleCurrentMonth" id="CountPeopleCurrentMonth" type="text" class="fld count"
                           value=""  maxlength="6" oninput="Ftest (this)"
                           onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
            </label></tr>


        <tr> <td> <label class = "txt">вложения:</label></td>
            <td> <p> <div class="file-load-block" style="margin-left: -20px;">
                    <input tabindex="24" type="file" value="" id="file" name="userFile[]"  multiple="multiple"  />
                    <div class="fileLoad"  >
                        <button>загрузить файл</button>
                    </div>
                </div></p></td>
        </tr>
        <tr>
            <td></td><td>
                <p>Если вы хотите загрузить несколько приложений - используйте<br>клавишу Сtrl.
                    Ограничение по объему приложения – 5 Мб</p>
                <ol id="log"></ol>
                <ol id="lg"></ol>
            </td>
        </tr>
        <tr><td colspan="2" align="center">

                <input type="hidden" value="" name="count" id = "count"/>
                <input type="reset" value="Очистить" name="reset" id="reset" onclick="confirm('Вы уверены, что хотите очистить форму?')"/>
                <input type="button" value="Сохранить" name="button" id="button"
                       onclick="SendFile('status', 'formreg', 'save.php') " />
            </td>
        </tr>
    </table>
</form>
<div id = "status" style = "padding-top: 30px;" align="center"></div>
</body>

<script>
    document.getElementById('file').addEventListener('change', handleFileSelect, false);
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        var output = [];
        for (var i = 0, f; f = files[i]; i++) {
            output.push('<li>'+ f.name + '</li>');
        }
        $('#log').append('Выбранные файлы:'+output.join(''));
        $('#count').val(i);
    }
    $(function() {
        $("#file").click();
    });
    /* Добавляем новый класс кнопке если инпут файл получил фокус */
    $('#file').hover(function(){
        $(this).parent().find('button').addClass('button-hover');
    }, function(){
        $(this).parent().find('button').removeClass('button-hover');
    });
    $('select#ckp').change(function(){
        var option = $(this).children(':selected');
        var city = option.attr('data-city');
        $('#city').val(city);
        var address = option.attr('data-address');
        $('#address').val(address);
        var contact = option.attr('data-contact');
        $('#contact').val(contact);
        var email = option.attr('data-email');
        $('#email').val(email);
        var openingHours = option.attr('data-openingHours');
        $('#openingHours').val(openingHours);
        var phone = option.attr('data-phone');
        $('#phone').val(phone);
        var CountPeopleWIthOpening = option.attr('data-CountPeopleWIthOpening');
        $('#CountPeopleWIthOpening').val(CountPeopleWIthOpening);

    });


    $('select#ckp').change(function (){
        var id_elem = $(this).val();
        $.ajax({
            type: "POST",
            url: "form.php",
            data: {'idckp': id_elem},
            success: function(result)
            {$("#status").text(result);},
            error: function()
            {alert('Problem whith POST!');}});});

</script>
</html>

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

    <div style="width:100px;height:80px; float:right;right:40px;top:15px; z-index: 1000;  background-image: url(img/logo.png); background-repeat: no-repeat">

    </div>

    <div align="center" style = "padding-top: 20px; ">
        <div class = "heading"> Интерфейс раздачи ролей
            <a href="index.php"> <img src="img/logout.jpg" ></a>
        </div>

        <ul class="menu">
            <li><a href="index.php">Интерфейс раздачи ролей</a></li>
            <li ><a href="records.php">Форма ввода</a></li>
            <li><a href="report.php">Итоговая форма</a></li>
            <li class="current">Администрирование</li>
        </ul>

    </div>
    <form action = ''>
        <table class = "sortable">
            <tr>
                <td></td>
            </tr>
        </table>

    </form>
    <form>
        <p align="center"><input type="submit" value="Показать"></p>
        <table class = 'admin_edit' align="center" border="1" cellspacing='0' cellpadding='0'>
            <tr>
                <td width="100px">Филиал</td>
                <td colspan="6">ЦКП</td>
                <td>Менеджер</td>
                <td>E-Mail</td>
            </tr>
            <tr >
                <td rowspan="3">
                    <select name="branch" id="branch" class="">
                        <option value="">-- выберите филиал --</option>
                        <?php
                        $result = mysql_query("SELECT * FROM branch ORDER BY Name")
                        or die ("<b>Query failed:</b> " . mysql_error());
                        while ($row = mysql_fetch_array($result)) {
                            echo "<option   data-branch ='" . $row['idBranch'] . "'
                                        data-district_id = '" . $row['District_idDistrict'] . "'
                                        value='".$row['idBranch']."'>
                                        " . $row['Name'] . "</option>";
                        }
                        ?>
                    </select></td>
                <td colspan="6">
                    <select name="ckp" id="ckp" class="">
                        <option value="">-- выберите ЦКП --</option>
                        <?php
                        $result = mysql_query("SELECT * FROM ccs ORDER BY Name")
                        or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.");
                        while ($row = mysql_fetch_array($result)) {

                            echo '<option data-city="'.$row['City'].'" data-address="'.$row['Address'].'" data-contact="'.$row['ContactPerson'].'"
                                            data-email="'.$row['E-Mail'].'" data-openingHours="'.$row['OpeningHours'].'" data-phone="'.$row['Phone'].'"
                                            data-CountPeopleWIthOpening="'.$item['CountPeopleWIthOpening'].'" value="'.$row['idCCS'].'" >ЦКП-'.$row['City'].'</option>';
                        }?>
                    </select></td>
                <td rowspan="3">
                    <select name="manager" id="manager" class="">
                        <option value="">-- выберите менеджера --</option>
                        <?php

                        $result = mysql_query("SELECT * FROM manager ORDER BY Name")
                        or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.");
                        while ($row = mysql_fetch_array($result)) {

                            echo '<option data-manager ="' . $row['Branch_idBranch'] . '" value="' . $row['idManager'] . '">' . $row['Name'] . '</option>';
                        }
                        ?>

                    </select></td>
                <td rowspan="3"></td>
            </tr>
            <tr> <td>Город</td>
                <td>Адрес</td>
                <td>E-Mail ЦКП</td>
                <td>Контактное лицо</td>
                <td>Время работы</td>
                <td>Телефон</td></tr>

            <tr>
                <td><input tabindex="2" name="city" id="city" type="text" class="ckp" value=""  ></td>
                <td><input tabindex="3" name="address" id="address" type="text" class="" value=""></td>
                <td><input tabindex="4" name="contact" id="contact" type="text" class="ckp" value="" ></td>
                <td><input tabindex="5" name="email" id="email" type="text" class="ckp" value=""  ></td>
                <td><input tabindex="6" name="openingHours" id="openingHours" type="text" class="" value=""></td>
                <td><input type="text" tabindex="7" name="phone" id="phone"  class="" value=""></td>
            </tr>
        </table>
    </form>
    <?php
    $result =  mysql_query ("SELECT idManager,m.Name as manager, EMail, b.Name as branch,m.Login
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict")
    or die ("<p align='center'><b>Ошибка в обращении к БД.Невозможно получить список пользователей!<br>
Обновите страницу или обратитесь к администратору</b> </p>");
    if(($num_rows =  mysql_num_rows($result)) != 0) {
        $table ="
        <form action='' id='manager' name='manager' class = 'manager' method='post' enctype='multipart/form-data' style='margin:20px;'>

        <table width='900px' class='sortable' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center'>
             <thead>";
        $table.='<tr>
                <th >Имя</th>
                <th >Логин</th>
                <th >E-Mail</th>
                <th >Филиал</th>
            </tr> </thead><tbody>';
        while($myrow = mysql_fetch_array($result)){
            $table.='<tr> ';
            $table.='<td name="Name_'.$myrow['idManager'].'">'.$myrow['manager'].'</td>';
            $table.='<td name="login_'.$myrow['idManager'].'">'.$myrow['Login'].'</td>';
            $table.='<td name="email_'.$myrow['idManager'].'">'.$myrow['EMail'].'</td>';
            $table.='<td name="branch_'.$myrow['idManager'].'">'.$myrow['branch'].'</td>';
            $table.='<td><a href="rename.php?id='.$myrow['idManager'].'">Ред.</a></td>';
            $table.= '<td><a href="delete.php?id='.$myrow['idManager'].'"
                 onClick="return window.confirm(\'Вы уверены, что хотите удалить пользователя?\')">Удал.</a></td>';
            $table.='</tr>';
        }
        $table.='</tbody> <tfoot></tfoot></table>
            </form>
            	<p align="center"><input type="submit" value="Добавить"></p>';
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
if($_POST['delete'] ){
    $idBranch = $_POST['branch'];
    $idckp = $_POST['ckp'];
    $idManager = $_POST['manager'];
    if($idBranch != ''){
        $fact = mysql_query("DELETE FROM fact WHERE idBranch_Branch = $idBranch")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        $manager = mysql_query("DELETE FROM manager WHERE idBranch_Branch = $idBranch")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        $ccs = mysql_query("DELETE FROM ccs WHERE idBranch_Branch = $idBranch")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        $branch = mysql_query("DELETE FROM branch WHERE idBranch = $idBranch")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        /* Выполняем запрос. Если произойдет ошибка - вывести ее. */
        if ($fact == 'true' && $manager == 'true' && $ccs == 'true' && $branch == 'true') {
            echo '<p align="center">Данные успешно удалены.</p>';
        } else {
            echo '<p align="center">Неудалось выполнить запрос.</p>';
        }
        //header("Location: data.php");
    }
    if($idckp !=''){
        $fact_ckp = mysql_query("DELETE FROM fact WHERE idCCS_CCS = $idckp")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        $branch_ckp = mysql_query("DELETE FROM ccs WHERE idCCS = $idckp")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');
        $branch_ckp = mysql_query("DELETE FROM branch WHERE idCCS_CCS = $idckp")
        or die ('<p align="center">Ошибка при обращении к БД. Невозможно выполнить запрос.</p>');

    }
}elseif($_POST['add_branch']){
    echo '<form action=" " method="post" name = "addBranch">
        <table align="center">
         <tr><td>Округ</td></tr>
        <tr><td><select name="district" id="district" type="text"  class = "add">
                        <option value="">-- выберите округ --</option>';
    $result = mysql_query("SELECT * FROM district ORDER BY Name")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    while ($row = mysql_fetch_array($result)) {
        echo "<option data-idbranch = data-id_district = '" . $row['idDistrict'] . "' value='" . $row['idDistrict'] . "' >" . $row['Name'] . "</option>";
    }

    echo '</select></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="" name = "branch_add" class = "add"></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="" name = "ckp_add" class = "add"  ></td></tr>
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="" name = "manager_add" class = "add"></td></tr>
        <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin" >
        <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>

        </table>
        </form>';
}
elseif($_POST['add_ckp']){
    echo '<form action=" " method="post" name = "addCCS">
        <table align="center">
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="" name = "branch_add" class = "add" ></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="" name = "ckp_add" class = "add" ></td></tr>
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="" name = "manager_add" class = "add" ></td></tr>
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
         <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin">
        <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>
        </table>
        </form>';
} else

    elseif($_POST['edit_branch']){
    $idBranch = $_POST['branch'];
    $edit_branch = mysql_query("select b.Name as branch,d.Name as district,c.Name as ccs, m.Name as manager from branch b
INNER JOIN district d ON b.District_idDistrict = d.idDistrict
 INNER JOIN manager m ON m.Branch_idBranch = b.idBranch
 INNER JOIN ccs c ON c.Branch_idBranch = b.idBranch
 where idBranch = $idBranch")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ".mysql_error());
    $data = mysql_fetch_assoc($edit_branch);
    echo '<form action=" " method="post" name = "editBranch">
        <table align="center">
         <tr><td>Округ</td></tr>
        <tr><td><input type="text" value="'.$data['district'].'" name = "district_add" class = "add"></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="'.$data['branch'].'" name = "branch_add" class = "add"></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="'.$data['ccs'].'" name = "ckp_add" class = "add"  ></td></tr>
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="'.$data['manager'].'" name = "manager_add" class = "add"></td></tr>
        <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin" >
        <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>

        </table>
        </form>';
}elseif($_POST['edit_ckp']) {
    $idckp = $_POST['ckp'];
    $edit_ckp = mysql_query("select b.Name as branch,c.Name as ccs, m.Name as manager, City,
 Address,ContactPerson,`E-Mail`, Phone,OpeningHours as emailfrom ccs c
INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
INNER JOIN district d ON b.District_idDistrict = d.idDistrict
INNER JOIN manager m ON m.Branch_idBranch = b.idBranch
 where idCCS = $idckp")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    $data = mysql_fetch_assoc($edit_ckp);
    echo '<form action=" " method="post" name = "editCCS">
        <table align="center">
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="'.$data['branch'].'" name = "branch_edit" class = "add" ></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="'.$data['ccs'].'" name = "ckp_add" class = "add" ></td></tr>
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="'.$data['manager'].'" name = "manager_add" class = "add" ></td></tr>
        <tr><td>Город</td></tr>
        <tr><td><input type="text" value="'.$data['City'].'" name = "city_add" class = "add"></td></tr>
        <tr><td>Адрес</td></tr>
        <tr><td><input type="text" value="'.$data['Address'].'" name = "address_add" class = "add"></td></tr>
        <tr><td>Контактное лицо</td></tr>
        <tr><td><input type="text" value="'.$data['ContactPerson'].'" name = "contact_add" class = "add"></td></tr>
        <tr><td>E-Mail</td></tr>
        <tr><td><input type="text" value="'.$data['email'].'" name = "email_add" class = "add"></td></tr>
        <tr><td>Время работы</td></tr>
        <tr><td><input type="text" value="'.$data['OpeningHours'].'" name = "hour_add" class = "add"></td></tr>
        <tr><td>Телефон</td></tr>
        <tr><td><input type="text" value="'.$data['Phone'].'" name = "phone_add" class = "add"></td></tr>
         <tr><td><p align="center"><input type="submit" value="Сохранить" name = "save_branch" id = "admin">
        <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>
        </table>
        </form>';
}elseif($_POST['edit_manager']) {
    echo '<form action=" " method="post" name = "addManager">
        <table align="center">
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="" name = "manager_add" class = "add" ></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><input type="text" value="" name = "branch_add" class = "add" ></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="" name = "ckp_add" class = "add" ></td></tr>
         <tr><td><p align="center"><input type="submit" value="Сохранить" id = "admin">
         <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>
        </table>
        </form>';
}

if(CountCallWithOpening < CountCallCurrentMonth){
    $('#CountCallCurrentMonth').css('border', '1px solid red');
    is_valid = false;
}
                if(CountPeopleWIthOpening < CountPeopleCurrentMonth){
                    $('#CountPeopleCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(CountPresentationWithOpening < CountPresentationCurrentMonth){
                    $('#CountPresentationCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }
                if(CountLettersWithOpening < CountLettersCurrentMonth){
                    $('#CountLettersCurrentMonth').css('border', '1px solid red');
                    is_valid = false;
                }



if($_POST['add_manager']){
    echo '<form action="manager.php" method="post" name = "addManager" enctype="multipart/form-data">
        <table align="center">
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text"  name = "managerAdd" class = "add" value=" " ></td></tr>
        <tr><td>Филиал</td></tr>
        <tr><td><select name="branchAdd" id="branchAdd" type="text"  class = "add">
                        <option value="">-- выберите округ --</option>';
    $result = mysql_query("SELECT * FROM branch ORDER BY Name")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    while ($row = mysql_fetch_array($result)) {
        echo "<option  value='" . $row['idBranch'] . "' >" . $row['Name'] . "</option>";
    }
    echo '</select></td></tr>
        <tr><td>ЦКП</td></tr>
        <tr><td><select name="branch_add" id="ckp_add" type="text"  class = "add">
                        <option value="">-- выберите округ --</option>';
    $result = mysql_query("SELECT * FROM ccs ORDER BY Name")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    while ($row = mysql_fetch_array($result)) {
        echo "<option  value='" . $row['idCCS'] . "' >" . $row['Name'] . "</option>";
    }
    echo '</select></td></tr>
         <tr><td>Логин</td></tr>
        <tr><td><input type="text" value="" name = "login_add" class = "add" ></td></tr>
         <tr><td>Email</td></tr>
        <tr><td><input type="text" value="" name = "email_add" class = "add" ></td></tr>
         <tr><td><p align="center"><input type="submit" name = "manager_button" value="Сохранить" id = "admin"
          onclick="AjaxFormRequest(\'results\', \'addManager\', \'manager.php\');">
         <button type="button" onClick="history.back();" id = "admin">Отменить</button></p></td></tr>
        </table>
        </form>';
}

 <tr><td>ЦКП</td></tr>
        <tr><td><input type="text" value="" name = "ckp_add" class = "add"  ></td></tr>
        <tr><td>Менеджер</td></tr>
        <tr><td><input type="text" value="" name = "manager_add" class = "add"></td></tr>

<tr><td>ЦКП</td></tr>
        <tr><td><select name="branch_add" id="ckp_add" type="text"  class = "add">
                        <option value="">-- выберите ЦКП --</option>';
    $result = mysql_query("SELECT * FROM ccs ORDER BY Name")
    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
    while ($row = mysql_fetch_array($result)) {
        echo "<option  value='" . $row['idCCS'] . "' >" . $row['Name'] . "</option>";
    }
    echo '</select></td></tr>