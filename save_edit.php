<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';

//if($_SESSION['Role']=='Гость' || $_SESSION['Role'] == 'Менеджер' ){
//    header('Location: index.html');
//    echo 'У вас нет прав для просмотра этой страницы!';
//}

    $id = $_POST['id_facts'];
    $count = $_POST['count'];
    $manager = $_POST['manager'];
    $ckp_name = $_POST['ckp_name'];
$CreateDate_ = date('Y-m-d H-i-s');
$Manager_name =  $_SESSION['Name_Manager'];



    if($count>0) {
        for ($i = 0; $i < $count; $i++) {
            if (!(strpos($_FILES['userFile']['name'][$i], 'php') === false)) {
                die(json_encode(array('response' =>"Нельзя загружать скрипт!")));
            } else {
                $blacklist = array(".php", ".phtml", ".php3", ".php4", ".html", ".htm");
                for ($i = 0; $i < $count; $i++) {
                    foreach ($blacklist as $item) {
                        if (preg_match("/$item\$/i", $_FILES['userFile']['name'][$i])) {
                            die(json_encode(array('response' =>"Нельзя загружать скрипт!")));
                            exit;
                        }
                    }
                }
            }
        }
        $uploadDir = '/var/www/html/uploads/';
        for ($i = 0; $i < $count; $i++) {

            $uploadFile = $CreateDate_ . " " . ($_FILES['userFile']['name'][$i]);
            echo $uploadFile;
            $size = $_FILES['userFile']['size'][$i];
            $file = $_FILES['userFile']['name'][$i];
            if (!is_writable($uploadDir)) {

                die(json_encode(array('response' =>'Вы не можете загрузить в указанную папку; смените CHMOD на 777.')));
            } else {
                if (is_uploaded_file($_FILES['userFile']['tmp_name'][$i])) {
                    if ($size < 5242880) {
                        if (!move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/' . $uploadFile)) {

                            die(json_encode(array('response' =>'Проверьте путь!')));

                        } else {

                                $upload = mysql_query("INSERT INTO attachment (PathFile,Fact_idFacts) VALUES('$uploadFile','$id')")
                                or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос6.')));
                                move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/' . $uploadFile);
                            if($upload){
                                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate_', '$Manager_name','$ckp_name',N'Таблица вложений',N'INSERT')")
                                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                                )));
                            }
                        }
                    } else {
                       echo json_encode(array('response' =>'Ошибка! Размер файла более 5МБ!'));

                    }
                }
            }
        }
    }

            $strQuery = "SELECT idFacts, Date, CountCallWithOpening, CountCallCurrentMonth,
                            CountPeopleWIthOpening,  CountPeopleCurrentMonth, CountPresentationWithOpening,
                             CountPresentationCurrentMonth, CountLettersWithOpening,
                            CountLettersCurrentMonth,CountClaimsToQuality, CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                            CountQuestionsRegionalIntervention,  CountQuestionsBreaksInBroadcasting, CountComplaintsOfCCS, OtherQuestions
                            FROM
                            fact as  f
                            INNER JOIN ccs c ON f.CCS_idCCS = c.idCCS
                            INNER JOIN manager m ON f.Manager_idManager = m.idManager
                            INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                            left join attachment a ON f.idFacts = a.Fact_idFacts
                            Where f.idFacts='$id'";

        $res = mysql_query($strQuery )
        or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос.7')));
        $row = mysql_fetch_array($res);
        $id_facts = mysql_query("SELECT idFacts, Date FROM fact ORDER BY idFacts DESC LIMIT 1")
        or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос.3')));
        $row_id = mysql_fetch_array($id_facts);


            $CountCallCurrentMonth = ($_POST['CountCallCurrentMonth']);
            $CountCallCurrentMonth_new = ($_POST['CountCallCurrentMonth'])- $row['CountCallCurrentMonth'];
          $CountCallWithOpening =($_POST['CountCallWithOpening']) + $CountCallCurrentMonth_new;

           $CountPeopleCurrentMonth = ($_POST['CountPeopleCurrentMonth']);
            $CountPeopleCurrentMonth_new = ($_POST['CountPeopleCurrentMonth'])- $row['CountPeopleCurrentMonth'];
           $CountPeopleWIthOpening = ($_POST['CountPeopleWIthOpening']) + $CountPeopleCurrentMonth_new;

          $CountPresentationCurrentMonth = ($_POST['CountPresentationCurrentMonth']);
            $CountPresentationCurrentMonth_new = ($_POST['CountPresentationCurrentMonth'])- $row['CountPresentationCurrentMonth'];
           $CountPresentationWithOpening = ($_POST['CountPresentationWithOpening']) +  $CountPresentationCurrentMonth_new;

            $CountLettersCurrentMonth = ($_POST['CountLettersCurrentMonth']);
            $CountLettersCurrentMonth_new = ($_POST['CountLettersCurrentMonth'])- $row['CountLettersCurrentMonth'];
            $CountLettersWithOpening = ($_POST['CountLettersWithOpening']) + $CountLettersCurrentMonth_new;

            $CountClaimsToQuality = ($_POST['CountClaimsToQuality']);
            $CountQuestionsTransmitterOff = ($_POST['CountQuestionsTransmitterOff']);
            $CountQuestionsLaunchDateTansmitters = ($_POST['CountQuestionsLaunchDateTansmitters']);
            $CountQuestionsEquipment = ($_POST['CountQuestionsEquipment']);
            $CountQuestionsRegionalIntervention = ($_POST['CountQuestionsRegionalIntervention']);
            $CountQuestionsBreaksInBroadcasting = ($_POST['CountQuestionsBreaksInBroadcasting']);
            $CountComplaintsOfCCS = ($_POST['CountComplaintsOfCCS']);
            $OtherQuestions = ($_POST['OtherQuestions']);
//echo $OtherQuestions;
    if($CountClaimsToQuality != '' &&
        $CountQuestionsTransmitterOff != '' && $CountQuestionsLaunchDateTansmitters != ''
        && $CountQuestionsEquipment  != '' && $CountQuestionsRegionalIntervention != '' && $CountQuestionsBreaksInBroadcasting != '' &&
        $CountComplaintsOfCCS != '') {

        if($row_id['idFacts'] != $id ){
                $d = $row['Date'];
                $query_update = "UPDATE fact
            INNER JOIN ccs c ON CCS_idCCS = c.idCCS
            INNER JOIN manager m ON Manager_idManager = m.idManager
            SET CountCallWithOpening= CountCallWithOpening +'$CountCallCurrentMonth_new',
                    CountPeopleWIthOpening = CountPeopleWIthOpening +'$CountPeopleCurrentMonth_new ',
                    CountPresentationWithOpening= CountPresentationWithOpening+'$CountPresentationCurrentMonth_new',
                    CountLettersWithOpening= CountLettersWithOpening + '$CountLettersCurrentMonth_new'
                     WHERE Date > '$d' AND idFacts <> $id and Manager_idManager = $manager";

                $result1 = mysql_query($query_update)
                or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос.9'.mysql_error())));
            if($result1){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate_', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                )));
            }
                $query = "UPDATE fact SET CountCallWithOpening='$CountCallWithOpening',
                    CountCallCurrentMonth ='$CountCallCurrentMonth',
                    CountPeopleWIthOpening ='$CountPeopleWIthOpening',  CountPeopleCurrentMonth='$CountPeopleCurrentMonth',
                    CountPresentationWithOpening='$CountPresentationWithOpening',  CountPresentationCurrentMonth='$CountPresentationCurrentMonth',
                    CountLettersWithOpening='$CountLettersWithOpening',  CountLettersCurrentMonth='$CountLettersCurrentMonth',
                    CountClaimsToQuality='$CountClaimsToQuality',CountQuestionsTransmitterOff='$CountQuestionsTransmitterOff',
                    CountQuestionsLaunchDateTansmitters='$CountQuestionsLaunchDateTansmitters', CountQuestionsEquipment='$CountQuestionsEquipment',
                    CountQuestionsRegionalIntervention='$CountQuestionsRegionalIntervention', CountQuestionsBreaksInBroadcasting='$CountQuestionsBreaksInBroadcasting',
                    CountComplaintsOfCCS='$CountComplaintsOfCCS',OtherQuestions='$OtherQuestions'
                    WHERE idFacts='$id'";

                $result = mysql_query($query)
                or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос.8')));
            if($result){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate_', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                )));
            }

        }else {
            $query_ = "UPDATE fact SET CountCallWithOpening='$CountCallWithOpening',
                    CountCallCurrentMonth ='$CountCallCurrentMonth',
                    CountPeopleWIthOpening ='$CountPeopleWIthOpening',  CountPeopleCurrentMonth='$CountPeopleCurrentMonth',
                    CountPresentationWithOpening='$CountPresentationWithOpening',  CountPresentationCurrentMonth='$CountPresentationCurrentMonth',
                    CountLettersWithOpening='$CountLettersWithOpening',  CountLettersCurrentMonth='$CountLettersCurrentMonth',
                    CountClaimsToQuality='$CountClaimsToQuality',CountQuestionsTransmitterOff='$CountQuestionsTransmitterOff',
                    CountQuestionsLaunchDateTansmitters='$CountQuestionsLaunchDateTansmitters', CountQuestionsEquipment='$CountQuestionsEquipment',
                    CountQuestionsRegionalIntervention='$CountQuestionsRegionalIntervention', CountQuestionsBreaksInBroadcasting='$CountQuestionsBreaksInBroadcasting',
                    CountComplaintsOfCCS='$CountComplaintsOfCCS',OtherQuestions='$OtherQuestions'
                    WHERE idFacts='$id'";

            $result3 = mysql_query($query_)
            or die (json_encode(array('response' =>'Ошибка при обращении к БД. Невозможно выполнить запрос.10')));
            if($result3){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate_', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                )));
            }
        }
        if ($result == 'true' || $result1 == 'true' || $result3 == 'true') {
            echo json_encode(array('add' => 'Данные успешно сохранены.'));

        } else {
            echo json_encode(array('add' => 'Неудалось выполнить запрос.'));

        }
    }else{
        echo json_encode(array('response' => 'Заполните все поля формы!'));
    }

