<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$user = new user();
$user->setUserData();
$log = new log();

$table = array_keys($_GET)[0];

$fields = $DB->getTableFieldsName($table);

for ($i = 0; $i < count($fields); $i++) {
    if ($fields[$i]!='id' AND $fields[$i]!='author' AND $fields[$i]!='status') {
        $fieldss[] = $fields[$i];
    }
}

$obj = new stdClass();
for ($i = 0; $i < count($fieldss); $i++) {
    if (date_parse($_POST['name'.$i])['year'] != false) {
        //преобразование даты
        $date = new \DateTime("{$_POST['name'.$i]}");
        $obj->{$fieldss[$i]} = $date->format('d.m.Y');
    }
    else {
        $obj->{$fieldss[$i]} = $_POST['name'.$i];
    }
}
//заменить на имя куков
$obj->author    = $user->login;
$obj->status    = 1;
$DB->insert_record($table,$obj);
$log->fixed($user->name,"Вставка записи в таблицу $table");
echo "<script>window.location.replace('table.php?$table')</script>";
