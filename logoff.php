<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Оксана
 * Date: 31.03.2015
 * Time: 0:18
 */
if ($_GET['mode']=="logoff"){
    session_unset();
    session_destroy();
    header("Location: index.html");
}
