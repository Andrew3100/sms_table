<?php

//
////классы
require_once 'classes/classes.php';
////Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
//require_once 'html/template.html';


require_once 'Excel/Classes/PHPExcel.php';

$db_host = 'bsu-do-sql-pegas.bsu.edu.ru';
$db_user = "ADMIN"; // Логин БД
$db_password = "b1g#psKT"; // Пароль БД
$db_base = 'mou'; // Имя БД


// Подключение к базе данных
$mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);

$mysqli->set_charset("utf8");
if ($mysqli->connect_error) {
    echo "Ошибка подключения к базе данных";
}

//файл
$excel = PHPExcel_IOFactory::load('mou_july.xlsx');

//Указываем в качестве активной ячейки первую
$excel->setActiveSheetIndex(0);

$g = 1;
$n = 27;
while ($value = $excel->getActiveSheet()->getCell('A'.$g)->getValue()!="") {

    $crit = $excel->getActiveSheet()->getCell('A'.$g)->getValue();
    $formula = $excel->getActiveSheet()->getCell('B'.$g)->getValue();
    $ed = $excel->getActiveSheet()->getCell('C'.$g)->getValue();
    $g++;
    $insert = new stdClass();
    $insert->yearid = 15;
    $insert->gradelevel = 1;
    $insert->number = $n;
    $insert->name = $crit;
    $insert->formula = $formula;
    $insert->edizm = $ed;
    $insert->weight = 0;
    $insert->ordering = 1;
    $insert->indicator = 'null';
    $insert->sortnumber = $n;
    $n++;
    $DB->insert_record('mdl_monit_rating_criteria',$insert,1);
}
