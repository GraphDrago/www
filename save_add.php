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
    $manager =  $_POST['manager'];//id
    $date2 = $_POST['date2'];

    $datetime = strtotime($date2);
   $date= $date2.'-'.date('t', $datetime);
    $branch= $_POST['branch'];//имя
    $district= $_POST['district'];//имя

    //$ckp = ($_POST['ckp']);
    $ckp_id = ($_POST['ckp']);
    //$ckp_name = ($_POST['ckp_name']);
    $_SESSION['ckp'] = $ckp_id ;
    $Manager_name =  $_SESSION['Name_Manager'];

    $city = $_POST['city'];

    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $phone= $_POST['phone'];
    $openingHours = $_POST['openingHours'];

    $sql_manager =  mysql_query ("SELECT b.Name as br, m.Name as manager, m.idManager, b.idBranch,c.Name as ckp_name
											FROM manager m
											INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
											INNER JOIN ccs c ON c.Branch_idBranch = b.idBranch
											WHERE b.Name = '$branch'")
    or die ("<b>Ошибка в обращении к БД. Невозможно получить название филиала.<br>
Попробуйте обновить страницу или обратится к администратору.5</b> ".mysql_error());
    $data = mysql_fetch_assoc($sql_manager);
    $m = $data['idManager'];
    $ckp_name = $data['ckp_name'];
    if($m != $manager ){
        echo json_encode(array('add' => '8Выбранный менеджер не соответсвует выбранному ЦКП. Выберите менедежра за которым закреплен данный ЦКП.'));
    }else{

    $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts,
CountCallCurrentMonth, CountPeopleCurrentMonth,  CountPresentationCurrentMonth,
                              CountLettersCurrentMonth From fact
                                                     Where  CCS_idCCS = $ckp_id and Date  like '".$date."%'")
    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.6'.mysql_error()
    )));
    $idfact = mysql_fetch_assoc($sql_date);
    $date_fact = $idfact['Date'];

    if($date ==  $date_fact){
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
        $CountLettersWithOpening = $CountLettersWithOpening_prev+($CountLettersCurrentMonth_new - $CountLettersCurrentMonth_old);

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

    //echo (json_encode(array('response' => "hjkl;")));
//    echo (json_encode(array('response' =>  $date)));
//    echo (json_encode(array('response' => $ckp_id)));
//    echo (json_encode(array('response' => $CountCallWithOpening)));
//    echo (json_encode(array('response' => $CountCallCurrentMonth)));
//    echo (json_encode(array('response' => $CountPeopleWIthOpening)));
//    echo (json_encode(array('response' => $CountPeopleCurrentMonth)));
//    echo (json_encode(array('response' => $CountPresentationWithOpening)));
//    echo (json_encode(array('response' => $CountClaimsToQuality)));
//print_r($_POST);

    if($CreateDate !='' && $date != '' && $ckp_id != ''  && $manager != '' )
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

            $sql = mysql_query("UPDATE ccs SET City = '$city', Address ='$address',
                                                       ContactPerson = '$contact', `E-Mail`='$email',
                                                        OpeningHours='$openingHours', Phone = '$phone'
                                                        Where idCCS='$ckp_id'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.'.mysql_error())));
            if($sql){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'.mysql_error()
                )));
            }
            $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts From fact
                                                     Where CCS_idCCS='$ckp_id' and Date like '".$date."%'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'.mysql_error()
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
                    Попробуйте обновить страницу или обратится к администратору.')));
                if($result1){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
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
                                                    '$CountComplaintsOfCCS','$OtherQuestions','$manager','$ckp_id')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.')));
                if($result){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'INSERT')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                    )));
                }
            }


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

                                    $last_id = mysql_insert_id();
                                    $upload = mysql_query("INSERT INTO attachment (PathFile,Fact_idFacts) VALUES('$uploadFile','$last_id')")
                                    or die (json_encode(array('response' => "<b>Ошибка при обращении к БД. Невозможно загрузить файлы.</b> " . mysql_error())));
                                    move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/'.$uploadFile);
                                    if($upload){
                                        $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица вложений',N'INSERT')")
                                        or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
                                        )));
                                    }
                                }else {
                                    $upload = mysql_query("INSERT INTO attachment (PathFile,Fact_idFacts) VALUES('$uploadFile','$id_fact')")
                                    or die (json_encode(array('response' => "<b>Ошибка при обращении к БД. Невозможно загрузить файлы.</b> " . mysql_error())));
                                    move_uploaded_file($_FILES['userFile']['tmp_name'][$i], '/var/www/html/uploads/'.$uploadFile);
                                    if($upload){
                                        $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица вложений',N'INSERT')")
                                        or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'
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
                    Попробуйте обновить страницу и повторите еще раз или обратитесь к администратору.'.mysql_error()));
            }
        }else{

            $sql = mysql_query("UPDATE ccs SET City = '$city', Address ='$address', ContactPerson = '$contact',
                                                    `E-Mail`='$email',OpeningHours='$openingHours', Phone = '$phone'
                                                     Where idCCS='$ckp_id'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.8'.mysql_error()
            )));
            if($sql){
                $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица ЦКП',N'UPDATE')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.10'.mysql_error()
                )));
            }
            $sql_date = mysql_query("Select CONVERT( Date,char(10)) as Date, idFacts From fact
                                                     Where CCS_idCCS='$ckp_id' and Date like '".$date."%'")
            or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.12'.mysql_error()
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
                    CountComplaintsOfCCS='$CountComplaintsOfCCS',OtherQuestions='$OtherQuestions'
                    WHERE idFacts='$id_fact'")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору.11'.mysql_error())));
                if($result){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'UPDATE')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.13'.mysql_error()
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
                                                    '$CountComplaintsOfCCS','$OtherQuestions','$manager','$ckp_id')")
                or die (json_encode(array('response' => 'Ошибка при обращении к БД. Невозможно выполнить запрос.
                    Попробуйте обновить страницу или обратится к администратору. 14'.mysql_error())));
                if($result){
                    $sql_update = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$Manager_name','$ckp_name',N'Таблица фактов',N'INSERT')")
                    or die (json_encode(array('response' => 'Ошибка при обращении к БД.
                     Невозможно выполнить запрос.Попробуйте обновить страницу или обратится к администратору.'.mysql_error()
                    )));
                }
            }
            if ($result == 'true' && $sql == 'true' ) {

                echo json_encode(array('add' => 'Ваши данные добавлены.'));
            }
            else {
                echo json_encode(array('response' => 'Ваши данные не добавлены!Ошибка в обращении к БД!
                        Попробуйте обновить страницу или обратится к администратору.'
                ));
            }
        }
    }else {
        echo json_encode(array('response' => 'Заполните все поля формы!'));
    }

    }
}