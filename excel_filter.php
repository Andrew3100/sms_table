<?php
require_once 'classes/classes.php';
require_once 'libs/lib.php';
require_once 'db/db_config.php';
require_once 'Excel/Classes/PHPExcel.php';
$get = array_keys($_GET)[0];

$condition = $_POST['query'];
$q = (str_replace("(","'",$condition));
$q = explode(",,,,,",$q);

$print = $DB->getRecordsForTableInterfaceArray($get,$q[1],'',$q[0].',year');
$headers = $DB->getInterfaceFields($get);
$headers[] = 'Год';
$DB->reportToExcel($print,$headers,'Файл');