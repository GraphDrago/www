<?php
    ini_set("default_charset","UTF-8");
    ini_set('display_errors', 'Off');
    include 'connect.php';
session_start();
    include 'auth.php';
$manager_name = $_SESSION['manager_name'];
if (isset($_POST['ajaxRequest']) ){
    $type = $_POST['ajaxRequest'];
	switch ($type) {
			case 'CountPeopleWIthOpening':
				$idckp = $_POST['idckp'];


                $date2 = $_POST['date'];
                $datetime = strtotime($date2);
                $month= $date2.'-'.date('t', $datetime);

                if($month != ''){

//                $datetime = strtotime($month);
//                $date = $month.'-'.date('t', $datetime);
                $q = mysql_query("Select idManager From manager Where Login = '$manager_name'");
                    $idm = mysql_fetch_assoc($q);
                    $idManager = $idm['idManager'];
                $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts From fact
                                                     Where  CCS_idCCS = $idckp and Date  like '".$month."%'")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.222'
                )));
                $idfact = mysql_fetch_assoc($sql_date);
                $id_fact = $idfact['idFacts'];
                $date_fact = $idfact['Date'];

                if($month == $date_fact ){
                    $value = mysql_query("SELECT Date,CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff,
                            CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting,
                             CountComplaintsOfCCS, OtherQuestions,CCS_idCCS,GROUP_CONCAT(a.PathFile, ',') as PathFile,idFacts
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                        left join attachment a ON f.idFacts = a.Fact_idFacts
                       WHERE idFacts = $id_fact  Group by f.idFacts LIMIT 1")
                    or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.33'
                    )));
                   // $i=0;
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
                        return implode('',$links);
                    }




                    $item = mysql_fetch_assoc($value);
                    $i1 = $item['CountCallWithOpening'];
                    $i2 = $item['CountPeopleWIthOpening'];
                    $i3 = $item['CountPresentationWithOpening'];
                    $i4 = $item['CountLettersWithOpening'];

                    $i5 = $item['CountCallCurrentMonth'];
                    $i6 = $item['CountPeopleCurrentMonth'];
                    $i7 = $item['CountPresentationCurrentMonth'];
                    $i8 = $item['CountLettersCurrentMonth'];

                    $i9 = $item['CountClaimsToQuality'];
                    $i10 = $item['CountQuestionsTransmitterOff'];
                    $i11 = $item['CountQuestionsLaunchDateTansmitters'];
                    $i12 = $item['CountQuestionsEquipment'];

                    $i13 = $item['CountQuestionsRegionalIntervention'];
                    $i14 = $item['CountQuestionsBreaksInBroadcasting'];
                    $i15 = $item['CountComplaintsOfCCS'];
                    $i16 = $item['OtherQuestions'];
                    $i17 = filetolink($item['PathFile']);

                    if($i1 == 0)
                    {
                        $val1 = mysql_query("SELECT Date,CountCallWithOpening, idFacts
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                        left join attachment a ON f.idFacts = a.Fact_idFacts
                       WHERE  f.CCS_idCCS = $idckp and CountCallWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.44'
                        )));
                        if($num_rows =  mysql_num_rows($val1) != 0) {
                            $it = mysql_fetch_assoc($val1);
                            $i1 = $it['CountCallWithOpening'];
                        }else{
                            $i1 =0;
                        }
                    }
                    if($i2 == 0)
                    {
                        $val2 = mysql_query("SELECT Date,CountPeopleWIthOpening, idFacts
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                        left join attachment a ON f.idFacts = a.Fact_idFacts
                       WHERE  f.CCS_idCCS = $idckp and CountPeopleWIthOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.55'
                        )));
                        if($num_rows =  mysql_num_rows($val2) != 0) {
                            $it = mysql_fetch_assoc($val2);
                            $i2 = $it['CountPeopleWIthOpening'];
                        }else{
                            $i2 =0;
                        }
                    }
                    if($i3 == 0)
                    {
                        $val3 = mysql_query("SELECT Date,CountPresentationWithOpening, idFacts
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                        left join attachment a ON f.idFacts = a.Fact_idFacts
                       WHERE   f.CCS_idCCS = $idckp and CountPresentationWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.66'
                        )));
                        if($num_rows =  mysql_num_rows($val3) != 0) {
                            $it = mysql_fetch_assoc($val3);
                            $i3 = $it['CountPresentationWithOpening'];
                        }else{
                            $i3 =0;
                        }
                    }
                    if($i4 == 0)
                    {
                        $val4 = mysql_query("SELECT Date,CountLettersWithOpening, idFacts
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                        left join attachment a ON f.idFacts = a.Fact_idFacts
                       WHERE   f.CCS_idCCS = $idckp and CountLettersWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.77'
                        )));
                        if($num_rows =  mysql_num_rows($val4) != 0) {
                            $it = mysql_fetch_assoc($val4);
                            $i4 = $it['CountLettersWithOpening'];
                        }else{
                            $i4 =0;
                        }
                    }
                    echo json_encode(array('count1'=>$i1, 'count2' => $i2,'count3'=>$i3, 'count4' => $i4,
                        'count5'=>$i5, 'count6' => $i6,'count7'=>$i7, 'count8' => $i8,
                        'count9'=>$i9, 'count10' => $i10,'count11'=>$i11, 'count12' => $i12,
                        'count13'=>$i13, 'count14' => $i14,'count15'=>$i15, 'count16' => $i16, 'count17' => $i17));
                    //echo $item['CountPeopleWIthOpening'];

                }else{

                    $val = mysql_query("SELECT Date,CountCallWithOpening,
                            CountPeopleWIthOpening,  CountPresentationWithOpening,
                               CountLettersWithOpening,CCS_idCCS
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                       WHERE   CCS_idCCS = $idckp
                       ORDER BY Date DESC LIMIT 1")
                    or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.88'
                    )));
                    $item = mysql_fetch_assoc($val);
                    $i1 = $item['CountCallWithOpening'];
                    $i2 = $item['CountPeopleWIthOpening'];
                    $i3 = $item['CountPresentationWithOpening'];
                    $i4 = $item['CountLettersWithOpening'];

                    if($i1 == 0)
                    {
                        $val1 = mysql_query("SELECT Date,CountCallWithOpening

                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                       WHERE   f.CCS_idCCS = $idckp and CountCallWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.99'
                        )));
                        if($num_rows =  mysql_num_rows($val1) != 0) {
                            $it = mysql_fetch_assoc($val1);
                            $i1 = $it['CountCallWithOpening'];
                        }else{
                            $i1 =0;
                        }
                    }

                    if($i2 == 0)
                    {
                        $val2 = mysql_query("SELECT Date,CountPeopleWIthOpening

                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                       WHERE   f.CCS_idCCS = $idckp and CountPeopleWIthOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.1010'
                        )));
                        if($num_rows =  mysql_num_rows($val2) != 0) {
                            $it = mysql_fetch_assoc($val2);
                            $i2 = $it['CountPeopleWIthOpening'];
                        }else{
                            $i2 =0;
                        }

                    }
                    if($i3 == 0)
                    {
                        $val3 = mysql_query("SELECT Date,CountPresentationWithOpening
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                       WHERE  f.CCS_idCCS = $idckp and CountPresentationWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.233')));
                        if(mysql_num_rows($val4) != 0) {
                            $it = mysql_fetch_assoc($val3);
                            $i3 = $it['CountPresentationWithOpening'];
                        }else{
                            $i3 =0;
                        }



                    }
                    if($i4 == 0)
                    {
                        $val4 = mysql_query("SELECT Date,CountLettersWithOpening
                       FROM fact f
                       INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                       INNER JOIN manager m ON f.Manager_idManager = m.idManager
                       INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                       INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                       WHERE  f.CCS_idCCS = $idckp and CountLettersWithOpening <> 0 and  Date > '2015-01'
                       ORDER BY Date DESC LIMIT 1")
                        or die (json_encode(array('res' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.899')));
                        if(mysql_num_rows($val4) != 0) {
                            $it = mysql_fetch_assoc($val4);
                            $i4 = $it['CountLettersWithOpening'];
                        }else{
                            $i4 =0;
                        }

                    }



                    echo json_encode(array('count1'=>$i1, 'count2' => $i2,'count3'=>$i3, 'count4' => $i4));
                    //echo $item['CountPeopleWIthOpening'];


                }
                }else{
                    echo (json_encode(array('res' => 'Выберите дату на которую вносятся данные')));
                }
                break;
			default:
				break;
		}	
	die;
}
