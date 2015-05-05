<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
$login = $_SESSION['login'];
$manager =  mysql_query ("SELECT b.Name as br, m.Login, m.Name as manager, m.idManager, b.idBranch
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											WHERE m.Login = '$login'")
or die ("<b>Ошибка в обращении к БД. Невозможно получить название филиала.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ".mysql_error());
$data = mysql_fetch_assoc($manager);
$m = $data['Login'];
$br = $data['idBranch'];

if($login != $m) {
    echo "<script>alert('Для ввода данных необходимо перейдти на форму Администрирования' +
' для настройки привязки филиала к вашей учетке');
   history.back();</script>";
}else {
//if($_SESSION['Role'] =='Администратор' ) {
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Данные по филиалу</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css"/>
        <link href="css/style_adm.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#hidden').click(function() {
                    document.location.href='records.php';
                });

            });


            //onload = document.manager.reset;
            function AjaxFormRequest(result_id, formMain, url) {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    dataType: "html",
                    data: jQuery("#" + formMain).serialize(),
                    success: function (response) {
                        document.getElementById(result_id).innerHTML = response;
                    },
                    error: function (response) {
                        document.getElementById(result_id).innerHTML = "<p>Возникла ошибка при отправке формы. Попробуйте еще раз</p>";
                    }
                });
            }
        </script>
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
        <style>
            .vertical {
                padding: 0px;
                margin: -5px -40px -10px -40px;
                -moz-transform: rotate(270deg);
                -webkit-transform: rotate(270deg);
                -o-transform: rotate(270deg);
                width: 110px;
                height: 35px;
            }

            .vertical1 {
                -moz-transform: rotate(270deg);
                -webkit-transform: rotate(270deg);
                -o-transform: rotate(270deg);

                height: 30px;
                text-align: center;
            }

            .header {
                height: 120px;
                width: 50px;
            }
        </style>
    </head>
    <body>
<input type="hidden" id="hidden" onclick="">
    <div id="results">
        <div style="width:100px;height:80px; right:20px;margin-left:40px;margin-top:80px;padding-right:20px; z-index: 1000;
        position: absolute">
            <img src="img/logo.png">
        </div>

        <div align="center" style="padding-top: 20px; ">
            <div class="heading" style="width:93%"> Данные по филиалу
                <a href="logoff.php?mode=logoff" style="padding-left:50px;"
                   onclick="return window.confirm('Вы уверены что хотите покаинуть страницу?');">
                    <div style="float: left;padding-top: 3px;">Выход</div><img src="img/logout.jpg"></a>
            </div>
            <div style = "margin-left:50px;">
            <ul class="menu" >
                <li><a href="index.php">Интерфейс раздачи ролей</a></li>
                <li class="current"><a href="records.php"> Форма ввода</a></li>
                <li><a href="report.php">Итоговая форма</a></li>
                <li><a href="action.php">Администрирование</a></li>
            </ul>
            </div>

        </div>
        <?php
        $dat = mysql_query("Select CONVERT( Date,char(10)) as Date from date ORDER BY idDate DESC LIMIT 1")
        or die ("<p align='center'><b>Ошибка в обращении к БД. Попробуйте обновить страницу или обратится к администратору. </b> </p>");
        $d = mysql_fetch_assoc($dat);
        $id_date = $d['Date'];
        echo '<input type="hidden" name = "Date_DB" id = "Date_DB"  value = "' . $d['Date']. '"/>';
        $strQuery = "SELECT f.idFacts, CONVERT( Date,char(10)) as Date, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions,
                            m.Name as manager, c.Name as ckp,c.City as city, d.Code as district_name, Manager_idManager,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile ,
                            b.Name as branch, b.idBranch as idBranch
                            FROM
                            fact as  f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                            Where idBranch= $br  Group by f.idFacts Order by f.idFacts DESC LIMIT 5";

        $str = "SELECT idFacts, date_format(Date, '%Y.%m.%d') as Date, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions,
                            m.Name as manager, c.Name as ckp,c.City as city, d.Code as district_name, Manager_idManager,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile ,
                            b.Name as branch,b.idBranch as idBranch,m.idManager as idManager
                            FROM fact f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                            Where Login='" . $_SESSION['login'] . "' Group by f.idFacts ";
        //Where Login='".$_SESSION['login']."'
        $result = mysql_query($strQuery)
        or die ("<p align='center'><b>Ошибка в обращении к БД. Попробуйте обновить страницу или обратится к администратору. </b> </p>");


        function filetolink($files)
        {
            $files = explode(',', $files);
            $links = array();
            foreach ($files as $item) {
                $fileName = explode(' ', $item);
                $originName = array_splice($fileName, 2);
                $name =  implode(' ' , $originName);
                $link = "<a href='/uploads/{$item}'>{$name}</a>";
                array_push($links, $link);
            }
            return implode('<br>', $links);
        }

        $res = mysql_query($str);
        $row = mysql_fetch_array($res);

        $manager = mysql_query("SELECT b.Name as branch, m.Login, m.Name as manager, m.idManager,b.idBranch
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											INNER JOIN district d ON b.District_idDistrict = d.idDistrict
											WHERE m.Login='" . $_SESSION['login'] . "' LIMIT 1 ")
        or die ("<b>Ошибка в обращении к БД. Невозможно получить название филиала.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ");
        $row_manager = mysql_fetch_array($manager);
        echo "<p align='center'>Филиал: <label>" . $row_manager['branch'] . "</label></p>
    <p align='center'>Контент-менеджер: <label>" . $row_manager['manager'] . "</label></p>";
        echo '<p align="center"> <a href="enter.php">
	 <input type="hidden" id="hidden" value="false">
	 <input type="button" id = "add" value="Добавить данные" /></a>
	 <input type="button" name = "all_records" id = "all_records" value ="Вывести все данные"></p>';

        $_SESSION['manager_name'] = $row_manager['Login'];
        $_SESSION['id_branch'] = $row_manager['idBranch'];

        if (($num_rows = mysql_num_rows($result)) != 0) {
            $table .= "<div id = 'status'>
        <form action='' id='manager' name='manager' class='manager' method='post' enctype='multipart/form-data' style='margin:20px;'>
        <table width='900px' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center' class = 'sortable'>
            <tbody>";
            $table .= '<tr><td rowspan = "2"><div class = "vertical1" >Дата</div></td>

                                 <td rowspan = "2"><div class = "vertical1">Название ЦКП</div></td>
                                <td colspan = "2" >Количество обращений по телефону</td>
                                <td colspan = "2" >Количество посетителей ЦКП</td>
                                <td colspan = "2" >Количество презентаций</td>
                                <td colspan = "2" >Количество писем</td>
                                <td colspan = "5" >Количество вопросов</td>
                                <td rowspan = "2" > <div class = "vertical" style="height:30px;width: 150px">Количество претензий к качеству ТВ сигнала</div></td>
                                <td rowspan = "2"><div class = "vertical" >Количество жалоб на ЦКП</div></td>
                                <td  rowspan = "2" ><div class = "vertical1" >Прочие вопросы</div></td>
                                <td  rowspan = "2" ><div class = "vertical1" >Файлы</div></td>
                                <td  rowspan = "2" > <div class = "vertical" >Редактировать</div></td>
                                <td  rowspan = "2" ><div class = "vertical" >Удалить</div></td>

                                </tr>

                                <tr ><td ><div class = "vertical"> за весь период c открытия</div></td>

                                <td class = "header"><div class = "vertical">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vertical">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vertical">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vertical">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vertical">в т ч текущем месяцe</div></td>
                                <td class = "header"><div class = "vertical">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vertical">в т ч текущем месяце</div></td>
                                <td class = "header"> <div class = "vertical" style="height:50px;width: 120px">по отключенным передатчикам РТРС-2 «50-»</div> </td>
                                <td class = "header"><div class = "vertical">о сроках запуска мультиплексоров</div></td>
                                <td class = "header"><div class = "vertical">о оборудовании</div></td>
                                <td class = "header"><div class = "vertical">о региональных врезках</div></td>
                                <td class = "header"><div class = "vertical">о перерывах в вещании</div></td></tr>';
            while ($myrow = mysql_fetch_array($result)) {
                $table .= '<tr class="select">';
                $table .= '<td width = "40px" id = "Date" name="Date' . $myrow['idFacts'] . '">' . $myrow['Date'] . '
                <input type="hidden" name = "Date_select" id = "Date_select"  value = "' . $myrow['Date'] . '"/>
                <input type="hidden" name = "id" id = "id"  value = "' . $myrow['idFacts'] . '"/>

                </td>';

                $table .= '<td name="ckp_' . $myrow['idFacts'] . '">ЦКП-' . $myrow['ckp'] . ' ' . $myrow['city'] . '</td>';

                $table .= '<td class = "edit CountCallWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountCallWithOpening'] . '
                <input type="hidden" name = "CountCallWithOpening" value = "' . $myrow['CountCallWithOpening'] . '"/></td>  ';
                $table .= '<td class = "edit CountCallCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountCallCurrentMonth'] . '</td>';
                $table .= '<td class = "edit CountPeopleWIthOpening ' . $myrow['idFacts'] . '">' . $myrow['CountPeopleWIthOpening'] . '</td>';
                $table .= '<td class = "edit CountPeopleCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountPeopleCurrentMonth'] . '</td>';
                $table .= '<td class = "edit CountPresentationWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountPresentationWithOpening'] . '</td>';
                $table .= '<td class = "edit CountPresentationCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountPresentationCurrentMonth'] . '</td>';
                $table .= '<td class = "edit CountLettersWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountLettersWithOpening'] . '</td>';
                $table .= '<td class = "edit CountLettersCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountLettersCurrentMonth'] . '</td>';
                $table .= '<td class = "edit CountQuestionsTransmitterOff' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsTransmitterOff'] . '</td>';
                $table .= '<td class = "edit CountQuestionsLaunchDateTansmitters ' . $myrow['idFacts'] . '" >' . $myrow['CountQuestionsLaunchDateTansmitters'] . '</td>';
                $table .= '<td class = "edit CountQuestionsEquipment ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsEquipment'] . '</td>';
                $table .= '<td class = "edit CountQuestionsRegionalIntervention ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsRegionalIntervention'] . '</td>';
                $table .= '<td class = "edit CountQuestionsBreaksInBroadcasting ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsBreaksInBroadcasting'] . '</td>';
                $table .= '<td class = "edit CountClaimsToQuality' . $myrow['idFacts'] . '">' . $myrow['CountClaimsToQuality'] . '</td>';
                $table .= '<td  class = "edit CountComplaintsOfCCS ' . $myrow['idFacts'] . '">' . $myrow['CountComplaintsOfCCS'] . '</td>';
                $table .= '<td  class = "edit OtherQuestions ' . $myrow['idFacts'] . '"><textarea cols = "30" rows="3" readonly="readonly">' . $myrow['OtherQuestions'] . '</textarea></td>';
                $table .= '<td  class = " PathFile ' . $myrow['idFacts'] . '">' . filetolink($myrow['PathFile']) . '</td>';
                $table .= '<td><a href="edit.php?id=' . $myrow['idFacts'] . '">Редактировать</a></td>';
                $table .= '<td><a href="delete_records.php?id=' . $myrow['idFacts'] . '"
                     onClick="return window.confirm(\'Вы уверены, что хотите удалить данные?\')">Удалить</a></td>';
                $table .= "</tr>";
            }
            $table .= '</tbody> </table></form></div>';

            echo $table;


        }

        ?>


    </div>
    <div ID = "toTop" ><img src="img/up.jpg"> </div>
    </body>
    <script>
        $(document).ready(function(){
            $("input#add").val("Добавить данные");
            var date_db = $( "#Date_DB").val();
            var date =  ('tr:contains('+ date_db +')');
           // alert(date);
            if(('tr:contains('+ date_db +')')) {
                $('tr:contains(' + date_db + ')').css("background-color", "#d2f8d2");
                $("input#add").val("Изменить данные");
            }else{
                $("input#add").val("Добавить данные");
            }


        });


        $('#all_records').click(function (){
            $.ajax({
                url:"all_records.php",
                //statbox:"status",
                dataType: 'html',
                data:{'ckp':"deiojdi"},
                method:"POST",
                success:function(data){
                    $("input#add").val("Добавить данные");
                    document.getElementById("status").innerHTML=data;
                    var date_db = $( "#Date_DB").val();
                    if(('tr:contains('+ date_db +')')) {
                        $('tr:contains(' + date_db + ')').css("background-color", "#d2f8d2");
                        $("input#add").val("Изменить данные");
                    }else{
                        $("input#add").val("Добавить данные");
                    }

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
                        alert('Uncaught Error.\n' + jqXHR.res);
                    }

                }
            })
        });

    </script>
    </html>
<?php
}
//}else{
//
//    echo "<script>alert('Для ввода данных необходимо перейдти на форму Администрирования' +
// ' для настройки привязки филиала к вашей учетке');
//    history.back();</script>";
//
//
//}
//}else{
//   header('Location: index.php');
//   echo '<p align="center">У вас нет прав для доступа к этой странице!</p>';
//
//}

