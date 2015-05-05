<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';
//$manager = $_GET['m'];
$manager_name = $_SESSION['manager_name'];
//$idBranch = $_GET['id'];
$id_branch = $_SESSION['id_branch'];
?>
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
            $(document).ready(function () {
                if ($('#hidden').val() == 'true') {
                    $('select#ckp').trigger('change');
                }
                });
            </script>
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
                                history.back();
                                //$('#' + result_id).html(data.add);

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
        <style type="text/css">

        </style>
    </head>

    <body >
    <div style="width:100px;height:80px; float:right;margin-left: 40px; z-index: 1000;position: relative">
        <img src="img/logo.png">
    </div>
    <div style="padding-top:20px;" align="center">

        <div class = "heading" style = "width: 55%" >Внесение данных о работе Центра консультационной поддержки
            <a href="logoff.php?mode=logoff"  onclick="return window.confirm('Вы уверены что хотите покаинуть страницу?');"> Выход<img src="img/logout.jpg" ></a>
        </div>
        <ul class="menu">
            <li><a href="index.php">Интерфейс раздачи ролей</a></li>
            <li class="current"><a href="records.php"> Форма ввода</a></li>
            <li><a href="report.php">Итоговая форма</a></li>
            <li><a href="action.php">Администрирование</a></li>
        </ul>
    </div>

    <form class="formreg"  name = "formreg" id = "formreg" action="save.php"
          method="post" enctype="multipart/form-data" onkeypress="if(event.keyCode == 13) return false;"
          style = "width:850px;margin-left:260px; ">

        <table align="center"  style = "width:800px;">
            <?
            $date = mysql_query("Select CONVERT( Date,char(10)) as Date from date ORDER BY idDate DESC LIMIT 1");
            $d = mysql_fetch_assoc($date);
            $id_date = $d['Date'];
            ?>
            <tr > <label class = "txt"><td>период к которому относятся вводимые данные</td>
                    <td><input name="date" id="monthToday" readonly type="text"  class="fld form-control"
                               value="<?echo $id_date?>" ></td>
                </label></tr>
            <tr>
            <tr>
                <label class = "txt"><td>федеральный округ</td>
                    <td><?php
                        $result =  mysql_query ("SELECT d.Name as district, m.Login, m.Name as Name_Manager
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											INNER JOIN district d ON b.District_idDistrict = d.idDistrict
											WHERE m.Login ='$manager_name' LIMIT 1")
                        or die ("<b>Ошибка в обращении к БД.Невозможно получить название округа.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ");
                        $row = mysql_fetch_array($result);
                        echo '
           <input type = "text" name="district" class="fld" id="district" readonly="readonly" style = "background-color:white;" value = "'.$row['district'].'"/>';
                        ?></td>
                </label></tr>
                <label class = "txt"><td >наименование филиала</td>
                    <td > <?php

                        $result =  mysql_query ("SELECT b.Name as branch, m.Login, m.Name, m.idManager,b.idBranch
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											INNER JOIN district d ON b.District_idDistrict = d.idDistrict
											WHERE m.Login ='$manager_name' LIMIT 1 ")
                        or die ("<b>Ошибка в обращении к БД. Невозможно получить название филиала.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ");

                        $rows = mysql_fetch_array($result);
                        echo '<input  type = "text" name="branch" class="fld" id="branch" readonly="readonly"  style = "background-color:white;" value = "'.$rows['branch'].'"/>';
                        ?></td>
                </label></tr>

            <tr>
                <td> <label class = "title">информация о ЦКП</label></td></tr>
            <tr>
                <label class = "txt"><td>название ЦКП</td>
                    <td><input type="hidden" id="hidden" value="false">
                        <select tabindex="1" name="ckp" id="ckp" type="text" class="fld"  style="height: 28px">
                            <option value=\'\'>-- выберите ЦКП --</option>
                            <?php

                            $result =  mysql_query ("SELECT * FROM ccs WHERE Branch_idBranch = '".$id_branch."'  ORDER BY Name")
                            or die ("<b>Ошибка в обращении к БД.Невозможно получить название ЦКП.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ");

                            while ($row = mysql_fetch_array($result)){
                                echo '<option data-city="'.$row['City'].'" data-address="'.$row['Address'].'" data-contact="'.$row['ContactPerson'].'"
                                            data-email="'.$row['E-Mail'].'" data-openingHours="'.$row['OpeningHours'].'" data-phone="'.$row['Phone'].'"
                                            data-CountPeopleWIthOpening="'.$item['CountPeopleWIthOpening'].'" value="'.$row['idCCS'].'" >ЦКП-'.$row['City'].'</option>';
                            }?>
                        </select></td>
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



            <tr> <td><label class = "title">к-во обращений по телефону в ЦКП<br> (или  филиал РТРС) по тематике ФЦП</label></td></tr>
            <tr><label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td> <input tabindex="8" name="CountCallWithOpening" id="CountCallWithOpening" type="text" class="fld count"
                                value=""  maxlength="6" readonly oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>в т ч текущем месяце</td>
                    <td><input tabindex="9" name="CountCallCurrentMonth" id="CountCallCurrentMonth" type="text" class="fld count" value=""
                               maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><td><label class = "title">к-во посетителей ЦКП</label></td></tr>
            <tr><label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td> <input tabindex="10" name="CountPeopleWIthOpening" id="CountPeopleWIthOpening" type="text"
                                class="fld count" value=""  readonly maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td> в т ч текущем месяце</td>
                    <td><input tabindex="11" name="CountPeopleCurrentMonth" id="CountPeopleCurrentMonth" type="text" class="fld count"
                               value=""  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr>  <td><label class="title">к-во выездных презентаций </label></td</tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>

                    <td> <input tabindex="12" name="CountPresentationWithOpening" id="CountPresentationWithOpening" type="text" class="fld count"
                                value=""  maxlength="6" readonly oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>в т ч текущем месяце</td>
                    <td> <input tabindex="13" name="CountPresentationCurrentMonth" id="CountPresentationCurrentMonth" type="text" class="fld count"
                                value=""  maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><td><label class="title">к-во писем на e-mail</label></td></tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td><input tabindex="14" name="CountLettersWithOpening" id="CountLettersWithOpening" type="text" class="fld count"
                               value="" maxlength="6" readonly oninput="Ftest (this)"
                               onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr>  <label class = "txt"><td>в т ч текущем месяце</td>
                    <td> <input tabindex="15" name="CountLettersCurrentMonth" id="CountLettersCurrentMonth" type="text" class="fld count"
                                value="" maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr>  <td><label class = "title">тематики обращений</label></td></tr>
            <tr><label class = "txt"><td>к-во вопросов по отключенным передатчикам РТРС-2 «50-»</td>
                    <td> <input tabindex="16" name="CountQuestionsTransmitterOff" id="CountQuestionsTransmitterOff" type="text" class="fld count"
                                value=""  maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr>  <label class = "txt"><td>к-во вопросов о сроках запуска мультиплексов/передатчиков</td>
                    <td> <input  tabindex="17" name="CountQuestionsLaunchDateTansmitters" id="CountQuestionsLaunchDateTansmitters" type="text"
                                 class="fld count" value=""  maxlength="6" oninput="Ftest (this)"
                                 onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>к-во вопросов о настройке приемного оборудования</td>
                    <td><input tabindex="18" name="CountQuestionsEquipment" id="CountQuestionsEquipment" type="text" class="fld count"
                               value="" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во вопросов о региональных врезках</td>
                    <td> <input tabindex="19" name="CountQuestionsRegionalIntervention" id="CountQuestionsRegionalIntervention" type="text"
                                class="fld count" value="" maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во вопросов о перерывах в вещании</td>
                    <td><input tabindex="20" name="CountQuestionsBreaksInBroadcasting" id="CountQuestionsBreaksInBroadcasting" type="text" class="fld count"
                               value=""  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>к-во претензий к качеству ТВ сигнала</td>
                    <td> <input tabindex="21" name="CountClaimsToQuality" id="CountClaimsToQuality" type="text" class="fld count"
                                value="" maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во жалоб на ЦКП</td>
                    <td> <input tabindex="22" name="CountComplaintsOfCCS" id="CountComplaintsOfCCS" type="text" class="fld count"
                                value="" maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if ('v' == '\\v' && parseFloat (navigator.userAgent.split ('MSIE ') [1].split (';') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td><div >прочие вопросы</div></td>
                    <td><textarea tabindex="23" name="OtherQuestions" id="OtherQuestions" class="fld " style = "width: 350px;height: 100px;"
                            ></textarea></td>
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
            <tr >
                <td>прикрепленные файлы</td>
                <td>
                    <ol id="file_del"></ol>
                    <div id = "file_add"></div>

                </td>
            </tr>

            <tr><td colspan="2" align="center">

                    <input type="hidden" value="" name="count" id = "count"/>
                    <input type="reset" value="Очистить" name="reset" id="reset"
                           onclick="confirm('Вы уверены, что хотите очистить форму?')"/>
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
            //$('#CountPeopleWIthOpening').val(CountPeopleWIthOpening);

        });


        $('select#ckp').change(function (){
            var id_elem = $(this).val();
            var date =  $('#monthToday').val();
            $('#hidden').val('true');
            $.ajax({
                type: "POST",
                url: "ajax_enter.php",
                dataType: 'json',
                data: {'idckp': id_elem, 'date': date,'ajaxRequest': 'CountPeopleWIthOpening'},
                success: function(result)
                {
                    if(result.count1 == null) {
                        //alert(result.count1);
                        $('#CountCallWithOpening').val(0);
                    }
                    if(result.count2 == null) {
                        $('#CountPeopleWIthOpening').val(0);
                    }
                    if(result.count3 == null) {

                        $('#CountPresentationWithOpening').val(0);
                    }
                    if(result.count4 == null){

                        $('#CountLettersWithOpening').val(0);
                    }else {
                       // alert(result.count1);
                       // alert(result.count1);
                        $('#CountCallWithOpening').val(result.count1);
                        $('#CountPeopleWIthOpening').val(result.count2);
                        $('#CountPresentationWithOpening').val(result.count3);
                        $('#CountLettersWithOpening').val(result.count4);
                        $('#CountCallCurrentMonth').val(result.count5);
                        $('#CountPeopleCurrentMonth').val(result.count6);
                        $('#CountPresentationCurrentMonth').val(result.count7);
                        $('#CountLettersCurrentMonth').val(result.count8);
                        $('#CountClaimsToQuality').val(result.count9);
                        $('#CountQuestionsTransmitterOff').val(result.count10);
                        $('#CountQuestionsLaunchDateTansmitters').val(result.count11);
                        $('#CountQuestionsEquipment').val(result.count12);
                        $('#CountQuestionsRegionalIntervention').val(result.count13);
                        $('#CountQuestionsBreaksInBroadcasting').val(result.count14);
                        $('#CountComplaintsOfCCS').val(result.count15);
                        $('#OtherQuestions').val(result.count16);
                        $('#file_del').append(result.count17);
                    }
                },
                // $("#status").text(result);},
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
//function file_delete() {
//    $('#file_button').click(function () {
//        var item = $('#items').val();
//        alert($('#items').val());
//        $.ajax({
//            type: 'GET',
//            url: 'del.php',
//            data: {'p': item},
//            dataType: 'html',
//            success: function (result) {
//                $("#file_add").text(result);
//               // document.getElementById("#file_add").innerHTML = result;
//            },
//            error: function (jqXHR, exception) {
//                if (jqXHR.status === 0) {
//                    alert('Not connect.\n Verify Network.');
//                } else if (jqXHR.status == 404) {
//                    alert('Requested page not found. [404]');
//                } else if (jqXHR.status == 500) {
//                    alert('Internal Server Error [500].');
//                } else if (exception === 'parsererror') {
//                    alert('Requested JSON parse failed.');
//                } else if (exception === 'timeout') {
//                    alert('Time out error.');
//                } else if (exception === 'abort') {
//                    alert('Ajax request aborted.');
//                } else {
//                    alert('Uncaught Error.\n' + jqXHR.responseText);
//                }
//            }
//
//        });
//    });
//
//}
    </script>
    </html>
<?php



