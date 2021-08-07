<?php

require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$table = array_keys($_GET)[0];

$headers = $DB->getInterfaceFields($table);
$content = $DB->getRecordsForTableInterfaceArray($table);

$count = $_POST['hidden_count'];

if (isset($_POST['hidden'])) {

    for ($i = 0; $i < ($count); $i++) {
        if (isset($_POST['name'.$i])) {
            $f[] = $_POST['name'.$i];
        }
    }
    $f = implode($f,', ');

    $content = $DB->getRecordsForTableInterfaceArray($table,'','',$f);

}

for ($i = 0; $i < count($content); $i++) {
    for ($g = 0; $g < count($content[$i]); $g++) {
        // 0 - поле id
        // 7 - поле поле автора
        // 8 - поле статуса записи
            unset($content[$i][0]);
            unset($content[$i][7]);
            unset($content[$i][8]);
    }
    $content_all[] = array_values($content[$i]);
}

//удаляем контент, если создан параметр template - он создаётся в случае,
// если пользователь перешёл сюда по ссылке "(скачать шаблон загрузки)"
// для формирования шаблона импорта данных

if ($_GET['template'] == 1) {
    unset($content_all);
}
$DB->reportToExcel($content_all,$headers);