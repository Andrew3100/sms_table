<?php

//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;



$condition = str_replace('()',"'",$_GET['hid']);

$headers = $DB->getInterfaceFields($_GET['hid_t_n']);

($h_db = $DB->getTableFieldsName($_GET['hid_t_n']));
unset($h_db[7]);
unset($h_db[8]);
unset($h_db[9]);
($h_db = array_values($h_db));
($h_db = implode($h_db,','));
$content = $DB->getRecordsForTableInterfaceArray($_GET['hid_t_n'],$condition,'',$h_db);

$DB->reportToExcel($content,$headers,'report');
