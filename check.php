<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
include 'connect.php';


function login_ldap (&$_out, $user, $pass){

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

    $_out_res = false;

    foreach($ldap_server_list as $ldap_server){

        $ldap_user = $ldap_server['prefics'].'\\'.$user;
        $filter = "(&(objectClass=user)(samaccountname=".$user."))";

        $connect = ldap_connect( $ldap_server['ldap_host'], "389");
        ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
        $bind = @ldap_bind($connect, $ldap_user, $pass);

        if ($bind <> false){
            $read = ldap_search($connect, $ldap_server['base_dn'], $filter);
            $info = ldap_get_entries($connect, $read);
            // $col_name = array('distinguishedname', 'userprincipalname', 'name', 'mail');
            $col_name = array('distinguishedname', 'userprincipalname', 'name', 'mail', 'telephonenumber', 'title', 'department', 'mobile', 'company');
            if ($info["count"] == 1){
                $_out = array();
                for($colonne = 0; $colonne<$info[0]["count"]; $colonne++){
                    $_tmp = $info[0][$colonne];
                    if (in_array($_tmp,$col_name)){
                        $_out[$_tmp] = $info[0][$_tmp][0];
                    }
                }
            }
            if ($_out <> ''){
                $_out_res = true;
                break;
            }
        }
    }
    ldap_close($connect);
    return $_out_res;
}

if ($_POST){
    $someArray = array();
    $username = $_POST['login'];
    $_SESSION['login']=$_POST['login'];
    $user_name = mysql_query("SELECT Name  FROM users where Login='" . $username . "'");
    $name = mysql_fetch_assoc($user_name);
    $_SESSION['Name_Users'] = $name['Name'];

    $password = $_POST['password'];
    $_SESSION['password']=$_POST['password'];
    if( $username == '' || $password == ''){
        echo json_encode(array('found' =>'Вы ввели не все данные!Попробуйте еще раз.'));

    }else {
        $result = login_ldap($someArray, $username, $password);
        $query = mysql_query("SELECT Login,Name FROM manager WHERE Login='" . mysql_real_escape_string($_POST['login']) . "' LIMIT 1")
        or die ("<b>Ошибка в обращении в БД.</b> ");
        $data = mysql_fetch_assoc($query);
        $_SESSION['Name_Manager'] = $data['Name'];
        $users = mysql_query("SELECT r.Name_Role as Name_Role FROM users u
INNER JOIN role r ON u.Role_idRole = r.idRole where Login='" . $username . "'");
        $role = mysql_fetch_assoc($users);

        $namerole = $role['Name_Role'];
        $_SESSION['Role'] = $role['Name_Role'];
        //echo json_encode(array('found' => $namerole));
        if (mysql_num_rows($query) == 0 && $namerole == 'Content Manager') {
            echo json_encode(array('found' => 'За вами не закреплено ни одного филиала. Обратитесь к администратору ситемы.'));
        } else {
            if ($result == 'true') {
                if ($namerole == 'system Administrator') {
                    echo json_encode(array('redirect' => 'index.php'));

                } elseif ($namerole == 'data Manager') {
                    echo json_encode(array('redirect' => 'report.php'));

                } elseif ($namerole == 'Content Manager') {
                    echo json_encode(array('redirect' => 'data.php'));

                } else {
                    echo json_encode(array('redirect' => 'authen.php'));
                }
            } else {
                echo json_encode(array('found' => 'Вы ввели не правильный логин или пароль. Повторите еще раз'));
            }

        }
    }
    }
