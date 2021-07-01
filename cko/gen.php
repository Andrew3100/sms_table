<?php



//Модуль 2. Создание растровых изображений в Adobe Photoshop. Разработка структуры, публикация и продвижение Web – сайта
//Модуль 1. Введение в Web-дизайн. Язык разметки гипертекста HTML. Каскадные таблицы стилей
//Ведомость проведения итоговых испытаний в группе №5 (2020-2021 учебный год)
//дополнительной образовательной (общеразвивающей) программы

require_once '../Excel/Classes/PHPExcel.php';
$group_number = $_POST['group'];
$module       = $_POST['mod'];

if ($group_number==4) {
    $date = '21.06.2021';
}
if ($group_number==5) {
    $date = '21.06.2021';
}
if ($group_number==6) {
    $date = '16.06.2021';
}
if ($group_number==7) {
    $date = '9.06.2021';
}

$no_show = 0;


if ($module==1) {
    $file_name = 'Модуль 1';
    $module1 = 'Модуль 1. Введение в Web-дизайн. Язык разметки гипертекста HTML. Каскадные таблицы стилей';
    $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
}
else {
    if ($module == 'Итоговая ведомость') {
        $file_name = 'Итоговая ведомость';
        $module1 = '';
        $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
    }
    else {
        $file_name = 'Модуль 2';
        $module1 = 'Модуль 2. Создание растровых изображений в Adobe Photoshop. Разработка структуры, публикация и продвижение Web – сайта';
        $header_mod = 'Ведомость проведения итоговых испытаний в группе №'.$group_number.' (2020-2021 учебный год) дополнительной образовательной (общеразвивающей) программы';
    }
}

$excel = PHPExcel_IOFactory::load("excel/$group_number.xlsx");

$excel->setActiveSheetIndex(0);
$g=2;
$show = 0;
while ($excel->getActiveSheet()->getCell('A'.$g)->getValue()!='') {
    $mark_full = $excel->getActiveSheet()->getCell('B'.$g)->getValue();
    if ($mark_full==3) {
        $mark_full .= ' (удовл.)';
        $show++;
    }
    if ($mark_full==4) {
        $mark_full .= ' (хор.)';
        $show++;
    }
    if ($mark_full==5) {
        $mark_full .= ' (отл.)';
        $show++;
    }

    $student[] = $excel->getActiveSheet()->getCell('A'.$g)->getValue();
    $mark[] = $mark_full;
    $g++;
}

include 'word/header_for_all.php';
include 'word/header.php';
include 'word/table.php';

