<?php
ini_set("default_charset","UTF-8");
ini_set('display_errors', 'Off');
session_start();
//include 'auth.php';
include 'connect.php';
//if($_SESSION['Role'] =='Администратор' ||$_SESSION['Role'] =='Пользователь' ) {
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Итоговый отчет о работе Центра консультационной поддержки</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css" />
        <link href="css/style_adm.css" rel="stylesheet" type="text/css"/>
        <link href="bootstrap-datepicker-master/css/datepicker.css" rel="stylesheet">
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.scrollTo-min.js"></script>
        <script src="bootstrap-datepicker-master/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="bootstrap-datepicker-master/js/locales/bootstrap-datepicker.ru.js" type="text/javascript"
                charset="UTF-8"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('select').each(function () {
                    var $this = $(this);
                    if ($this.val()) {
                        $this.trigger('change');
                    }
                });
                if ($('#hidden').val() == 'true') {
                    $('#button').trigger('click');
                }
                $("#monthToday").datepicker({
                    format: "yyyy-mm",
                    minViewMode: 1,
                    language: "ru",
                    //multidate: true,
                    // multidateSeparator: " ",
                    autoclose: true
                })
                $("#monthTodayTo").datepicker({
                    format: "yyyy-mm",
                    minViewMode: 1,
                    language: "ru",
                    //multidate: true,
                    // multidateSeparator: " ",
                    autoclose: true
                })

            });
        </script>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#button').click(function() {
                    jQuery.scrollTo("#results", 500);
                });

            });
        </script>

        <script type="text/javascript">
            $(function() {
                $(window).scroll(function() {
                    if($(this).scrollTop() != 0) {
                        $('#toTop').fadeIn();
                    } else {
                        $('#toTop').fadeOut();
                    }
                });
                $('#toTop').click(function() {
                    $('body,html').animate({scrollTop:0},800);
                });
            });
        </script>

        <script type="text/javascript">
            function AjaxFormRequest(result_id, formMain, url) {
                $('#hidden').val('true');
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    dataType: "html",
                    data: jQuery("#" + formMain).serialize(),
                    success: function (response) {
                        document.getElementById(result_id).innerHTML = response;
                        jQuery.scrollTo("#results", 500);
                    },
                    error: function (response) {
                        document.getElementById(result_id).innerHTML = "<p>Возникла ошибка при отправке формы. Попробуйте еще раз</p>";
                    }
                });
            }
        </script>
        <style type="text/css">
            form {
                font-size: 13px;
            }
        </style>

    </head>
    <body>
    <div style="width:100px;height:80px; float:right;margin-left: 40px; z-index: 1000;position: relative">
        <img src="img/logo.png" >
    </div>

        <div style="padding-top:20px; padding-bottom: 20px;" align="center">

            <div class = "heading" style = "width: 52%"> Итоговый отчет о работе Центра консультационной поддержки
                <a href="logoff.php?mode=logoff"  onclick="return window.confirm('Вы уверены что хотите покаинуть страницу?');">
                    <div style="float: left;padding-top: 3px;">Выход</div> <img src="img/logout.jpg" ></a>
            </div>
    </div>

    <form method="post" action="search.php" id="formMain" name="formMain" class="formreg" enctype="multipart/form-data"
          style="font-size:13px;">

        <table width="500px" border="0" cellspacing="0" cellpadding="0" id="bdr_w" align="center">
            <tbody>
            <tr valign="top">
                <td>отчетный период (c)</td>
                <td class="bt1" width="600px">
                    <input type="text" class=" fld" id="monthToday" value="" name="date">
                </td>
            </tr>
            <tr valign="top">
                <td>отчетный период (по)</td>
                <td class="bt1" width="600px">
                    <input type="text" class="form-control fld" id="monthTodayTo" value="" name="dateto">
                </td>
            </tr>

            <tr valign="top">
                <td>федеральный округ</td>
                <td class="bt1">
                    <select name="district[]" id="district" type="text" class="fld" multiple="multiple" size=5 style="height: 80px;">
                        <option value="">-- выберите округ --</option>
                        <?php
                        $result = mysql_query("SELECT * FROM district ORDER BY Name")
                        or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.</b> ");
                        while ($row = mysql_fetch_array($result)) {


                            echo "<option data-idbranch = data-id_district = '" . $row['idDistrict'] . "' value='" . $row['idDistrict'] . "' >" . $row['Name'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <td>наименование филиала</td>
                <td width="50%" class="bt1">
                    <select name="branch[]" id="branch" class="fld" multiple="multiple" size=5 style="height: 80px;">
                        <option value="">-- выберите филиал --</option>
                        <?php
                        $result = mysql_query("SELECT * FROM branch ORDER BY Name")
                        or die ("<b>Query failed:</b> " . mysql_error());
                        while ($row = mysql_fetch_array($result)) {
                            echo "<option   data-branch ='" . $row['idBranch'] . "'
                                        data-district_id = '" . $row['District_idDistrict'] . "'
                                        value='".$row['idBranch']."'>
                                        " . $row['Name'] . "</option>";
                        }
                        ?>

                    </select>
                </td>
            </tr>
            <tr valign="top">
                <td>название ЦКП</td>
                <td class="bt1">
                    <select name="ckp[]" id="ckp" class="fld" multiple="multiple" size=5 style="height: 80px;">
                        <option value="">-- выберите ЦКП --</option>
                        <?php
                        $result = mysql_query("SELECT * FROM ccs c
                                            ORDER BY Name")
                        or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.");
                        while ($row = mysql_fetch_array($result)) {
                            echo '<option data-dis = "" data-id_ckp ="'. $row['idCCS'] .'" data-id_branch="' . $row['Branch_idBranch'] . '"
                            value="' . $row['idCCS'] . '">ЦКП-' . $row['City'] . '</option>';
                        }
                        ?>
                        </select>
                </td>
            </tr>

            <tr valign="top">
                <td>контент-менеджер</td>
                <td class="bt1">
                    <select name="manager[]" id="manager" class="fld" multiple="multiple" size=5 style="height: 80px;">
                        <option value="">-- выберите контент-менеджера --</option>
                    <?php

                    $result = mysql_query("SELECT * FROM manager m
                                            ORDER BY Name")
                    or die ("<b>Ошибка в обращении к БД. Невозможно выполнить запрос.");
                    while ($row = mysql_fetch_array($result)) {

                        echo '<option data-manager ="' . $row['Branch_idBranch'] . '" value="' . $row['idManager'] . '">' . $row['Name'] . '</option>';
                    }
                    ?>

                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="5" align="center">
                    <input type="hidden" id="hidden" value="false">
                    <input id="button" type="button" name="send" data-submitted="false"
                           value="Показать отчет" onclick="AjaxFormRequest('results', 'formMain', 'search.php');"/>
                    <input id="add_data" type="button" name="add_data" data-submitted="false"
                           value="Добавить данные" onclick="location.href='add.php'"/>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div id="results" style="padding: 8px;"></div>
    <div ID = "toTop" ><img src="img/up.jpg"> </div>
    </body>

    <script>


        // var multipleValues = $( "#multiple" ).val() || [];
        $('select#district').change(function () {
            var $branches = $('#branch').children().hide(),
                districtCollection = $(this).val();
            $.each(districtCollection, function(key, item) {
                $branches.filter('[data-district_id='+ item +']').show();
            });
//            $('#branch').val("");
//           $('#ckp').val("");
//            $('#manager').val("");
        });
        $('select#district').change(function () {
            var $ckp = $('#ckp').children().show(),
                districtCollection = $(this).val();
            $.each(districtCollection, function(key, item) {
                $ckp.filter('[data-dis='+ item +']').show();
            });
        });
        $('select#district').change(function () {
            var $ckp = $('#manager').children().show(),
                districtCollection = $(this).val();
            $.each(districtCollection, function(key, item) {
                $ckp.filter('[data-dis='+ item +']').show();
            });
        });

        $('select#branch').change(function () {
            var $ckp = $('#ckp').children().hide(),
                branchCollection = $(this).val();
            $.each(branchCollection, function(key, item) {
                $ckp.filter('[data-id_branch='+ item +']').show();
            });

        });

        $('select#branch').change(function () {
            var $managers = $('#manager').children().hide(),
                managerCollection = $(this).val();
            $.each(managerCollection, function(key, item) {
                $managers.filter('[data-manager='+ item +']').show();
            });

        });
        $('select#ckp').change(function () {
            $('#district').val("");
            $('#branch').val("");
            $('#manager').val("");
        });

//        $('select#ckp').change(function(){
//            var branch = $(this).children(':selected').attr('data-id_branch'),
//                $managers = $('#branch').children().show();
//
//            $managers.each(function(){
//                var $manager = $(this),
//                    branch_manager_id = $manager.attr('data-branch') || branch;
//                if (branch_manager_id != branch) {
//                    $manager.hide().select();
//
//
//                }
//            });
//
//        });






    </script>

    </html>
<?
//}else{
//    header('Location: index.php');
//    echo '<p align="center">У вас нет прав для просмотра этой страницы!</p>';
//}

