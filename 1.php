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
//<div><?echo $_SESSION['Name_Manager']</div>
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
            if ($("#OtherQuestions").val() == ""){
                $("#OtherQuestions").css('border', '1px solid red');
                is_valid = false;
            }else{
                $("#OtherQuestions").blur(function (){
                    if ($("#OtherQuestions").val() == "" ) {
                        $("#OtherQuestions").css('border', '1px solid red');
                        is_valid = false;
                    }
                });
                $("#OtherQuestions").css('border', '1px solid #8699a4');

            }
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
                            //history.back();
                            //document.location.href='data.php';
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

<body >
<div style="width:100px;height:80px; float:right;margin-left: 40px; z-index: 1000;position: relative">
    <img src="img/logo.png">
</div>

<div align="center" style="padding-top: 20px; ">

    <div class="heading" style = "width: 55%">
        Внесение данных о работе Центра консультационной поддержки
        <a href="logoff.php?mode=logoff"  onclick="return window.confirm('Вы уверены что хотите покаинуть страницу?');">
            <div style="float: left;padding-top: 3px;"> Выход</div> <img src="img/logout.jpg"></a>
    </div>
<?
$id = $_GET['id'];
$res = mysql_query("SELECT idFacts, Date, CreateDate, CountCallWithOpening, CountCallCurrentMonth,
                    CountPeopleWIthOpening, CountPeopleCurrentMonth, CountPresentationWithOpening,
                     CountPresentationCurrentMonth, CountLettersWithOpening,
                    CountLettersCurrentMonth, CountClaimsToQuality,CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                    CountQuestionsRegionalIntervention, CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS,OtherQuestions,
                    m.Name, c.Name as ckp, d.Name as district_name, Manager_idManager,CCS_idCCS, b.Name as branch,
                    GROUP_CONCAT(a.PathFile, ',') as PathFile
                    FROM fact f
                    INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                    INNER JOIN manager m ON f.Manager_idManager = m.idManager
                    INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                    INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                     left join attachment a ON f.idFacts = a.Fact_idFacts
                    WHERE idFacts= '$id' Group by f.idFacts")
or die ('Ошибка в обращении к БД. Невозможно выполнить запрос.');
$item = mysql_fetch_array($res);

function filetolink($files){
    $files = explode(',', $files);
    $links = array();
    foreach ($files as $items) {
        if (empty($items)){
            continue;
        }
        $fileName = explode(' ', $items);
        $originName = array_splice($fileName, 2);
        $name =  implode(' ' , $originName);
        $link = "<li><table><tr><td width='200px'><a href='/uploads/{$items}' style='text-decoration: none;color: #1F497D;'>{$name}</a></td>
<td><input type='button' id = 'file_button' value='Удалить' onclick=\"if(confirm('Вы действительно хотите удалить файл?'))
                                location.href='del.php?p={$items}';\"/></td></tr></table>
                                </li>";
        array_push($links, $link);
    }

    return implode('', $links);
}

    echo '<form name="editform" id = "editform" action="save_edit.php" method="POST">
        <table align="center">';
            echo '
            <tr> <label class = "txt"><td>федеральный округ</td>
                    <td>
                        <input type="text" name="district" class="fld" id="district" disabled="disabled" style = "background-color:white;"
                               value = "' . $item['district_name'] . '"/></td>
                </label></tr>
            <tr> <label class = "txt"><td>наименование филиала</td>
                    <td> <input type="text" name="branch" class="fld" id="branch" disabled="disabled" style = "background-color:white;"
                                value = "' . $item['branch'] . '"/></td>
                </label></tr>
            <tr><label class = "txt"><td>название ЦКП</td>
                    <td><input tabindex="1" name="ckp" id="ckp" type="text" class="fld" disabled="disabled"
                               style = "background-color:white;" value = "' . $item['ckp'] . '"/></td>
                </label></tr>
            <tr><td><label class = "title">к-во обращений по телефону в ЦКП (или  филиал РТРС)</br> по тематике ФЦП</label></td></tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td><input tabindex="8" name="CountCallWithOpening" readonly id="CountCallWithOpening" type="text" class="fld count"
                               value="' . $item['CountCallWithOpening'] . '"  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>в т ч текущем месяце</td>
                    <td> <input tabindex="9" name="CountCallCurrentMonth" id="CountCallCurrentMonth" type="text" class="fld count" value="' . $item['CountCallCurrentMonth'] . '"
                                maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <td><label class = "title">к-во посетителей ЦКП</label></td></tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td><input tabindex="10" name="CountPeopleWIthOpening" readonly id="CountPeopleWIthOpening" type="text"
                               class="fld count" value="' . $item['CountPeopleWIthOpening'] . '"  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td> в т ч текущем месяце</td>
                    <td> <input tabindex="11" name="CountPeopleCurrentMonth"  id="CountPeopleCurrentMonth" type="text" class="fld count"
                                value="' . $item['CountPeopleCurrentMonth'] . '"  maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><td> <label class="title"> к-во выездных презентаций</label></td></tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td> <input tabindex="12" name="CountPresentationWithOpening" readonly id="CountPresentationWithOpening" type="text" class="fld count"
                                value="' . $item['CountPresentationWithOpening'] . '"  maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>в т ч текущем месяце</td>
                    <td><input tabindex="13" name="CountPresentationCurrentMonth" id="CountPresentationCurrentMonth" type="text" class="fld count"
                               value="' . $item['CountPresentationCurrentMonth'] . '"  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <td><label class="title"> к-во писем на e-mail</label></td></tr>
            <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td><input tabindex="14" name="CountLettersWithOpening" readonly id="CountLettersWithOpening" type="text" class="fld count"
                               value="' . $item['CountLettersWithOpening'] . '" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>в т ч текущем месяце</td>
                    <td><input tabindex="15" name="CountLettersCurrentMonth" id="CountLettersCurrentMonth" type="text" class="fld count"
                               value="' . $item['CountLettersCurrentMonth'] . '" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <td><label class = "title">тематики обращений</label></td></tr>
            <tr><label class = "txt"><td>к-во вопросов по отключенным передатчикам РТРС-2 «50-»</td>
                    <td><input tabindex="16" name="CountQuestionsTransmitterOff" id="CountQuestionsTransmitterOff" type="text" class="fld count"
                               value="' . $item['CountQuestionsTransmitterOff'] . '"  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во вопросов о сроках запуска мультиплексов/передатчиков</td>
                    <td> <input  tabindex="17" name="CountQuestionsLaunchDateTansmitters" id="CountQuestionsLaunchDateTansmitters" type="text"
                                 class="fld count" value="' . $item['CountQuestionsLaunchDateTansmitters'] . '"  maxlength="6" oninput="Ftest (this)"
                                 onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr> <label class = "txt"><td>к-во вопросов о настройке приемного оборудования</td>
                    <td><input tabindex="18" name="CountQuestionsEquipment" id="CountQuestionsEquipment" type="text" class="fld count"
                               value="' . $item['CountQuestionsEquipment'] . '" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во вопросов о региональных врезках</td>
                    <td> <input tabindex="19" name="CountQuestionsRegionalIntervention" id="CountQuestionsRegionalIntervention" type="text"
                                class="fld count" value="' . $item['CountQuestionsRegionalIntervention'] . '" maxlength="6" oninput="Ftest (this)"
                                onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во вопросов о перерывах в вещании</td>
                    <td><input tabindex="20" name="CountQuestionsBreaksInBroadcasting" id="CountQuestionsBreaksInBroadcasting" type="text" class="fld count"
                               value="' . $item['CountQuestionsBreaksInBroadcasting'] . '"  maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во претензий к качеству ТВ сигнала</td>
                    <td><input tabindex="21" name="CountClaimsToQuality" id="CountClaimsToQuality" type="text" class="fld count"
                               value="' . $item['CountClaimsToQuality'] . '" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td>к-во жалоб на ЦКП</td>
                    <td><input tabindex="22" name="CountComplaintsOfCCS" id="CountComplaintsOfCCS" type="text" class="fld count"
                               value="' . $item['CountComplaintsOfCCS'] . '" maxlength="6" oninput="Ftest (this)"
                               onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
            <tr><label class = "txt"><td><div >прочие вопросы</div></td>
                    <td> <textarea tabindex="23" name="OtherQuestions" id="OtherQuestions" class="fld " style = "width: 350px;height: 150px;"
                            >' . $item['OtherQuestions'] . '</textarea></td>
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
                    <ol id="file_del">'.filetolink($item['PathFile']) .'</ol>
                    <div id = "file_add"></div>

                </td>
            </tr>
            <tr><td colspan="2" align="center">
                <input type = "hidden" value = "'.$id.'" name = "id_facts">
                    <input type="hidden" value="" name="count" id = "count"/>
                    <input type="reset" value="Очистить" name="reset" id="reset" onclick="confirm(\'Вы уверены, что хотите очистить форму?\')"/>
                    <input type="button" value="Сохранить" name="button" id="button"
                           onclick="SendFile(\'status\', \'editform\', \'save_edit.php\') " />
                    <input id="history" type="button" name="history" data-submitted="false"
                           value="Вернуться назад" onclick="location.href=\'report.php\'"/>
                </td>
            </tr>
        </table>
    </form>
    <div id = "status" align="center"></div>';
    ?>
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

        var branch_id = option.attr('data-branch');
        $('#branch').val(branch_id);
        var district_id = option.attr('data-dis');
        $('#district').val(district_id);
//            var manager = option.attr('data-branch');
//            $('#manager').val(manager);
//            $('#manager').text(manager);

    });


    $('select#ckp').change(function (){
        var id_elem = $(this).val();
        var date =  $('#monthToday').val();
        $('#hidden').val('true');
        $.ajax({
            type: "POST",
            url: "itog.php",
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
                    alert('Uncaught Error.\n' + jqXHR.res);
                }

            }
        });
    });


</script>

</html>


