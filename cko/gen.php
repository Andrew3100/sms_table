<?php
require_once '../Excel/Classes/PHPExcel.php';
$group_number = $_POST['group'];


$excel = PHPExcel_IOFactory::load("excel/$group_number.xlsx");

$excel->setActiveSheetIndex(0);
$g=2;

while ($excel->getActiveSheet()->getCell('A'.$g)->getValue()!='') {
    $student = $excel->getActiveSheet()->getCell('A'.$g)->getValue();
    $mark = $excel->getActiveSheet()->getCell('B'.$g)->getValue();
    $g++;
}

