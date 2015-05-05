<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';
//if($_SESSION['Role']=='Гость' || $_SESSION['Role'] == 'Менеджер' ){
//    header('Location: index.html');
//    echo '<p align="center">У вас нет прав для просмотра этой страницы!</p>';
//}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Поиск</title>
    <link href="css/style_adm.css" rel="stylesheet" type="text/css" />
    <script src="jquery-1.7.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="js/EventHelpers.js">
    </script>
    <script type="text/javascript" src="js/cssQuery-p.js">
    </script>
    <script type="text/javascript" src="js/jcoglan.com/sylvester.js">
    </script>
    <script type="text/javascript" src="js/cssSandpaper.js">
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
        .header{
            height: 120px;
            width:50px;
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

    </style>

</head>

<body>
<?php

    if($_POST)
    {

        $month_year = rtrim($_POST['date']);
        $month = rtrim($_POST['date']);
        $monthto = rtrim($_POST['dateto']);

        $datetime = strtotime($month);
        $date = $month.'-'.date('t', $datetime);

        $datetime = strtotime($monthto);
        $dateto = $monthto.'-'.date('t', $datetime);

       // $manager = rtrim($_POST['manager']);
       // $ckp = rtrim($_POST['ckp']);
       // $branch = rtrim($_POST['branch']);
        //$district = rtrim($_POST['district']);

        $arr = implode(',', $_POST['district']);
       // echo "<b>$arr</b>";

            $branch = implode(',', $_POST['branch']);
           // echo "<b>$branch</b>";


            $ckp = implode(',', $_POST['ckp']);
           // echo "<b>$ckp</b>";
        $manager = implode(',', $_POST['manager']);
        //echo "<b>$manager</b>";

        if(empty($arr)) {
            $arr = -1;
        }
        if(empty($branch)){
                $branch = -1;
        }
        if(empty($ckp)){
            $ckp = -1;
        }
        if(empty($manager)){
            $manager = -1;
        }

        //where t.id in ($arr)
//$arr = implode(',', $branches);

        $strQuery = "SELECT idFacts, date_format(Date, '%Y.%m.%d') as Date,CreateDate, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions,
                            m.Name as manager, c.Name as ckp,c.City as city, d.Code as district_name,
                            Manager_idManager,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile ,b.Name as branch
                            FROM fact f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                           Where (d.idDistrict IN ($arr) OR -1 IN ($arr)) and (b.idBranch in ($branch) or -1 IN ($branch) ) and
                           (c.idCCS in ($ckp) or -1 IN ($ckp) )  and (m.idManager in ($manager) or -1 IN ($manager) )and
                           (Date BETWEEN '".$date."%' AND '".$dateto."%' OR Date LIKE '%".$month_year."%')
                           Group by f.idFacts Order by Date Desc";
//        and (b.idBranch in ($branch) or -1 IN ($branch) )
//                            and (c.idCCS in ($ckp) or -1 IN ($ckp) ) and (m.idManager in ($manager) or -1 IN ($manager) )
        //". $strWhere." Group by f.idFacts";
        //or (b.idBranch in ($branch)) or (c.idCCS in ($ckp))
        //or (m.idManager in ($manager)) and
        $result = mysql_query($strQuery)
        or die ("<p><b>Ошибка в обращении к БД. Невозможно выполнить запрос.
 Обновите страницу и попробуйте еще раз</b></p>" .mysql_error());
        require_once 'Classes/PHPExcel.php';
        $pExcel = new PHPExcel();
        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        // Название листа
        $aSheet->setTitle('Отчет');
        // Настройки шрифта
        $aSheet->getColumnDimension('A')->setWidth(6);
        $aSheet->getColumnDimension('B')->setWidth(20);
        $aSheet->getColumnDimension('C')->setWidth(20);
        $aSheet->getColumnDimension('D')->setWidth(20);
        $aSheet->getColumnDimension('E')->setWidth(20);
        $aSheet->getColumnDimension('F')->setWidth(20);
        $aSheet->getColumnDimension('G')->setWidth(10);
        $aSheet->getColumnDimension('W')->setWidth(50);
        $pExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

        $aSheet->setCellValue('A1','№');
        $aSheet->setCellValue('B1','Дата');
        $aSheet->setCellValue('C1','Менеджер');
        $aSheet->setCellValue('D1','Название ЦКП');
        $aSheet->setCellValue('E1','Город');
        $aSheet->setCellValue('F1','Филиал');
        $aSheet->setCellValue('G1','Округ');
        $aSheet->setCellValue('H1','Количество обращений по телефону за весь период c открытия');
        $aSheet->setCellValue('I1','Количество обращений по телефону в текущем месяце');
        $aSheet->setCellValue('J1','Количество посетителей ЦКП за весь период c открытия');
        $aSheet->setCellValue('K1','Количество посетителей ЦКП в текущем месяце');
        $aSheet->setCellValue('L1','Количество презентаций за весь период c открытия');
        $aSheet->setCellValue('M1','Количество презентаций в текущем месяце');
        $aSheet->setCellValue('N1','Количество писем за весь период c открытия');
        $aSheet->setCellValue('O1','Количество писем в текущем месяце');
        $aSheet->setCellValue('P1','Количество вопросов по отключенным передатчикам РТРС-2 «50-»');
        $aSheet->setCellValue('Q1','Количество вопросов о сроках запуска мультиплексов');
        $aSheet->setCellValue('R1','Количество вопросов о оборудовании');
        $aSheet->setCellValue('S1','Количество вопросов о региональных врезках');
        $aSheet->setCellValue('T1','Количество вопросов о перерывах в вещании');
        $aSheet->setCellValue('U1','Количество претензий к качеству ТВ сигнала');
        $aSheet->setCellValue('V1','Количество жалоб на ЦКП');
        $aSheet->setCellValue('W1','Прочие вопросы');
        $aSheet->setCellValue('X1','Файлы');

        $i = 1;
        while( $prd = mysql_fetch_assoc($result) ) {
            $aSheet->getRowDimension()->setRowHeight(30);
            $aSheet->setCellValue('A' . ($i + 1), $prd['idFacts']);
            $aSheet->setCellValue('B' . ($i + 1), $prd['CreateDate']);
            $aSheet->setCellValue('C' . ($i + 1), $prd['manager']);
            $aSheet->setCellValue('D' . ($i + 1), $prd['ckp']);
            $aSheet->setCellValue('E' . ($i + 1), $prd['city']);
            $aSheet->setCellValue('F' . ($i + 1), $prd['branch']);
            $aSheet->setCellValue('G' . ($i + 1), $prd['district_name']);
            $aSheet->setCellValue('H' . ($i + 1), $prd['CountCallWithOpening']);
            $aSheet->setCellValue('I' . ($i + 1), $prd['CountCallCurrentMonth']);
            $aSheet->setCellValue('J' . ($i + 1), $prd['CountPeopleWIthOpening']);
            $aSheet->setCellValue('K' . ($i + 1), $prd['CountPeopleCurrentMonth']);
            $aSheet->setCellValue('L' . ($i + 1), $prd['CountPresentationWithOpening']);
            $aSheet->setCellValue('M' . ($i + 1), $prd['CountPresentationCurrentMonth']);
            $aSheet->setCellValue('N' . ($i + 1), $prd['CountLettersWithOpening']);
            $aSheet->setCellValue('O' . ($i + 1), $prd['CountLettersCurrentMonth']);
            $aSheet->setCellValue('P' . ($i + 1), $prd['CountClaimsToQuality']);
            $aSheet->setCellValue('Q' . ($i + 1), $prd['CountQuestionsTransmitterOff']);
            $aSheet->setCellValue('R' . ($i + 1), $prd['CountQuestionsLaunchDateTansmitters']);
            $aSheet->setCellValue('S' . ($i + 1), $prd['CountQuestionsEquipment']);
            $aSheet->setCellValue('T' . ($i + 1), $prd['CountQuestionsRegionalIntervention']);
            $aSheet->setCellValue('U' . ($i + 1), $prd['CountQuestionsBreaksInBroadcasting']);
            $aSheet->setCellValue('V' . ($i + 1), $prd['CountComplaintsOfCCS']);
            $aSheet->setCellValue('W' . ($i + 1), $prd['OtherQuestions']);
            $aSheet->setCellValue('X' . ($i + 1), $prd['PathFile']);

            $i++;
        }
        $formula = '=SUM(H2:H'.($i).')';
        $formula1 = '=SUM(I2:I'.($i).')';
        $formula2 = '=SUM(J2:J'.($i).')';
        $formula3 = '=SUM(K2:K'.($i).')';
        $formula4 = '=SUM(L2:L'.($i).')';
        $formula5 = '=SUM(M2:M'.($i).')';
        $formula6 = '=SUM(N2:N'.($i).')';
        $formula7 = '=SUM(O2:O'.($i).')';
        $formula8 = '=SUM(P2:P'.($i).')';
        $formula9 = '=SUM(Q2:Q'.($i).')';
        $formula10 = '=SUM(R2:R'.($i).')';
        $formula11 = '=SUM(S2:S'.($i).')';
        $formula12 = '=SUM(T2:T'.($i).')';
        $formula13 = '=SUM(U2:U'.($i).')';
        $formula14 = '=SUM(V2:V'.($i).')';

        $aSheet->setCellValue('G'.($i+1), 'Всего');
        $aSheet->setCellValue('H'.($i+1), $formula);
        $aSheet->setCellValue('I'.($i+1), $formula1);
        $aSheet->setCellValue('J'.($i+1), $formula2);
        $aSheet->setCellValue('K'.($i+1), $formula3);
        $aSheet->setCellValue('L'.($i+1), $formula4);
        $aSheet->setCellValue('M'.($i+1), $formula5);
        $aSheet->setCellValue('N'.($i+1), $formula6);
        $aSheet->setCellValue('O'.($i+1), $formula7);
        $aSheet->setCellValue('P'.($i+1), $formula8);
        $aSheet->setCellValue('Q'.($i+1), $formula9);
        $aSheet->setCellValue('R'.($i+1), $formula10);
        $aSheet->setCellValue('S'.($i+1), $formula11);
        $aSheet->setCellValue('T'.($i+1), $formula12);
        $aSheet->setCellValue('U'.($i+1), $formula13);
        $aSheet->setCellValue('V'.($i+1), $formula14);

        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel2007');
        $objWriter->save('Export.xlsx');
        $result = mysql_query($strQuery)
        or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
        function filetolink($files){
            $files = explode(',', $files);
            $links = array();
            foreach ($files as $items) {
                $fileName = explode(' ', $items);
                $originName = array_splice($fileName, 2);
                $name =  implode(' ' , $originName);
                $link = "<a href='/uploads/{$items}'>{$name}</a>";
                array_push($links, $link);
            }

            return implode('<br>', $links);
        }

        if(($num_rows =  mysql_num_rows($result)) != 0)
        {
            $table ="
            <div class = 'result'> </div>
            <form method='post' action='edit_new.php' id='edit_new' name='edit_new' class='edit_new' enctype='multipart/form-data' >
                 <table width='100%' border='1' cellspacing='0' cellpadding='0' id='fact' align = 'center' class = 'tableEdit'>
                     <tbody>\n";
            $table.='<tr><td rowspan = "2"><div class = "vert" >Дата</div></td>
                                <td rowspan = "2"  ><div class = "vert"  >Контент-менеджер</div></td>
                                <td rowspan = "2"><div class = "vert">Название ЦКП<br>Город</div></td>
                                <td rowspan = "2"><div class = "vert">Филиал</div></td>
                                <td rowspan = "2"><div class = "vert">Округ</div></td>
                                <td colspan = "2" >Количество обращений по телефону</td>
                                <td colspan = "2" >Количество посетителей ЦКП</td>
                                <td colspan = "2" >Количество презентаций</td>
                                <td colspan = "2" >Количество писем</td>

                                <td colspan = "5" >Количество вопросов</td>

                                <td rowspan = "2" > <div class = "vert">Количество претензий к качеству ТВ сигнала</div></td>
                                <td rowspan = "2"><div class = "vert" >Количество жалоб на ЦКП</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Прочие вопросы</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Файлы</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Редактировать</div></td>
                                <td  rowspan = "2" ><div class = "vert" >Удалить</div></td>

                                </tr>

                                <tr ><td ><div class = "vert"> за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяцe</div></td>
                                <td class = "header"><div class = "vert">за весь период c открытия</div></td>

                                <td class = "header"><div class = "vert">в т ч текущем месяце</div></td>
                                <td class = "header"> <div class = "vert" >по отключенным передатчикам РТРС-2 «50-»</div> </td>
                                <td class = "header"><div class = "vert">о сроках запуска мультиплексов</div></td>
                                <td class = "header"><div class = "vert">о оборудовании</div></td>
                                <td class = "header"><div class = "vert">о региональных врезках</div></td>
                                <td class = "header"><div class = "vert">о перерывах в вещании</div></td></tr>';
                while($myrow = mysql_fetch_array($result)){
                    $file = filetolink($myrow['PathFile']);
                $table.='<tr>';
                $table.='<td width = "40px" name="Date'.$myrow['idFacts'].'">'.$myrow['Date'].'</td>';
                $table.='<td name="Name_'.$myrow['idFacts'].'">'.$myrow['manager'].'</td>';
                $table.='<td name="ckp_'.$myrow['idFacts'].'">ЦКП-'.$myrow['ckp'].' '.$myrow['city'].'</td>';
                $table.='<td name="branch_'.$myrow['idFacts'].'">'.$myrow['branch'].'</td>';
                $table.='<td name="district_name_'.$myrow['idFacts'].'" >'.$myrow['district_name'].'</td>';
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

                $table.='<td  class = "edit OtherQuestions '.$myrow['idFacts'].'"><textarea cols = "30" rows="3" readonly id = "textarea">'.$myrow['OtherQuestions'].'</textarea></td>';
                $table.="<td  class = ' PathFile ".$myrow['idFacts']."'>".$file."</td>";
                $table.="<td><a href='change.php?id=".$myrow['idFacts']."'>Редактировать</a></td>";
                    $table.= '<td><a href="delete_search.php?id=' .$myrow['idFacts'].'" onClick="return window.confirm(\'Вы уверены, что хотите удалить данные?\')">Удалить</a></td>';
                    $table.="</tr>";
            }
            $table.='</tbody> </table></form>	';
            echo $table;

            echo '<p><a href="Export.xlsx" >Получить файл с отчетом </a></p>';
        }
        else { echo '
             <p align="center">По вашему запросу нету данных!</p> ';
        }
    }
    ?>

</body>
</html>
