<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
require_once 'html/template.html';

//Запрос на отбор таблиц с кол-вом участников
/*$updates = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`descriptor_n` LIKE 'Количество%'",'fn,get_name,descriptor_n',1);*/


$up = $DB->getRecordsByConditionFetchAssoc('aus','','id,qua');
$get = array_keys($_GET)[0];

//получаем массив, в котором храним уловие отбора данных. Ключ = поле БД, значение - соотв.
($filters = parseGetData());
//На основе данного массива генерируем условие отбора данных для БД
/*pre*/($keys   = array_keys($filters));
/*pre*/($values = array_values($filters));



if ((count($keys)) > 0) {
    $condition .= '`status` = 1 AND ';
    for ($i = 0; $i < count($filters); $i++) {

        if ($i != count($filters)-1) {
            $and = 'AND ';
        }
        else {
            $and = '';
        }
        if ($keys[$i] == 'start' OR $keys[$i] == 'qua') {
            $math_symbol = '>';
        }
        else {
            if ($keys[$i] == 'stop' OR $keys[$i] == 'qua2') {
                $math_symbol = '<';
            }
            else {
                $math_symbol = '=';
            }
        }
        $condition .= "`{$keys[$i]}` $math_symbol '$values[$i]' $and";

    }
}
else {
    $condition .= '`status` = 1';
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
$fields = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$get'",'*');

foreach ($fields as $field) {
    //готовые заголовки для таблицы
    $headers[] = $field['descriptor_n'];
    $headers_db[] = $field['fn'];
    //для определения того сколько селекторов дат надо вывести в интерфейс - ищем поля дат в БД
    if ($DB->getDataTypes($get,$field['fn']) == 'date') {
         $fls_date[] = $field['fn'];
    }
}
//pre($headers_db,1);

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
$impl = implode(',',$headers_db);
$content = $DB->getRecordsForTableInterfaceArray($get,"$condition",'',$impl);




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

$m_up_right = [$chekers];

$menu_html_up_left = $bootstrap->setListMenu($m_up_left);
$menu_html_up_right = $bootstrap->setListMenu($m_up_right);
$html_table = $table->printTableWithAction($table_name,$headers,$content);
$menu_html = $bootstrap->setListMenu($menu_list);
$html = [$menu_html_up_left];
$html2 = [$html_table];
echo $bootstrap->setContainer([12],$html);



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
$form = new html_form();

if (count($fls_date) == 1) {
    echo $form->GetDynamicalSelectorForDate('start',$get,'st','Начало периода');
    echo '<br>';
}
else {
    if (count($fls_date) == 2) {
        echo $form->GetDynamicalSelectorForDate('start',$get,'st','Начало периода');
        echo '<br>';
        echo $form->GetDynamicalSelectorForDate('stop',$get,'st','Конец периода');
    }
}

echo '<br>';
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col text-center">';
$hidden_query = new html_form();
echo $hidden_query->openForm("excel_filter.php?$get",'post');
$c = str_replace("'",'(',$condition);
echo $hidden_query->hidden("$impl,,,,,$c",'query');
echo $hidden_query->closeForm('Отчёт по фильтрам','warning');
echo '</div>';
echo '</div>';
echo '</div>';
echo '<br>';
$html_table = new html_table();
$spec = ['Год' => 'id','Владелец записи' => 'id'];
$table =  $html_table->getContentForInterFace($get,$spec,$condition);
//echo $bootstrap->setContainer([12],[$report]);
echo $bootstrap->setContainer([12],[$table]);

