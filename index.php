<?php

require_once 'classes/classes.php';
include 'db/db_config.php';
require_once 'libs/lib.php';
require_once 'Excel/Classes/PHPExcel.php';
require_once 'libs/html_elements_lib.php';
global $DB;

$user = new user();
$user->setUserData();
$gets = $DB->getRecordsByConditionFetchAssoc('administration_table_link','','*');

foreach ($gets as $get) {
    $get_table[] = $get['link_get'];
    $get_block[] = $get['get'];
}

$bootstrap = new Bootstrap();

$bread = ["index.php?main=1" => 'Главная'];
if (array_keys($_GET)[0] == 'roots') {
    $bread["index.php?roots=1"] = 'Права доступа';
}

$active = [
    '',
    'active'
];


$bootstrap->GetHeader();

getUserInfoBadge($user->name);
echo '<div style="margin-bottom: 6px;"></div>';
$bootstrap->getBreadcrumb($bread,$active);
echo '<br><br>';

$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
    ];

if ($user->is_site_admin()) {
    $menu_list[] = '<a href="index.php?logs=1">Логи</a>';
    $menu_list[] = '<a href="index.php?roots=1">Права доступа</a>';
    $menu_list[] = '<a href="index.php?create=1">Создать раздел</a>';
}

$menu_list[] = '<a href="exit.php">Выйти из системы</a>';

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

