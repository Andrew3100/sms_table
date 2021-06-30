<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
require_once 'html/template.html';

$get = array_keys($_GET)[0];

$block = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`get` = '$get'",'header');

foreach ($block as $blok) {
    $blocks = $blok['header'];
}

$bread = [
    "index.php?main=1" => 'Главная',
    "index.php?data=1" => 'Работа с данными',
    "" => "$blocks",
];
$active = [
    '',
    '',
    'active'
];

$bootstrap = new Bootstrap();

//шапка
$bootstrap->GetHeader();
echo '<br><br>';

$bootstrap->getBreadcrumb($bread,$active);

echo '<br><br>';

//список пунктов меню
$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
        '<a href="index.php?reports=1">Отчёты</a>',
        '<a href="index.php?import=1">Импорт данных</a>'
    ];
//преобразуем список пунктов меню в красивый лист
$menu_html = $bootstrap->setListMenu($menu_list);
$table_list = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`get` = '$get'",'*');

foreach ($table_list as $table_lists) {
    $table_list_links[] = "<a href='table.php?{$table_lists["link_get"]}'>{$table_lists['linkname']}</a>";
}
//список таблиц в виде html-компонента
$tables_list_html = $bootstrap->setListMenu($table_list_links);
$html = [$menu_html,$tables_list_html];

//контейнер (описание метода есть в классе)
echo $bootstrap->setContainer([3,9],$html,'fluid');