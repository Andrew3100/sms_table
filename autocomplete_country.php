<?php
//Библиотека
require_once 'libs/html_elements_lib.php';
//Библиотека
require_once 'libs/lib.php';
//Классы
require_once 'classes/classes.php';
//база
require_once 'db/db_config.php';

$fio = $_REQUEST['term1'];

$res = $DB->getRecordsByConditionFetchAssoc('ref_country',"`fullname` LIKE '{$fio}%'",'*',1);

$arr["arr"] = array();

foreach ($res as $row) {
    $arr[] = array('label' => $row['fullname'],'data'=> $row['fullname'],'value'=>$row['fullname']);
}

echo json_encode($arr);