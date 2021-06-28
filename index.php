<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$gets = $DB->getRecordsByConditionFetchAssoc('administration_table_link','','*');

foreach ($gets as $get) {
    $get_table[] = $get['link_get'];
    $get_block[] = $get['get'];
}

$bootstrap = new Bootstrap();

echo $bootstrap->GetHeader();
echo '<br><br><br>';

$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
        '<a href="index.php?reports=1">Отчёты по выборочным данным</a>',
        '<a href="index.php?import=1">Логи</a>',
        '<a href="index.php?roots=1">Права доступа</a>',
        '<a href="index.php?create=1">Создать раздел</a>'
    ];

$menu = $bootstrap->setListMenu($menu_list);
$c = getContentByMenu();
$html = [$menu,'for_include_content'=>$c];

echo "
<div class='container-fluid'>
    <div class='row'>
        <div class='col-3'>
           $menu 
        </div>
        <div class='col-9'>";
include ($c);
echo "    
        </div>
        
    </div>
</div>
";

exit();
//for_include_content - ключ указывает на то, что в данных находится контент, которые надо выполнять по include
echo $bootstrap->setContainer([3,9],$html,'fluid');
