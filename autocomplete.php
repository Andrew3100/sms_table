<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;

$fio = $_REQUEST['term1'];

$users = $DB->getRecordsByConditionFetchAssoc('users',"name like '{$fio}%'");

foreach ($users as $user) {
    $full = $user['fullname'];
    $search[] = array('label' =>$full, 'data'=>$full,
        'value'=>$full);
}

echo json_encode($search);
