<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
if($_SESSION['Role'] !='Content Manager' ) {
    header('Location: index.html');
}
//if($_SESSION['Role'] =='Администратор' || $_SESSION['Role'] =='Менеджер' ) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Данные по филиалу</title>
    <link href="css/style_adm.css" rel="stylesheet" type="text/css" />
    <link href="css/menu.css" rel="stylesheet" type="text/css" />


    <script src="js/jquery-1.7.1.min.js" type="text/javascript"  ></script>
    <script type="text/javascript" src="js/EventHelpers.js">
    </script>
    <script type="text/javascript" src="js/cssQuery-p.js">
    </script>
    <script type="text/javascript" src="js/jcoglan.com/sylvester.js">
    </script>
    <script type="text/javascript" src="js/cssSandpaper.js">
    </script>

    <script type="text/javascript">

        //onload = document.manager.reset;
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
        .vertical{
            position: relative;
            padding: 5px;
            width: 110px;
            height:30px;
            margin: 0px -50px 0px -25px;
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -webkit-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            -sand-transform: rotate(270deg);

        }
        .vertical1{
            height:30px;
            text-align: center;
            position: relative;
            -moz-transform: rotate(270deg);
            -webkit-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            -sand-transform: rotate(270deg);
            -ms-transform: rotate(270deg);


        }
        .vert{
            display: inline;
            position: relative;
            padding: 0px;
            width: 50px;
            height:30px;
            margin:0px;
            text-align: center;
            margin-top:-30px;
        }

        .header{
            height: 120px;
            width:50px;


        }
        textarea{
            font-family:Myriad Pro;
            color:#1F497D;
        }

    </style>
</head>
<body>

<div id = "results">

<div style="padding-top:20px;" align="center">
    <div class = "heading" >
        <label style="float: left" class = "img_logo"> <img src="img/logo.png" style="width:130px;height:100px;"></label>
        <label style="" class="headers"> Данные по филиалу</label>
        <label  class="logoff"> <a href="logoff.php?mode=logoff"
                                   onclick="return window.confirm('Вы уверены что хотите покинуть страницу?');">
                <label class="log" > Выход</label> <img src="img/logout.png" style="float: right;border:none;" ></a></label>
        <div class = "name_manager"><?echo $_SESSION['Name_Users'];?></div>
    </div>



    <?php
    $dat = mysql_query("Select CONVERT( Date,char(10)) as Date from date ORDER BY idDate DESC LIMIT 1")
    or die ("<p align='center'><b>Ошибка в обращении к БД. Попробуйте обновить страницу или обратится к администратору.5 </b> </p>");
    $d = mysql_fetch_assoc($dat);
    $id_date = $d['Date'];
    echo '<input type="hidden" name = "Date_DB" id = "Date_DB"  value = "' . $d['Date']. '"/>';
    $idBranch = mysql_query("select b.idBranch from manager m
                              INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                              inner JOIN ccs c on c.Branch_idBranch = b.idBranch
                              where Login = '".$_SESSION['login']."'")
    or die ('<p align="center"><b>Ошибка при обращении к БД. Невозможно выполнить запрос.</b></p>');
    $id_br = mysql_fetch_array($idBranch);
    $id_brs = $id_br['idBranch'];
    $strQuery = "SELECT idFacts, CONVERT( Date,char(10)) as Date, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions,
                            m.Name as manager, c.Name as ckp,c.City as city, d.Code as district_name, Manager_idManager,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile ,
                            b.Name as branch,b.idBranch as idBranch,Login,c.Branch_idBranch
                            FROM
                            fact as  f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                            WHERE  c.Branch_idBranch = $id_brs   Group by f.idFacts Order by f.idFacts DESC LIMIT 5";

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
                            Where Login='".$_SESSION['login']."' Group by f.idFacts ";
    //Where Login='".$_SESSION['login']."'
    $result = mysql_query($strQuery)
    or die ("<p align='center'><b>Ошибка в обращении к БД. Попробуйте обновить страницу или обратится к администратору.6 </b> </p>".mysql_error());
    function filetolink($files){
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
    $manager =  mysql_query ("SELECT b.Name as branch, m.Login, m.Name as manager, m.idManager,b.idBranch
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											INNER JOIN district d ON b.District_idDistrict = d.idDistrict
											WHERE m.Login='".$_SESSION['login']."' LIMIT 1 ")
    or die ("<b>Ошибка в обращении к БД. Невозможно получить название филиала.<br>
Попробуйте обновить страницу или обратится к администратору.</b> ");
    $row_manager = mysql_fetch_array($manager);
    echo "<p align='center'>Филиал: <label>".$row_manager['branch']."</label></p>
    <p align='center'>Контент-менеджер: <label>".$row_manager['manager']."</label></p>";?>

    <p align='center'>
        <input type='button' id = 'add' value='Добавить данные' onclick=" location.href='form.php'  ">
        <input type='hidden' id='hidden' value='false'>
	 <input type='button' name = 'all_records' id = 'all_records' value ='Вывести все данные'
	 ></p>
    <?
    $_SESSION['manager_name']=$row_manager['Login'];
    $_SESSION['id_branch']=$row_manager['idBranch'];

    if(($num_rows =  mysql_num_rows($result)) != 0) {
        $table.="<div id = 'status'>
        <form action='' id='manager' name='manager' class='manager' method='post' enctype='multipart/form-data' style='margin:20px;'>
        <table width='95%' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center' class = 'sortable'>
            <tbody>";
        $table.='<tr><td rowspan = "2"><div class = "vert" >Дата</div></td>

                                 <td rowspan = "2"><div class = "vert">Название ЦКП</div></td>
                                <td colspan = "2" >Количество обращений по телефону</td>
                                <td colspan = "2" >Количество посетителей ЦКП</td>
                                <td colspan = "2" >Количество презентаций</td>
                                <td colspan = "2" >Количество писем</td>
                                <td colspan = "5" >Количество вопросов</td>
                                <td rowspan = "2" > <div class = "vert" style="height:30px;width: 70px">Количество претензий к качеству ТВ сигнала</div></td>
                                <td rowspan = "2"><div class = "vert" style="width: 65px" >Количество жалоб на ЦКП</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Прочие вопросы</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Файлы</div></td>

                                </tr>

                                <tr ><td ><div class = "vert"> за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяцe</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"> <div class = "vert" style="height:30px;width: 75px">по отключенным передатчикам РТРС-2 «50-»</div> </td>
                                <td class = "header"><div class = "vert" style="width: 60px">о сроках запуска мульти-<br>плексов</div></td>
                                <td class = "header"><div class = "vert" style="width: 70px">о оборудо<br>вании</div></td>
                                <td class = "header"><div class = "vert" style="width: 70px">о региональ<br>ных врезках</div></td>
                                <td class = "header"><div class = "vert" style="width: 65px">о перерывах в вещании</div></td></tr>';
        while( $myrow = mysql_fetch_array($result)){
            $table.='<tr>';
            $table.='<td width = "40px" id = "Date" name="Date'.$myrow['idFacts'].'">'.$myrow['Date'].'
             <input type="hidden" name = "Date_select" id = "Date_select"  value = "' . $myrow['Date'] . '"/>
                <input type="hidden" name = "id" id = "id"  value = "' . $myrow['idFacts'] . '"/></td>';

            $table.='<td name="ckp_'.$myrow['idFacts'].'">ЦКП-'.$myrow['ckp'].' '.$myrow['city'].'</td>';

            $table.='<td class = "edit CountCallWithOpening '.$myrow['idFacts'].'">'.$myrow['CountCallWithOpening'].'
                <input type="hidden" name = "CountCallWithOpening" value = "'.$myrow['CountCallWithOpening'].'"/></td>  ';
            $table.='<td class = "edit CountCallCurrentMonth '.$myrow['idFacts'].'">'.$myrow['CountCallCurrentMonth'].'</td>';
            $table.='<td class = "edit CountPeopleWIthOpening '.$myrow['idFacts'].'">'.$myrow['CountPeopleWIthOpening'].'</td>';
            $table.='<td class = "edit CountPeopleCurrentMonth '.$myrow['idFacts'].'">'.$myrow['CountPeopleCurrentMonth'].'</td>';
            $table.='<td class = "edit CountPresentationWithOpening '.$myrow['idFacts'].'">'.$myrow['CountPresentationWithOpening'].'</td>';
            $table.='<td class = "edit CountPresentationCurrentMonth '.$myrow['idFacts'].'">'.$myrow['CountPresentationCurrentMonth'].'</td>';
            $table.='<td class = "edit CountLettersWithOpening '.$myrow['idFacts'].'">'.$myrow['CountLettersWithOpening'].'</td>';
            $table.='<td class = "edit CountLettersCurrentMonth '.$myrow['idFacts'].'">'.$myrow['CountLettersCurrentMonth'].'</td>';
            $table.='<td class = "edit CountQuestionsTransmitterOff'.$myrow['idFacts'].'">'.$myrow['CountQuestionsTransmitterOff'].'</td>';
            $table.='<td class = "edit CountQuestionsLaunchDateTansmitters '.$myrow['idFacts'].'" >'.$myrow['CountQuestionsLaunchDateTansmitters'].'</td>';
            $table.='<td class = "edit CountQuestionsEquipment '.$myrow['idFacts'].'">'.$myrow['CountQuestionsEquipment'].'</td>';
            $table.='<td class = "edit CountQuestionsRegionalIntervention '.$myrow['idFacts'].'">'.$myrow['CountQuestionsRegionalIntervention'].'</td>';
            $table.='<td class = "edit CountQuestionsBreaksInBroadcasting '.$myrow['idFacts'].'">'.$myrow['CountQuestionsBreaksInBroadcasting'].'</td>';
            $table.='<td class = "edit CountClaimsToQuality'.$myrow['idFacts'].'">'.$myrow['CountClaimsToQuality'].'</td>';
            $table.='<td  class = "edit CountComplaintsOfCCS '.$myrow['idFacts'].'">'.$myrow['CountComplaintsOfCCS'].'</td>';
            $table.='<td  class = "edit OtherQuestions '.$myrow['idFacts'].'"><textarea readonly="readonly" cols = "30" rows="3">'.$myrow['OtherQuestions'].'</textarea></td>';
            $table.='<td  class = " PathFile '.$myrow['idFacts'].'">'.filetolink($myrow['PathFile']).'</td>';
            $table.="</tr>";
        }
        $table.='</tbody> </table></form></div>';

        echo $table;


    }

    ?>

</div>
    <div ID = "toTop" ><img src="img/up.png"> </div>
</body>
<script>
    $(document).ready(function(){
        var date_db = $( "#Date_DB").val();

            if($('tr:contains('+ date_db +')')){
                $('tr:contains('+ date_db +')').css("background-color", "#d2f8d2");
                $( "input#add").val("Изменить данные");
            }
    });

    $('#all_records').click(function (){

        $.ajax({
            url:"all.php",
            dataType: 'html',
            data:{'ckp':"deiojdi"},
            method:"POST",
            success:function(data){

                $('#status').html(data);
                //document.getElementById("status").innerHTML=data;
                var date_db = $( "#Date_DB").val();

                if($('tr:contains('+ date_db +')')){
                    $('tr:contains('+ date_db +')').css("background-color", "#d2f8d2");
                        $( "input#add").val("Изменить данные");
                    }
            }
        })
    })
</script>

</html>
<?php
//}else{
//    header('Location: index.php');
//    echo '<p align="center">У вас нет прав для доступа к этой странице!</p>';
//
//}

