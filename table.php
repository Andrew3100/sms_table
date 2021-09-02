<?php

//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
require_once 'html/template.html';

//получаем массив, в котором храним уловие отбора данных. Ключ = поле БД, значение - соотв.
($filters = parseGetData());

//На основе данного массива генерируем условие отбора данных для БД
/*pre*/($keys   = array_keys($filters));
/*pre*/($values = array_values($filters));
if ((count($keys)) > 0) {
    $condition = '`status` = 1 AND ';
    for ($i = 0; $i < count($filters); $i++) {
        if ($i != count($filters)-1) {
            $and = 'AND ';
        }
        else {
            $and = '';
        }
        $condition .= "`{$keys[$i]}` = '$values[$i]' $and";

    }
}
else {
    $condition = '`status` = 1';
}


/*Для скрытой формы, так как проблемы с экранированием символов*/
$condition1 = str_replace("'",'()',$condition);


if ($DB->db_production == 1) {
    $text = '/monitoring_international_2021';
}
else {
    $text = '';
}

//вычисляем GET
$get = array_keys($_GET)[0];
$single_table_name = array_keys($_GET)[0];

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
$active = ['','','','active'];

//объекты
$table =     new html_table();
$bootstrap = new Bootstrap();
$form = new html_form();
//echo $form->get_country_autocomplete(1,'Страна');
$bootstrap->GetHeader();

getUserInfoBadge($user->name);
$bootstrap->getBreadcrumb($bread,$active);


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
    $headers_db[] = $field['fn'];
}
//pre($headers_db,1);
echo implode(',',$headers_db);
$fields = $DB->getTableFieldsName($get);

for ($i = 0; $i < count($fields); $i++) {
    if ($fields[$i] != 'status' AND $fields[$i] != 'author') {
        $fields_for_interface[] = $fields[$i];
    }
}

$fields_for_interface = implode($fields_for_interface,',');


$form = new html_form();


$filter_year_text='';
if ($ad = $_POST['selector'] != NULL) {
    $filter_year_text = 'AND `year` = '.$_POST['selector'];
}

$content = $DB->getRecordsForTableInterfaceArray($get,"$condition",'',implode(',',$headers_db),1);

$content = array_values($content);

$m_up_left = [
    "<a href='print_excel.php?$get'>Сохранить в Excel всю таблицу<img src='https://zappysys.com/images/ssis-powerpack/ssis-export-excel-file-task.png' style='width: 25px; height: 25px; margin-left: 10px;'></a>",

    "<form enctype='multipart/form-data' method='post' action='excel_upload.php?$get'>
        <label class='form-label' for='excel'>Загрузить данные из Excel <a href='print_excel.php?$get&template=1'>(скачать шаблон загрузки)</a></label>
        <input class='form-control' type='file' name='excel' id='excel'>
        <button style='margin-top: 15px;' class='btn btn-sm btn-success' type='submit'>Загрузить</button>
    </form>",
    "<a class='btn btn-outline-success' href='add.php?$get' style='border-radius: 100px'><div style='color: black'>Добавить одну запись</div></a>"
];





/*$chekers = '<h5>Отчёт по выборочным данным:<br></h5>'.$form_check->openForm("print_excel.php?$get",'post');
for ($i = 0; $i < count($headers); $i++) {
    $chekers .= $form_check->getCheckBox($i,$headers[$i],$headers_db[$i]).'<br>';
}
$chekers .= $form_check->hidden('hidden','hidden');
$chekers .= $form_check->hidden($i,'hidden_count');
$chekers .= $form_check->closeForm('Отчёт','success');*/

//exit('1');
$filter_date = new html_form();
echo $get;
$chekers = $filter_date->getDataFilter(array('_date1','_date2'),$single_table_name,array('Начало периода','Конец периода'));

$m_up_right = [$chekers];


/*for ($i = 0; $i < count($content); $i++) {
    unset($content[$i][count($content[$i]) - 1]);
    //отсекаем года для указанных таблиц
    if ($table_name == 'Очная форма обучения' OR $table_name == 'Заочная форма обучения' OR $table_name == 'Иностранные слушатели') {
        unset($content[$i][count($content[$i]) - 1]);
    }
//        unset($content[$i][count($content[$i]) - 1]);
}*/

$menu_html_up_left = $bootstrap->setListMenu($m_up_left);
$menu_html_up_right = $bootstrap->setListMenu($m_up_right);
$html_table = $table->printTableWithAction($table_name,$headers,$content);
$menu_html = $bootstrap->setListMenu($menu_list);
$html = [$menu_html_up_left,$menu_html_up_right];
$html2 = [$html_table];
echo $bootstrap->setContainer([6,6],$html);


if (!isset(array_keys($_GET)[1])) {
    $get_param = 'Выберите календарный год';
}
else {
    $get_param = array_values($_GET)[1];
}

if (!isset(array_keys($_GET)[2])) {
    $get_param_vuz = 'Выберите учебное заведение';
}
else {
    $get_param_vuz = array_values($_GET)[2];

    $get_param_vuz_fullname = $DB->getRecordsByConditionFetchAssoc('users',"`login` = '$get_param_vuz'",'*');

    foreach ($get_param_vuz_fullname as $get_param_vuz_fullname1) {
        $name = $get_param_vuz_fullname1['fullname'];
    }
}


//array ('year' => 1) - ключ означает имя гет параметра, 1 - порядок его появления в урл адресе. 0 не занимать, на месте 0 всегда имя таблицы БД для отрисовки интерфейса
echo '<br>';
echo '<br>';
echo "<a href='$text/table.php?$get' style='text-align: center'><h5>Очистить фильтры</h5></a>";
echo '<br>';

//получаем все данные для фильтров нужной таблицы
$filters_data = (getFilters($get));
//pre($filters_data);
for ($i = 0; $i < count($filters_data); $i++) {
        $select[] = $DB->getSelectorForDataBase(
            $filters_data[$i]->db_table_name,
            $filters_data[$i]->db_table_fields,
            $filters_data[$i]->where,
            $filters_data[$i]->header,
            $filters_data[$i]->get_name,
            $filters_data[$i]->width,
            $filters_data[$i]->label
        );
        $boot[] = $bootstrap->setContainer([12],$select);
        unset($select);
}

for ($i = 0; $i < count($boot); $i++) {
    echo $boot[$i];
    echo '<br>';
}



$report_form = new html_form();
$report =  $report_form->openForm("filter_report.php",'get');
$report .= $report_form->hidden($condition1,'hid');
$report .= $report_form->hidden($get,'hid_t_n');
$report .= $report_form->closeForm('Скачать отчёт в Excel','btn btn-outline-success');

echo $bootstrap->setContainer([12],[$report]);
echo $bootstrap->setContainer([12],$html2);

