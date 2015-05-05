<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
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
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Данные по филиалу</title>
    <link href="css/menu.css" rel="stylesheet" type="text/css"/>
    <link href="css/style_adm.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>

</head>
<body>
<?
if($_POST) {
    $dat = mysql_query("Select CONVERT( Date,char(10)) as Date from date ORDER BY idDate DESC LIMIT 1")
    or die ("<p align='center'><b>Ошибка в обращении к БД. Попробуйте обновить страницу или обратится к администратору. </b> </p>");
    $d = mysql_fetch_assoc($dat);
    $id_date = $d['Date'];
    echo '<input type="hidden" name = "Date_DB" id = "Date_DB"  value = "' . $d['Date']. '"/>';
    $strQuery = "SELECT f.idFacts,  CONVERT( Date,char(10)) as Date, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions,
                            m.Name as manager, c.Name as ckp,c.City as city, d.Code as district_name, Manager_idManager,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile ,
                            b.Name as branch,b.idBranch as idBranch
                            FROM
                            fact as  f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                            Where idBranch= $br Group by f.idFacts Order by f.idFacts DESC ";
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
    if (($num_rows = mysql_num_rows($result)) != 0) {
        echo "
        <form action='' id='manager' name='manager' class='manager' method='post' enctype='multipart/form-data' style='margin:20px;'>
        <table width='900px' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center' class = 'sortable'>
            <tbody>";
        echo '<tr><td rowspan = "2"><div class = "vertical1" >Дата</div></td>

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
                                <td class = "header"><div class = "vertical">о сроках запуска мультиплексов</div></td>
                                <td class = "header"><div class = "vertical">о оборудовании</div></td>
                                <td class = "header"><div class = "vertical">о региональных врезках</div></td>
                                <td class = "header"><div class = "vertical">о перерывах в вещании</div></td></tr>';
        while ($myrow = mysql_fetch_array($result)) {
            echo '<tr>';
            echo '<td width = "40px" id = "Date" name="Date' . $myrow['idFacts'] . '">' . $myrow['Date'] . '
            <input type="hidden" name = "Date_select" id = "Date_select"  value = "' . $myrow['Date'] . '"/>
                <input type="hidden" name = "id" id = "id"  value = "' . $myrow['idFacts'] . '"/></td>';

            echo '<td name="ckp_' . $myrow['idFacts'] . '">ЦКП-' . $myrow['ckp'] . ' ' . $myrow['city'] . '</td>';

            echo '<td class = "edit CountCallWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountCallWithOpening'] . '
                <input type="hidden" name = "CountCallWithOpening" value = "' . $myrow['CountCallWithOpening'] . '"/></td>  ';
            echo '<td class = "edit CountCallCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountCallCurrentMonth'] . '</td>';
            echo '<td class = "edit CountPeopleWIthOpening ' . $myrow['idFacts'] . '">' . $myrow['CountPeopleWIthOpening'] . '</td>';
            echo'<td class = "edit CountPeopleCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountPeopleCurrentMonth'] . '</td>';
            echo '<td class = "edit CountPresentationWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountPresentationWithOpening'] . '</td>';
            echo '<td class = "edit CountPresentationCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountPresentationCurrentMonth'] . '</td>';
            echo '<td class = "edit CountLettersWithOpening ' . $myrow['idFacts'] . '">' . $myrow['CountLettersWithOpening'] . '</td>';
            echo '<td class = "edit CountLettersCurrentMonth ' . $myrow['idFacts'] . '">' . $myrow['CountLettersCurrentMonth'] . '</td>';
            echo '<td class = "edit CountQuestionsTransmitterOff' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsTransmitterOff'] . '</td>';
            echo '<td class = "edit CountQuestionsLaunchDateTansmitters ' . $myrow['idFacts'] . '" >' . $myrow['CountQuestionsLaunchDateTansmitters'] . '</td>';
            echo '<td class = "edit CountQuestionsEquipment ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsEquipment'] . '</td>';
            echo '<td class = "edit CountQuestionsRegionalIntervention ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsRegionalIntervention'] . '</td>';
            echo '<td class = "edit CountQuestionsBreaksInBroadcasting ' . $myrow['idFacts'] . '">' . $myrow['CountQuestionsBreaksInBroadcasting'] . '</td>';
            echo '<td class = "edit CountClaimsToQuality' . $myrow['idFacts'] . '">' . $myrow['CountClaimsToQuality'] . '</td>';
            echo '<td  class = "edit CountComplaintsOfCCS ' . $myrow['idFacts'] . '">' . $myrow['CountComplaintsOfCCS'] . '</td>';
            echo '<td  class = "edit OtherQuestions ' . $myrow['idFacts'] . '"><textarea cols = "30" rows="3" readonly="readonly">' . $myrow['OtherQuestions'] . '</textarea></td>';
            echo'<td  class = " PathFile ' . $myrow['idFacts'] . '">' . filetolink($myrow['PathFile']) . '</td>';
            echo '<td><a href="edit.php?id=' . $myrow['idFacts'] . '">Редактировать</a></td>';
            echo '<td><a href="delete_records.php?id=' . $myrow['idFacts'] . '"
                     onClick="return window.confirm(\'Вы уверены, что хотите удалить данные?\')">Удалить</a></td>';
            echo "</tr>";
        }
        echo '</tbody> </table></form>';


    }

}?>


</body>
<?
echo '<script type="text/javascript">
     $(document).ready(function(){
            var date_db = $( "#Date_DB").val();
            var date =  $("tr:contains("+ date_db +")");

                $("tr:contains("+ date_db +")");.css("background-color", "#d2f8d2");
                $("input#add").val("Изменить данные");


        });
    </script>';
    ?>
</html>
