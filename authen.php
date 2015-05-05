
<?php
session_start();
include 'connect.php';
$ldap_server_list = array(
    '0'=>array(
        "ldap_host" => "Msk-krv13-dc01.rtrs.local",
        "base_dn"   => "DC=rtrs,DC=local",
        "prefics"   => "RTRS"
    ),
    '1'=>array(
        "ldap_host" => "Msk-krv13-dc02.rtrs.local",
        "base_dn"   => "DC=rtrs,DC=local",
        "prefics"   => "RTRS"
    ),
    //  на случай аварии аналог Msk-krv13-dc01.rtrs.local
    '2'=>array(
        "ldap_host" => "172.31.42.14",
        "base_dn"   => "DC=rtrs,DC=local",
        "prefics"   => "RTRS"
    ),
    //  на случай аварии аналог Msk-krv13-dc02.rtrs.local
    '3'=>array(
        "ldap_host" => "172.31.42.15",
        "base_dn"   => "DC=rtrs,DC=local",
        "prefics"   => "RTRS"
    ),
    // устарели удалить
    '4'=>array(
        "ldap_host" => "DC01.rtrn.ru",
        "base_dn"   => "DC=rtrn,DC=ru",
        "prefics"   => "RTRN"
    ),
    '5'=>array(
        "ldap_host" => "192.168.112.2",
        "base_dn"   => "DC=FNS,DC=RTRN,DC=LOCAL",
        "prefics"   => "FNS"
    )
);
$users = $_SESSION['login'];
//$user = 'MAdjieva';
$passw = $_SESSION['password'];
foreach($ldap_server_list as $ldap_server) {
    //$ldap_user = $ldap_server['RTRS'].'\\'.$user;
    $ldap_user = 'RTRS' . '\\' . $users;
    //eapetrova
    $filter = "(&(objectClass=user)(samaccountname=".$users."))";
    //$connect = ldap_connect($ldap_server['ldap_host']);
    $attributes = array();
    $attributes[] = 'givenname';
    $attributes[] = 'mail';
    $attributes[] = 'samaccountname';
    $attributes[] = 'sn';
    $attributes[] = 'telephoneNumber';
    $attributes[] = 'description';
    $attributes[] = 'title';
    $attributes[] = 'department';
    $attributes[] = 'physicalDeliveryOfficeName';
    $attributes[] = 'displayName';
    $attributes[] = 'userAccountControl';
    $attributes[] = 'ou';
    $connect = ldap_connect( $ldap_server['ldap_host'], "389")
    or die("Couldn't connect to AD!");
    ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
    $bind = @ldap_bind($connect, $ldap_user, $passw);


    $read = ldap_search($connect, $ldap_server['base_dn'], $filter, $attributes);
    global $info;
    $info = ldap_get_entries($connect, $read);
   // echo '<pre>'; print_r($info); echo '</pre>';
    // $col_name = array('distinguishedname', 'userprincipalname', 'name', 'mail');
    $col_name = array('distinguishedname', 'userprincipalname', 'name', 'mail', 'telephonenumber', 'title', 'department', 'mobile', 'company');
    if ($bind == 'true') {
        $login = $info[0]["samaccountname"][0];
        $name = $info[0]["displayname"][0];
        $mail = $info[0]["mail"][0];
        $res = mysql_query("Select Login From users Where Login = '".$users."'")
        or die ("<b>Ошибка в обращении к БД.</b> ");
        $role = mysql_fetch_assoc($res);
        if(strtolower($role['Login']) == strtolower($users)){
            include 'index.html';
            echo "<p align='center'>Вы уже есть в списке пользователей. Дождитесь пока администратор выдаст вам права.</p>";
        }else {
            $query = mysql_query("INSERT INTO users (Name,Login,EMail,Role_idRole)
                                         VALUES ('$name','$login','$mail','4' )")
            or die ("<b>Ошибка в обращении к БД.</b> ");
            include 'index.html';
            echo "<p align='center'>У вас нет прав доступа. Обратитесь к администратору для получения прав.</p>";
        }
        break;
    }

}
ldap_close($connect);

