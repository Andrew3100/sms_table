<?php



//Модуль 2. Создание растровых изображений в Adobe Photoshop. Разработка структуры, публикация и продвижение Web – сайта
//Модуль 1. Введение в Web-дизайн. Язык разметки гипертекста HTML. Каскадные таблицы стилей
//Ведомость проведения итоговых испытаний в группе №5 (2020-2021 учебный год)
//дополнительной образовательной (общеразвивающей) программы

require_once '../Excel/Classes/PHPExcel.php';
$group_number = $_POST['group'];
$module       = $_POST['mod'];

if ($module==1) {
    $module1 = 'Модуль 1. Введение в Web-дизайн. Язык разметки гипертекста HTML. Каскадные таблицы стилей';
    $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
}
else {
    if ($module == 'Итоговая ведомость') {
        $module1 = '';
        $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
    }
    else {
        $module1 = 'Модуль 2. Создание растровых изображений в Adobe Photoshop. Разработка структуры, публикация и продвижение Web – сайта';
        $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
    }
}

$excel = PHPExcel_IOFactory::load("excel/$group_number.xlsx");

$excel->setActiveSheetIndex(0);
$g=2;

while ($excel->getActiveSheet()->getCell('A'.$g)->getValue()!='') {
    $student[] = $excel->getActiveSheet()->getCell('A'.$g)->getValue();
    $mark[] = $excel->getActiveSheet()->getCell('B'.$g)->getValue();
    $g++;
}

include 'word/header_for_all.php';
include 'word/header.php';
include 'word/table.php';