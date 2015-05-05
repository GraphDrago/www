<?php
    ini_set("default_charset","UTF-8");
	ini_set('display_errors', 'Off');
    include 'connect.php';
    include 'auth.php';
//if($_SESSION['Role']=='Гость' || $_SESSION['Role'] == 'Менеджер' || $_SESSION['Role'] == 'Пользователь'){
//    header('Location: index.html');
//    echo '<p align="center">У вас нет прав для просмотра этой страницы!</p>';
//}
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Редактирование</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <link href="css/menu.css" rel="stylesheet" type="text/css" />
        <script src="jquery-1.7.1.min.js" type="text/javascript"  ></script>
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


            function AjaxFormRequest(result_id,formMain,url) {
                jQuery.ajax({
                    url:     url,
                    type:     "POST",
                    dataType: "html",
                    data: jQuery("#"+formMain).serialize(),
                    success: function(response) {
                        //document.getElementById(result_id).innerHTML = response;
                        history.back();
                    },
                    error: function(response) {
                        document.getElementById(result_id).innerHTML =
                            "<p>Возникла ошибка при отправке формы. Попробуйте еще раз</p>";
                    }
                });
            }


        </script>
        <script>
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
    <body>
    <div style="width:100px;height:80px; float:right;margin-left: 40px; z-index: 1000;position: relative">
        <img src="img/logo.png" >
    </div>

    <div style="padding-top:20px;" align="center">

        <div class = "heading" style = "width: 52%"> Редактировать
            <a href="logoff.php?mode=logoff"  onclick="return window.confirm('Вы уверены что хотите покаинуть страницу?');">
                <div style="float: left;padding-top: 3px;">Выход</div> <img src="img/logout.png" ></a>
        </div>
        <ul class="menu">

        </ul>

    </div>
    <?php
        $id = $_GET['id'];
        $res = mysql_query("SELECT idFacts, Date, CreateDate, CountCallWithOpening, CountCallCurrentMonth,
                    CountPeopleWIthOpening, CountPeopleCurrentMonth, CountPresentationWithOpening,
                     CountPresentationCurrentMonth, CountLettersWithOpening,
                    CountLettersCurrentMonth, CountClaimsToQuality,CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                    CountQuestionsRegionalIntervention, CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS,OtherQuestions,
                    m.Name, c.Name as ckp, d.Name as district_name, Manager_idManager,CCS_idCCS, b.Name as branch
                    FROM fact f
                    INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                    INNER JOIN manager m ON f.Manager_idManager = m.idManager
                    INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                    INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                    WHERE idFacts= '$id'")
        or die ('Ошибка в обращении к БД. Невозможно выполнить запрос.');
        $item = mysql_fetch_array( $res );
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
        echo '<form name="editform" id = "editform" action="save_edit.php?id='.$_GET['id'].'" method="POST"><table align="center">';
        echo '
               <tr> <label class = "txt"><td>федеральный округ</td>
                   <td> <input type="text" name="district" class="fld" id="district" disabled="disabled" style = "background-color:white;"
                           value = "'.$item['district_name'].'"/></td>
                </label></tr>
               <tr> <label class = "txt"><td>наименование филиала</td>
                   <td> <input type="text" name="branch" class="fld" id="branch" disabled="disabled" style = "background-color:white;"
                            value = "'.$item['branch'].'"/></td>
                </label></tr>
                <tr><label class = "txt"><td>название ЦКП</td>
                    <td><input tabindex="1" name="ckp" id="ckp" type="text" class="fld" disabled="disabled"
                           style = "background-color:white;" value = "'.$item['ckp'].'"/></td>
                </label></tr>
                <tr><td><label class = "title">к-во обращений по телефону в ЦКП (или  филиал РТРС)</br> по тематике ФЦП</label></td></tr>
               <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td><input tabindex="8" name="CountCallWithOpening" id="CountCallWithOpening" type="text" class="fld count"
                            value="'.$item['CountCallWithOpening'].'" readonly maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <label class = "txt"><td>в т ч текущем месяце</td>
                   <td> <input tabindex="9" name="CountCallCurrentMonth" id="CountCallCurrentMonth" type="text" class="fld count" value="'.$item['CountCallCurrentMonth'].'"
                                   maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                   </label></tr>
               <tr> <td><label class = "title">к-во посетителей ЦКП</label></td></tr>
               <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                     <td><input tabindex="10" name="CountPeopleWIthOpening" readonly id="CountPeopleWIthOpening" type="text"
                         class="fld count" value="'.$item['CountPeopleWIthOpening'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <label class = "txt"><td> в т ч текущем месяце</td>
                    <td> <input tabindex="11" name="CountPeopleCurrentMonth" id="CountPeopleCurrentMonth" type="text" class="fld count"
                                   value="'.$item['CountPeopleCurrentMonth'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr><td> <label class="title"> к-во выездных презентаций</label></td></tr>
               <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                    <td> <input tabindex="12" name="CountPresentationWithOpening" readonly id="CountPresentationWithOpening" type="text" class="fld count"
                                   value="'.$item['CountPresentationWithOpening'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>в т ч текущем месяце</td>
                      <td><input tabindex="13" name="CountPresentationCurrentMonth" id="CountPresentationCurrentMonth" type="text" class="fld count"
                                   value="'.$item['CountPresentationCurrentMonth'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <td><label class="title"> к-во писем на e-mail</label></td></tr>
               <tr> <label class = "txt"><td>за весь период работы с момента открытия</td>
                     <td><input tabindex="14" name="CountLettersWithOpening" readonly id="CountLettersWithOpening" type="text" class="fld count"
                                   value="'.$item['CountLettersWithOpening'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <label class = "txt"><td>в т ч текущем месяце</td>
                    <td><input tabindex="15" name="CountLettersCurrentMonth" id="CountLettersCurrentMonth" type="text" class="fld count"
                                   value="'.$item['CountLettersCurrentMonth'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <td><label class = "title">тематики обращений</label></td></tr>
                <tr><label class = "txt"><td>к-во вопросов по отключенным передатчикам РТРС-2 «50-»</td>
                    <td><input tabindex="16" name="CountQuestionsTransmitterOff" id="CountQuestionsTransmitterOff" type="text" class="fld count"
                                   value="'.$item['CountQuestionsTransmitterOff'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>к-во вопросов о сроках запуска мультиплексов/передатчиков</td>
                   <td> <input  tabindex="17" name="CountQuestionsLaunchDateTansmitters" id="CountQuestionsLaunchDateTansmitters" type="text"
                                    class="fld count" value="'.$item['CountQuestionsLaunchDateTansmitters'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
               <tr> <label class = "txt"><td>к-во вопросов о настройке приемного оборудования</td>
                    <td><input tabindex="18" name="CountQuestionsEquipment" id="CountQuestionsEquipment" type="text" class="fld count"
                                   value="'.$item['CountQuestionsEquipment'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>к-во вопросов о региональных врезках</td>
                   <td> <input tabindex="19" name="CountQuestionsRegionalIntervention" id="CountQuestionsRegionalIntervention" type="text"
                                   class="fld count" value="'.$item['CountQuestionsRegionalIntervention'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>к-во вопросов о перерывах в вещании</td>
                    <td><input tabindex="20" name="CountQuestionsBreaksInBroadcasting" id="CountQuestionsBreaksInBroadcasting" type="text" class="fld count"
                                   value="'.$item['CountQuestionsBreaksInBroadcasting'].'"  maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>к-во претензий к качеству ТВ сигнала</td>
                    <td><input tabindex="21" name="CountClaimsToQuality" id="CountClaimsToQuality" type="text" class="fld count"
                                   value="'.$item['CountClaimsToQuality'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td>к-во жалоб на ЦКП</td>
                    <td><input tabindex="22" name="CountComplaintsOfCCS" id="CountComplaintsOfCCS" type="text" class="fld count"
                                   value="'.$item['CountComplaintsOfCCS'].'" maxlength="6" oninput="Ftest (this)"
                                   onpropertychange="if (\'v\' == \'/v\' && parseFloat (navigator.userAgent.split (\'MSIE \') [1].split (\';\') [0]) <= 9) Ftest (this)"></td>
                </label></tr>
                <tr><label class = "txt"><td><div >прочие вопросы</div></td>
                           <td> <textarea tabindex="23" name="OtherQuestions" id="OtherQuestions" class="fld " style = "width: 350px;height: 150px;"
                                       >'.$item['OtherQuestions'].'</textarea></td>
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
               <tr> <td colspan="2" ><p align = "center"><input type="button" value="Сохранить" name="button" id="button"
                           onclick="if(formValidators() && confirm(\'Вы уверены, что хотите сохранить данные?\'))
                            AjaxFormRequest(\'results\', \'editform\', \'save_edit.php?id='.$_GET['id'].'\')" />
                <input type="button" onClick="history.back();" value = "Отменить"></p></td></tr>
                </table></form> <div id = "results" align="center"></div>';

    ?>
    </div>
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
        </script>
    </html>
