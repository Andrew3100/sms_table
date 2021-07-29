<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
require_once 'html/template.html';


//вычисляем GET
$get = array_keys($_GET)[0];

$block = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$get'",'*');

foreach ($block as $blok) {
    $blocks = $blok['header'];
    $for_link = $blok['get'];
}

//интерфейсное имя таблицы
$table_named = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$get'");
foreach ($table_named as $table_name1) {
    $table_name = $table_name1['linkname'];
}
$bread = [
    "index.php?main=1" => 'Главная',
    "index.php?data=1" => 'Работа с данными',
    "table_list.php?$for_link" => "$blocks",
    "#" => "$table_name"
];
$active = [
    '',
    '',
    '',
    'active'
];

//объекты
$table =     new html_table();
$bootstrap = new Bootstrap();

$bootstrap->GetHeader();

getUserInfoBadge($user->name);
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
$fields = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$get'");

foreach ($fields as $field) {
    //готовые заголовки для таблицы
    $headers[] = $field['descriptor_n'];
}

$fields = $DB->getTableFieldsName($get);

for ($i = 0; $i < count($fields); $i++) {
    if ($fields[$i] != 'status' AND $fields[$i] != 'author') {
        $fields_for_interface[] = $fields[$i];
    }
}

$fields_for_interface = implode($fields_for_interface,',');

if ($user->is_site_admin() OR $user->isGubernator()) {
    //для админа или администрации губернатора даём возможность редактировать все записи

}
$years = array(
    2020,
    2021,
    2022,
    2023,
    2024,
    2025
);
$form = new html_form();
if ($table_name == 'Очная форма обучения' OR $table_name == 'Заочная форма обучения' OR $table_name == 'Иностранные слушатели') {
    //селектор для годов
    $selector = $form->getSelectYear('year',$years);
}
/*else {
    $selector = 'Для данной таблицы не предусмотрено архивирование данных';
}*/
if (!isset($_GET['limit'])) {
    $limit = "Limit 100";
}
else {
    if ($_GET['limit'] == 'all') {
        $limit = '';
    }
    else {
        $limits = $_GET['limit'];
        $limit = "Limit $limits";
    }
}

echo $bootstrap->getTab([1,2],[1,'эжёпа']);


$content = $DB->getRecordsForTableInterfaceArray($get,"`status` = 1 $limit",'','*');

for ($i=0; $i <= count($content); $i++) {
    for ($g=0; $g <= count($content[$i]); $g++) {
        if ($content[$i][$g] == NULL) {
            unset($content[$i][$g]);
        }
    }
}
$content = array_values($content);

$m_up_left = [
    "<a href='print_excel.php?$get'>Сохранить в Excel всю таблицу<img src='https://zappysys.com/images/ssis-powerpack/ssis-export-excel-file-task.png' style='width: 25px; height: 25px; margin-left: 10px;'></a>",
    '<h6>Показать записей: 
<a href="/table.php?'.$get.'&limit=20">20</a>
<a href="/table.php?'.$get.'&limit=40">40</a>
<a href="/table.php?'.$get.'&limit=60">60</a>
<a href="/table.php?'.$get.'&limit=80">80</a>
<a href="/table.php?'.$get.'&limit=100">100</a>
<a href="/table.php?'.$get.'&limit=150">150</a>
<a href="/table.php?'.$get.'&limit=300">300</a>
<a href="/table.php?'.$get.'&limit=500">500</a>
<a href="/table.php?'.$get.'&limit=1000">1000</a>
<a href="/table.php?'.$get.'&limit=all">Все</a>
</h6>',
    $selector,
    "<form enctype='multipart/form-data' method='post' action='excel_upload.php?$get'>
        <label class='form-label' for='excel'>Загрузить данные из Excel <a href='print_excel.php?$get&template=1'>(скачать шаблон загрузки)</a></label>
        <input class='form-control' type='file' required name='excel' id='excel'>
        
        <button style='margin-top: 15px;' class='btn btn-sm btn-success' type='submit'>Загрузить</button>
        
        
    </form>",
    "<a class='btn btn-success' href='add.php?$get' style='border-radius: 100px'>Добавить одну запись +</a>"
];

$form_date = new html_form();
$form = "<p>Отчёт за период</p>";
$form .= $form_date->openForm('report_select.php','post');
$form .=$form_date->getFormByType('date',1,'Начало периода',150);
$form .=$form_date->getFormByType('date',2,'Конец периода',150).'<br>';
$form .=$form_date->closeForm('Отчёт','success');

$m_up_right = [
    "
    <p>$form</p>
    ",
];


for ($i = 0; $i < count($content); $i++) {
        unset($content[$i][count($content[$i]) - 1]);
//        unset($content[$i][count($content[$i]) - 1]);
}

$menu_html_up_left = $bootstrap->setListMenu($m_up_left);
$menu_html_up_right = ''/*$bootstrap->setListMenu($m_up_right)*/;
$html_table = $table->printTableWithAction($table_name,$headers,$content);
$menu_html = $bootstrap->setListMenu($menu_list);
$html = [$menu_html_up_left,$menu_html_up_right];
$html2 = [$html_table];
echo $bootstrap->setContainer([6,6],$html);
echo $bootstrap->setContainer([12],$html2);