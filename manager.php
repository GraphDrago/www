<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';
include 'auth.php';
$manager =  $_SESSION['Name_Manager'];
$CreateDate = date('Y-m-d H-i-s');
if(isset($_POST)) {

    if ($_POST['district'] !=''  && $_POST['branch_add'] != '' ) {
       // && $_POST[ckp_add] != '' && $_POST[manager_add] != ''
        //добавление филиал

        $iddistrict = $_POST['district'];
        $branch = $_POST['branch_add'];
        $query = mysql_query("Select * From branch Where Name = '$branch'");
        $b = mysql_fetch_assoc($query);
        $i = $b['Name'];
        if($branch == $i ){
            echo "Такой филиал уже существует. Данные не будут добавлены";
        }else {
            $q = mysql_query("INSERT INTO branch (Name,District_idDistrict) VALUES ('$branch','$iddistrict')");
            if($q){
                $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$branch',N'Таблица филиала',N'INSERT')");
            }
            if ($q == "true") {
                echo "Данные успешно добавлены!";
            } else {
                echo "Неудалось добавить данные";
            }
        }
    }elseif($_POST['branch_add_ckp'] !=''  && $_POST['ckp_add']!=''  &&  $_POST['city_add'] !=''  &&
    $_POST['address_add'] !=''  && $_POST['contact_add'] !=''  && $_POST['email_add'] !=''
        && $_POST ['hour_add'] !=''  && $_POST['phone_add']!=''  && $_POST['district_add']!=''){
        //добавление ЦКП
        $ckp = $_POST['ckp_add'];
        $district = $_POST['district_add'];
        $branch = $_POST['branch_add_ckp'];
        $city =  $_POST['city_add'];
        $address= $_POST['address_add'];
        $contact = $_POST['contact_add'];
        $email = $_POST['email_add'];
        $hour = $_POST['hour_add'];
        $phone = $_POST['phone_add'];
        $query = mysql_query("Select * From ccs Where Name = '$ckp'");
        $b = mysql_fetch_assoc($query);
        $i = $b['Name'];
        if( $ckp == $i ){
            echo "ЦКП с таким названием уже существует. Данные не будут добавлены";
        }else {
            $q = mysql_query("INSERT INTO ccs (Name,City,Address,ContactPerson,`E-Mail`, Phone,OpeningHours, Branch_idBranch)
                              VALUES ('$ckp','$city','$address','$contact','$email','$phone','$hour','$branch')");
            if($q){
                $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$ckp',N'Таблица ЦКП',N'INSERT')");
            }
            if ($q == "true") {
                echo "Данные успешно добавлены!";
            } else {
                echo "Неудалось добавить данные";
            }
        }
    }elseif($_POST['managerAdd'] !=''  && $_POST['branchAdd'] !=''  &&
    $_POST['login_add'] !=''  && $_POST['email_add']!='' ){
        //добавление менеджера
        $manager_ = $_POST['managerAdd'];
        $branch = $_POST['branchAdd'];
        //echo $branch;
        $login =  $_POST['login_add'];
        $email = $_POST['email_add'];
        $query = mysql_query("Select * From manager Where Login = '".$login."'");
        $b = mysql_fetch_assoc($query);
        $i = $b['Login'];
        if($login == $i ){
            echo "Такой менеджер уже существует. Данные не будут добавлены";
        }else {
            $q = mysql_query("INSERT INTO manager (Name,Login, EMail, Branch_idBranch)
                              VALUES ('$manager_','$login','$email','$branch')");
            if($q){
                $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$manager_',N'Таблица контент-менеджера',N'INSERT')");
            }
            if ($q == "true") {
                echo "Данные успешно добавлены!";
            } else {
                echo "Неудалось добавить данные";
            }
        }
    }elseif($_POST['district_add_name'] !='' ){
        //добавление округа
        print_r($_POST);
        $district = $_POST['district_add_name'];

        $query = mysql_query("Select * From district Where Name = '$district'");
        $b = mysql_fetch_assoc($query);
        $i = $b['Name'];
        if($district == $i ){
            echo "Такой округ уже существует. Данные не будут добавлены";
        }else {
            $q = mysql_query("INSERT INTO district (Name)
                              VALUES ('$district')");
            if($q){
                $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$district',N'Таблица округа',N'INSERT')");
            }
            if ($q == "true") {
                echo "Данные успешно добавлены!";
            } else {
                echo "Не удалось добавить данныеhjkl";
            }
        }
    }elseif( $_POST['district_edit_id'] !=''  && $_POST['branch_edit_name']!=''  ){

        //филиал реадктирвоание
        //print_r($_POST);

        $branch = $_POST['branch_edit_name'];
        $district = $_POST['district_edit_id'];

        $idbranch = $_POST['idbranch'];
        $update = mysql_query("UPDATE branch b
            INNER JOIN district d ON b.District_idDistrict = d.idDistrict
            INNER JOIN manager m ON b.idBranch = m.Branch_idBranch
            INNER JOIN ccs c ON b.idBranch = c.Branch_idBranch
            SET b.Name = '$branch',
            b.District_idDistrict =$district
           Where b.idBranch = $idbranch");
        if($update){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$branch',N'Таблица филиала',N'UPDATE')");
        }
        if($update){
            echo "Данные успешно изменены!";
        }else{
            echo "Не удалось обновить данные!";
        }

    }elseif($_POST['manager_add'] !=''  && $_POST['branch_add'] !=''  && $_POST['login_add'] !='' && $_POST['email_add'] !=''){

        $manager_ = $_POST['manager_add'];
        $branch = $_POST['branch_add'];
        $login = $_POST['login_add'];
        $email = $_POST['email_add'];
        $idmanager = $_POST['idmanager'];
        $update = mysql_query("UPDATE manager m
                    INNER JOIN branch b ON m.Branch_idBranch = b.idBranch
                    INNER JOIN ccs c ON c.Branch_idBranch = b.idBranch
                    SET m.Name = '$manager_',
                    m.Branch_idBranch ='$branch',
                    m.Login ='$login',
                    m.EMail =  '$email' Where m.idManager =  $idmanager");
        if($update){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$manager_',N'Таблица контент-менеджера',N'UPDATE')");
        }
        if($update){
            echo "Данные успешно изменены!";
        }else{
            echo "Не удалось обновить данные!";
        }
        //менеджер редактирвоание
    }elseif($_POST['branch_edit'] !=''  && $_POST['ckp_add'] !=''  &&
    $_POST['city_add'] !=''  && $_POST['address_add'] !=''  && $_POST['contact_add'] !=''  &&
        $_POST['email_add'] !=''  && $_POST['hour_add'] !=''  && $_POST['phone_add']!='' ){
        //ckkp реадктирвоание
        //print_r($_POST);
        $ckp = $_POST['ckp_add'];
        $branch = $_POST['branch_edit'];
        $city =  $_POST['city_add'];
        $address= $_POST['address_add'];
        $contact = $_POST['contact_add'];
        $email = $_POST['email_add'];
        $hour = $_POST['hour_add'];
        $phone = $_POST['phone_add'];

        $idckp = $_POST['idckp'];
        $update = mysql_query("UPDATE ccs c
                    INNER JOIN branch b ON c.Branch_idBranch = b.idBranch
                    INNER JOIN district d ON b.District_idDistrict = d.idDistrict
                    INNER JOIN manager m ON m.Branch_idBranch = b.idBranch
                    SET
                    c.Branch_idBranch ='$branch',
                    c.Name = '$ckp',
                    c.City ='$city',
                    c.Address ='$address',
                    c.ContactPerson ='$contact',
                    c.`E-Mail` ='$email',
                    c.Phone ='$phone',
                    c.OpeningHours ='$hour'
                    Where c.idCCS =  $idckp");
        if($update){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$ckp',N'Таблица ЦКП',N'UPDATE')");
        }
        if($update){
            echo "Данные успешно изменены!";
        }else{
            echo "Не удалось обновить данные!";
        }
    }elseif($_POST['district_add_update'] !=''  ){
    //округ реадктирвоание
        //print_r($_POST);
    $district = $_POST['district_add_update'];
     $iddistrict = $_POST['iddistrict'];
       // echo $iddistrict;
    $update = mysql_query("UPDATE district
                    SET Name = '$district'
                    Where idDistrict =  $iddistrict");
        if($update){
            $sql_delete = mysql_query("INSERT INTO logging (Date,Manager,CCS,Table_Change,Actions)
                                                  VALUES( '$CreateDate', '$manager','$district',N'Таблица округа',N'UPDATE')");
        }
    if($update){
        echo "Данные успешно изменены!";
    }else{
        echo "Не удалось обновить данныеhjk!";
    }
}else{

        echo "Вы ввели не все данные. Проверьте и попробуйте сохранить еще раз!";
    }

//$sql = mysql_query("UPDATE ccs SET City = '$city', Address ='$address', ContactPerson = '$contact',
//                                                    `E-Mail`='$email',OpeningHours='$openingHours', Phone = '$phone'
//                                                     Where idCCS='$ckp_id'");
//$result = mysql_query("INSERT INTO manager (Name, Login, EMail,Branch_idBranch)
//                                                    VALUES ('$date','$CreateDate','$CountCallWithOpening', '$CountCallCurrentMonth',
//                                                    '$CountPeopleWIthOpening','$CountPeopleCurrentMonth','$CountPresentationWithOpening',
//                                                    '$CountPresentationCurrentMonth','$CountLettersWithOpening',
//                                                    '$CountLettersCurrentMonth','$CountClaimsToQuality','$CountQuestionsTransmitterOff','$CountQuestionsLaunchDateTansmitters',
//                                                    '$CountQuestionsEquipment','$CountQuestionsRegionalIntervention','$CountQuestionsBreaksInBroadcasting',
//                                                    '$CountComplaintsOfCCS','$OtherQuestions','$idManager','$ckp_id')")

}