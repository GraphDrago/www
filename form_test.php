<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
include 'connect.php';
session_start();
include 'auth.php';
header('Content-Type: application/json');
header('Charset: utf-8');

if (isset($_POST)){

    $CreateDate = date('Y-m-d H-i-s');
    $count = $_POST['count'];
    $managers =  $_SESSION['manager'];
    $manager =  $_POST['managers_name'];

    $dates = $_POST['dates'];
    $datetime = strtotime($dates);
    $date= $dates.'-'.date('t', $datetime);

    $branch= $_POST['branch'];
    $district= $_POST['district'];

    //$ckp = ($_POST['ckp']);
    $ckp_id = ($_POST['ckp']);
    $city = $_POST['city'];
    $ckp_name = $_POST['ckp_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $phone= $_POST['phone'];
    $openingHours = $_POST['openingHours'];

    $res =  mysql_query ("SELECT b.Name, m.Login, m.idManager, b.idBranch
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                                          WHERE b.Name = '".$branch."' and m.Login = $manager")
    or (json_encode(array('response' => 'Ошибка при обращении к БД.
            Попробуйте обновить страницу или обратится к администратору.23'
    )));
    $data = mysql_fetch_assoc($res);
    $idManager = $data['idManager'];
    $num = mysql_num_rows($res);
    $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts,
CountCallCurrentMonth,CountPeopleCurrentMonth,  CountPresentationCurrentMonth,
                              CountLettersCurrentMonth From fact
                                                     Where  Manager_idManager='$idManager' and  CCS_idCCS = $ckp_id and Date  like '".$date."%'")
    or die (json_encode(array('response' => print_r($_POST)

    )));
    $idfact = mysql_fetch_assoc($sql_date);
    $date_facts = $idfact['Date'];

    if($date ==  $date_facts){
        $CountCallWithOpening_prev =($_POST['CountCallWithOpening']);
        $CountCallCurrentMonth_new = ($_POST['CountCallCurrentMonth']);
        $CountCallCurrentMonth_old = ($idfact['CountCallCurrentMonth']);
        $CountCallWithOpening = $CountCallWithOpening_prev +( $CountCallCurrentMonth_new -$CountCallCurrentMonth_old);
        $CountCallCurrentMonth = ($_POST['CountCallCurrentMonth']);

        $CountPeopleWIthOpening_prev = ($_POST['CountPeopleWIthOpening']);
        $CountPeopleCurrentMonth_new = ($_POST['CountPeopleCurrentMonth']);
        $CountPeopleCurrentMonth = ($_POST['CountPeopleCurrentMonth']);
        $CountPeopleCurrentMonth_old = ($idfact['CountPeopleCurrentMonth']);
        $CountPeopleWIthOpening = $CountPeopleWIthOpening_prev + ($CountPeopleCurrentMonth_new-$CountPeopleCurrentMonth_old);

        $CountPresentationWithOpening_prev = ($_POST['CountPresentationWithOpening']);
        $CountPresentationCurrentMonth_new = ($_POST['CountPresentationCurrentMonth']);
        $CountPresentationCurrentMonth_old = ($idfact['CountPresentationCurrentMonth']);
        $CountPresentationCurrentMonth = ($_POST['CountPresentationCurrentMonth']);
        $CountPresentationWithOpening = $CountPresentationWithOpening_prev +($CountPresentationCurrentMonth_new-$CountPresentationCurrentMonth_old);

        $CountLettersWithOpening_prev = ($_POST['CountLettersWithOpening']);
        $CountLettersCurrentMonth = ($_POST['CountLettersCurrentMonth']);
        $CountLettersCurrentMonth_new = ($_POST['CountLettersCurrentMonth']);
        $CountLettersCurrentMonth_old = ($idfact['CountLettersCurrentMonth']);
        $CountLettersWithOpening = $CountLettersWithOpening_prev+($CountLettersCurrentMonth_new-$CountLettersCurrentMonth_old);

        $CountClaimsToQuality = ($_POST['CountClaimsToQuality']);
        $CountQuestionsTransmitterOff = ($_POST['CountQuestionsTransmitterOff']);
        $CountQuestionsLaunchDateTansmitters = ($_POST['CountQuestionsLaunchDateTansmitters']);
        $CountQuestionsEquipment = ($_POST['CountQuestionsEquipment']);
        $CountQuestionsRegionalIntervention = ($_POST['CountQuestionsRegionalIntervention']);
        $CountQuestionsBreaksInBroadcasting = ($_POST['CountQuestionsBreaksInBroadcasting']);
        $CountComplaintsOfCCS = ($_POST['CountComplaintsOfCCS']);
        $OtherQuestions = ($_POST['OtherQuestions']);
    }else{
        $CountCallWithOpening_prev =($_POST['CountCallWithOpening']);
        $CountCallCurrentMonth = ($_POST['CountCallCurrentMonth']);
        $CountCallWithOpening = $CountCallWithOpening_prev + $CountCallCurrentMonth;

        $CountPeopleWIthOpening_prev = ($_POST['CountPeopleWIthOpening']);
        $CountPeopleCurrentMonth = ($_POST['CountPeopleCurrentMonth']);
        $CountPeopleWIthOpening = $CountPeopleWIthOpening_prev + $CountPeopleCurrentMonth;

        $CountPresentationWithOpening_prev = ($_POST['CountPresentationWithOpening']);
        $CountPresentationCurrentMonth = ($_POST['CountPresentationCurrentMonth']);
        $CountPresentationWithOpening = $CountPresentationWithOpening_prev + $CountPresentationCurrentMonth;

        $CountLettersWithOpening_prev = ($_POST['CountLettersWithOpening']);
        $CountLettersCurrentMonth = ($_POST['CountLettersCurrentMonth']);
        $CountLettersWithOpening = $CountLettersWithOpening_prev+$CountLettersCurrentMonth;

        $CountClaimsToQuality = ($_POST['CountClaimsToQuality']);
        $CountQuestionsTransmitterOff = ($_POST['CountQuestionsTransmitterOff']);
        $CountQuestionsLaunchDateTansmitters = ($_POST['CountQuestionsLaunchDateTansmitters']);
        $CountQuestionsEquipment = ($_POST['CountQuestionsEquipment']);
        $CountQuestionsRegionalIntervention = ($_POST['CountQuestionsRegionalIntervention']);
        $CountQuestionsBreaksInBroadcasting = ($_POST['CountQuestionsBreaksInBroadcasting']);
        $CountComplaintsOfCCS = ($_POST['CountComplaintsOfCCS']);
        $OtherQuestions = ($_POST['OtherQuestions']);
    }




    if($CreateDate !='' && $date != '' && $ckp_id != ''  && $CountCallCurrentMonth != '' &&
        $CountPeopleCurrentMonth != ''  && $CountPresentationCurrentMonth != ''
        && $CountLettersCurrentMonth != '' && $CountClaimsToQuality != '' &&
        $CountQuestionsTransmitterOff != '' && $CountQuestionsLaunchDateTansmitters != ''
        && $CountQuestionsEquipment  != '' && $CountQuestionsRegionalIntervention != '' && $CountQuestionsBreaksInBroadcasting != '' &&
        $CountComplaintsOfCCS != '')
    {

        if($count>0) {
            for ($i=0; $i<$count; $i++){
                if(!(strpos($_FILES['userFile']['name'][$i],'php') === false)) {
                    die('Нельзя загружать скрипт!');}
                else{
                    $blacklist = array(".php", ".phtml", ".php3", ".php4", ".html", ".htm");
                    for ($i=0; $i<$count; $i++){
                        foreach ($blacklist as $item){
                            if(preg_match("/$item\$/i", $_FILES['userFile']['name'][$i])){
                                die('Нельзя загружать скрипт!'); exit;
                            }
                        }
                    }
                }
            }
            $res =  mysql_query ("SELECT b.Name, m.Login, m.idManager, b.idBranch, m.Name as manager
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                                          WHERE b.Name = '".$branch."' and m.Login = $manager ")
            or (json_encode(array('response' => 'Ошибка при обращении к БД.
                    Попробуйте обновить страницу или обратится к администратору.1'
            )));
            $data = mysql_fetch_assoc($res);
            $idManager = $data['idManager'];
            $Manager_name = $data['manager'];
            $sql = mysql_query("UPDATE ccs SET City = '$city', Address ='$address',
                                                       ContactPerson = '$contact', `E-Mail`='$email',
                                                        OpeningHours='$openingHours', Phone = '$phone'
                                                        Where idCCS='$ckp_id'")
            or (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.2')));
            if($sql){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.Невозможно выполнить запрос.
                        Попробуйте обновить страницу или обратится к администратору.3'
                )));
            }

            $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts From fact
                                                     Where CCS_idCCS='$ckp_id' and Manager_idManager='$idManager' and Date like '".$date."%'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.4'
            )));
            $idfact = mysql_fetch_assoc($sql_date);
            $id_fact = $idfact['idFacts'];
            $date_fact = $idfact['Date'];

            if($date == $date_fact ){
                $result1 = mysql_query("UPDATE fact SET CountCallWithOpening='$CountCallWithOpening',
                    CountCallCurrentMonth ='$CountCallCurrentMonth',
                    CountPeopleWIthOpening ='$CountPeopleWIthOpening',  CountPeopleCurrentMonth='$CountPeopleCurrentMonth',
                    CountPresentationWithOpening='$CountPresentationWithOpening',  CountPresentationCurrentMonth='$CountPresentationCurrentMonth',
                    CountLettersWithOpening='$CountLettersWithOpening',  CountLettersCurrentMonth='$CountLettersCurrentMonth',
                    CountClaimsToQuality='$CountClaimsToQuality',CountQuestionsTransmitterOff='$CountQuestionsTransmitterOff',
                    CountQuestionsLaunchDateTansmitters='$CountQuestionsLaunchDateTansmitters', CountQuestionsEquipment='$CountQuestionsEquipment',
                    CountQuestionsRegionalIntervention='$CountQuestionsRegionalIntervention', CountQuestionsBreaksInBroadcasting='$CountQuestionsBreaksInBroadcasting',
                    CountComplaintsOfCCS='$CountComplaintsOfCCS',OtherQuestions='$OtherQuestions'
                    WHERE idFacts='$id_fact'")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.5')));
                if($result1){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE1')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.Невозможно выполнить запрос.
                            Попробуйте обновить страницу или обратится к администратору.6'
                    )));
                }
            }else {
                $res =  mysql_query ("SELECT b.Name, m.Login, m.idManager, b.idBranch
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                                          WHERE b.Name = '".$branch."' and m.Login = $manager")
                or (json_encode(array('response' => 'Ошибка при обращении к БД.
            Попробуйте обновить страницу или обратится к администратору.23'
                )));
                $data = mysql_fetch_assoc($res);
                $idManager = $data['idManager'];
                $result = mysql_query("INSERT INTO fact (Date, CreateDate,CountCallWithOpening, CountCallCurrentMonth,
                                                    CountPeopleWIthOpening,  CountPeopleCurrentMonth,
                                                    CountPresentationWithOpening,  CountPresentationCurrentMonth,
                                                    CountLettersWithOpening,  CountLettersCurrentMonth,
                                                    CountClaimsToQuality,CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                                                    CountQuestionsRegionalIntervention, CountQuestionsBreaksInBroadcasting,
                                                    CountComplaintsOfCCS,OtherQuestions,Manager_idManager,CCS_idCCS)
                                                    VALUES ('$date','$CreateDate','$CountCallWithOpening', '$CountCallCurrentMonth',
                                                    '$CountPeopleWIthOpening','$CountPeopleCurrentMonth','$CountPresentationWithOpening',
                                                    '$CountPresentationCurrentMonth','$CountLettersWithOpening',
                                                    '$CountLettersCurrentMonth','$CountClaimsToQuality','$CountQuestionsTransmitterOff','$CountQuestionsLaunchDateTansmitters',
                                                    '$CountQuestionsEquipment','$CountQuestionsRegionalIntervention','$CountQuestionsBreaksInBroadcasting',
                                                    '$CountComplaintsOfCCS','$OtherQuestions','$idManager','$ckp_id')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.7'.$num.$date.mysql_error().$data['idManager'].$manager.$branch)));
                if($result){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'INSERT')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.8'
                    )));
                }
            }

            $qwer = mysql_query("SELECT max(idFacts) as idFacts From fact");
            $id = mysql_fetch_assoc($qwer);
            $last_id =$id['idFacts'];

            $uploadDir = '/var/www/html/uploads/';
            for ($i=0; $i<$count; $i++){
                $uploadFile = $CreateDate." ".($_FILES['userFile']['name'][$i]);
                $size = $_FILES['userFile']['size'][$i];
                $file =$_FILES['userFile']['name'][$i];
                if(!is_writable($uploadDir)){
                    die(json_encode(array('response' =>'Вы не можете загрузить в указанную папку; смените CHMOD на 777.')));
                }
                else{
                    if(is_uploaded_file($_FILES['userFile']['tmp_name'][$i])){
                        if ($size < 5242880){
                            if(!move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/'.$uploadFile)) {
                                die(json_encode(array('response' =>'Проверьте путь!')));
                            }else{
                                if($result){
                                    // move_uploaded_file($temp, “uploads/”.$name_file);

                                    $upload = mysql_query("INSERT INTO attachment (PathFile,Fact_idFacts) VALUES('$uploadFile','$last_id')")
                                    or die (json_encode(array('response' => "<b>Ошибка при обращении к БД. Невозможно загрузить файлы .</b> " . mysql_error())));
                                    move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/'.$uploadFile);
                                    if($upload){
                                        $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица вложений',N'INSERT')")
                                        or die (json_encode(array('response' => 'Ошибка при обращении к БД.Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.9'
                                        )));
                                    }
                                }else {
                                    $upload = mysql_query("INSERT INTO attachment (PathFile,Fact_idFacts) VALUES('$uploadFile','$id_fact')")
                                    or die (json_encode(array('response' => "<b>Ошибка при обращении к БД. Невозможно загрузить файлы.</b> " . mysql_error())));
                                    move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/'.$uploadFile);
                                    if($upload){
                                        $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица вложений',N'INSERT')")
                                        or die (json_encode(array('response' => 'Ошибка при обращении к БД.Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.20'
                                        )));
                                    }
                                }
                            }
                        }else { echo "<p align='center'><b>Ошибка! Размер файла более 5МБ!</b></p>"; }
                    }
                }
            }
            if ($result == 'true' || $result1 == 'true'  && $sql == 'true' ) {
                echo json_encode(array('add' => 'Ваши данные и файлы успешно добавлены', 'code' => 1));
            }else {  echo json_encode(array('response' => 'Ваши данные не добавлены!
                    Попробуйте обновить страницу и повторите еще раз или обратитесь к администратору2.'.mysql_error()));
            }
        }else{
            $res =  mysql_query ("SELECT b.Name, m.Login, m.idManager,b.idBranch,m.Name as manager
                                          FROM manager m
                                          INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                                          INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                                          WHERE b.Name = '".$branch."' and m.Login = '".$manager."'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                     Попробуйте обновить страницу или обратится к администратору.4'.mysql_error()
            )));
            $data = mysql_fetch_assoc($res);
            $idManager = $data['idManager'];
            $Manager_name = $data['manager'];

            $sql = mysql_query("UPDATE ccs SET City = '$city', Address ='$address', ContactPerson = '$contact',
                                                    `E-Mail`='$email',OpeningHours='$openingHours', Phone = '$phone'
                                                     Where idCCS='$ckp_id'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.11'
            )));
            if($sql){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.12'
                )));
            }
            $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts From fact
                                                     Where CCS_idCCS='$ckp_id' and Date like '".$date."%'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.13'
            )));
            $idfact = mysql_fetch_assoc($sql_date);
            $id_fact = $idfact['idFacts'];
            $date_fact = $idfact['Date'];

            if($date == $date_fact ){
                $result = mysql_query("UPDATE fact SET CountCallWithOpening='$CountCallWithOpening',
                    CountCallCurrentMonth ='$CountCallCurrentMonth',
                    CountPeopleWIthOpening ='$CountPeopleWIthOpening',  CountPeopleCurrentMonth='$CountPeopleCurrentMonth',
                    CountPresentationWithOpening='$CountPresentationWithOpening',  CountPresentationCurrentMonth='$CountPresentationCurrentMonth',
                    CountLettersWithOpening='$CountLettersWithOpening',  CountLettersCurrentMonth='$CountLettersCurrentMonth',
                    CountClaimsToQuality='$CountClaimsToQuality',CountQuestionsTransmitterOff='$CountQuestionsTransmitterOff',
                    CountQuestionsLaunchDateTansmitters='$CountQuestionsLaunchDateTansmitters', CountQuestionsEquipment='$CountQuestionsEquipment',
                    CountQuestionsRegionalIntervention='$CountQuestionsRegionalIntervention', CountQuestionsBreaksInBroadcasting='$CountQuestionsBreaksInBroadcasting',
                    CountComplaintsOfCCS='$CountComplaintsOfCCS',OtherQuestions='$OtherQuestions',Manager_idManager = '$idManager'
                    WHERE idFacts='$id_fact'")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.14')));
                if($result){
//                                $CountCallWithOpening_prev =($_POST['CountCallWithOpening']);
//                                $CountCallCurrentMonth_new = ($_POST['CountCallCurrentMonth']);
//                                $CountCallCurrentMonth_old = ($idfact['CountCallCurrentMonth']);
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.15'
                    )));
                }
            }else {
                $result = mysql_query("INSERT INTO fact (Date, CreateDate,CountCallWithOpening, CountCallCurrentMonth,
                                                    CountPeopleWIthOpening,  CountPeopleCurrentMonth,
                                                    CountPresentationWithOpening,  CountPresentationCurrentMonth,
                                                    CountLettersWithOpening,  CountLettersCurrentMonth,
                                                    CountClaimsToQuality,CountQuestionsTransmitterOff, CountQuestionsLaunchDateTansmitters, CountQuestionsEquipment,
                                                    CountQuestionsRegionalIntervention, CountQuestionsBreaksInBroadcasting,
                                                    CountComplaintsOfCCS,OtherQuestions,Manager_idManager,CCS_idCCS)
                                                    VALUES ('$date','$CreateDate','$CountCallWithOpening', '$CountCallCurrentMonth',
                                                    '$CountPeopleWIthOpening','$CountPeopleCurrentMonth','$CountPresentationWithOpening',
                                                    '$CountPresentationCurrentMonth','$CountLettersWithOpening',
                                                    '$CountLettersCurrentMonth','$CountClaimsToQuality','$CountQuestionsTransmitterOff','$CountQuestionsLaunchDateTansmitters',
                                                    '$CountQuestionsEquipment','$CountQuestionsRegionalIntervention','$CountQuestionsBreaksInBroadcasting',
                                                    '$CountComplaintsOfCCS','$OtherQuestions','$idManager','$ckp_id')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.16')));
                if($result){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'INSERT')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.17'
                    )));
                }
            }
            if ($result == 'true' && $sql == 'true' ) {

                echo json_encode(array('add' => $manager));
            }
            else {
                echo json_encode(array('response' => 'Ваши данные не добавлены!Ошибка в обращении к БД!
                        Попробуйте обновить страницу или обратится к администратору1111.'
                ));
            }
        }
    }else {
        echo json_encode(array('response' => 'Заполните все поля формы!'));
    }
}