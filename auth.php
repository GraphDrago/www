<?php
    session_start();
    if(empty($_SESSION['login']) || empty($_SESSION['password']) || empty($_SESSION['Name_Users'])){
        header('Location: index.html');
    }else{

    }
