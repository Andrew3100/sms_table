<?php


require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';

include 'db/db_config.php';





$bootstrap = new Bootstrap();

$bootstrap->GetHeader();
echo '<br><br><br>';
$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
        '<a href="index.php?reports=1">Отчёты</a>',
        '<a href="index.php?import=1">Импорт данных</a>'
    ];
$menu = $bootstrap->setListMenu($menu_list);
$c = getContentByMenu();
//for_include_content - ключ указывает на то, что в данных находится контент, которые надо выполнять по include
$html = [$menu,'for_include_content'=>$c];


$bootstrap->setContainer([3,9],$html,'fluid');



