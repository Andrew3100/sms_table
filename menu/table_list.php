<?php
//классы
require_once '../classes/classes.php';
//Библиотека
require_once '../libs/lib.php';
require_once '../db/db_config.php';
global $DB;
require_once '../html/template.html';


$bootstrap = new Bootstrap();

$list = $DB->getRecordsByConditionFetchAssoc('administration_table_link','','*');

foreach ($list as $listed) {
    $gets[] = $listed['get'];
}
//определяем какой GET создан в данный момент
for ($i = 0; $i < count($gets); $i++) {
    if ($gets[$i]==array_keys($_GET)[0]) {
       $fixed_get = $gets[$i];
    }
}

$table_list = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`get` = '$fixed_get'");

foreach ($table_list as $tables) {
    $link_get = $tables['link_get'];
    $table_name = $tables['linkname'];
    $list_t[] = "<a href='table.php?$link_get'>$table_name</a>";
}


echo $bootstrap->setListMenu($list_t);