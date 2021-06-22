<?php
//Библиотека
require_once 'libs/html_elements_lib.php';
//Библиотека
require_once 'libs/lib.php';
//Классы
require_once 'classes/classes.php';

global $DB;

$blocks = $DB->getRecordsByConditionFetchAssoc('administration_table_link');

foreach ($blocks as $block) {
    $get = $block["get"];
    $head = $block["header"];
    $link = "<a href='menu/table_list.php?$get'>$head</a>";
    $blocked[] = $link;
}

$bootstrap = new Bootstrap();

echo $bootstrap->setListMenu(array_values(array_unique($blocked)));