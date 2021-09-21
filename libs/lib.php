<?php

require_once 'classes/classes.php';
$user = new user();
$user->setUserData();
//отладка
debug();
//авторизация
is_auth();

function is_auth() {
    if ($_COOKIE['user'] == '') {
        header('Location: auth_form.php');
    }
}

is_auth();

function getYear() {
    return date('Y',time());
}


function getContentByMenu() {
    if ($_GET['data']==1) {
        $content = 'data.php';
    }
    else {
        if ($_GET['reports']==1) {
            $content = 'select_import.php';
        }
        else {
            if ($_GET['import']==1) {
                $content = 'select_import.php';
            }
            if ($_GET['create']) {
                $content = 'create_block.php';
            }
            if ($_GET['create_template']) {
                $content = 'create_block1.php';
            }
            if ($_GET['roots']) {
                $content = 'roots.php';
            }
            if ($_GET['create_user']) {
                $content = 'create_user.php';
            }
            if ($_GET['main']) {
                $content = 'hi.php';
            }
            if ($_GET['logs']) {
                $content = 'logs.php';
            }

        }
    }
    return $content;
}

//отладка
function debug() {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
}
//вывод для отладки
function pre($object,$header='Объект') {
    echo "<h6><b>$header</b></h6>";
    echo '<pre>';
    var_dump($object);
    echo '</pre>';
}


//функция преобразует текстовое представление даты (по стандартам Excel) в нормальное
function GetDateByText($text) {
    $stack1 = 86400 * $text;
    $stack2 = time();
     $razd = $stack1 - $stack2;
    //секунд с 1900 года
    $text1900 = 86400 * $text;
    //секунд в разнице эпох
    $hueta = 25569*86400;
    $timestamp = $text1900 - $hueta;
    $date = date('d-m-Y',$timestamp);
    return $date;
}


function getUserInfoBadge($name) {
    echo '<div class="container-fluid">
        <div class="row">
            <div class="col">';
    echo "<div style='text-align: right'>Вы зашли под учётной записью <b>$name</b> (<a href='exit.php'>Выход</a>)</div>";
    echo '      </div>
        </div>
      </div>';
}

//функция определяет какие фильтры выводить для той или иной таблицы
function getFilters($get_name) {

    //Данная функция на основе названия таблицы БД в GET-параметре выберет необходимые объекты и вернёт их
    //Затем данные объекты будут переданы в метод создания селектора (файл !!!table.php!!!)
    //Если в системе появляется новая таблица, то необходимо создать новый CASE с её названием
    //И в него засунуть объекты, необходимые для данной таблицы (регулируется техническим заданием от заказчика)

    /*
     * !!!СТРУКТУРА ОБЪЕКТА!!!:
     * db_table_name    - таблица базы данных из которой берутся значения селектора
     * db_table_fields  - !!!МАССИВ!!! Первое поле - то, что будет в списке, второе - сопоставленный ему GET-параметр в адресной строке
     * where            - Условие отбора записей
     * header           - Дефолтный заголовок селектора
     * get_name         - Имя GET-Параметра !!!ДОЛЖНО СОВПАДАТЬ С ПОЛЕМ ТАБЛИЦЫ БАЗЫ ДАННЫХ, КОТОРОЕ МЫ ХОТИМ ИЗВЛЕЧЬ ДЛЯ ФИЛЬТРАЦИИ ДАННЫХ!!!
     * width            - Ширина селектора (CSS)
     * label            - Название (Выводится в центре над селектором)
     * gr_by            - Поле, по которому надо группировать
     *

     * !!!Созданные объекты обязательно записать в массив!!!
     *
     *
     * */

    $DB = new DB();
    $block = $DB->getBlockName($get_name);
    $ts = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`descriptor_n` LIKE 'Количество%'",'fn,get_name,descriptor_n');
    while($tss = mysqli_fetch_assoc($ts)) {
        $t[] = $tss['get_name'];
    }

    $tableClass = $DB->getTableClass($get_name);

    //Селектор для календарного года. Доступен всем таблицам
    $year_selector = new stdClass();
    $year_selector->db_table_name = 'years';
    $year_selector->db_table_fields = ['year_calendar','year_calendar'];
    $year_selector->where = '';
    $year_selector->header = 'Год';
    $year_selector->get_name = 'year';
    $year_selector->width = 300;
    $year_selector->label = 'Год';
    $obj[] = $year_selector;

    //селекторы для классов таблицы
    switch ($tableClass) {
        //для таблицы с классом "Мероприятия" обязательный селектор с наименованием мероприятия, статуса, и места проведения
        case 'event':
            //селектор для наименования мероприятия
            $event_name_selector = new stdClass();
            $event_name_selector->db_table_name = $get_name;
            $event_name_selector->db_table_fields = ['event_name','event_name'];
            $event_name_selector->where = '';
            $event_name_selector->header = 'Наименование мероприятия';
            $event_name_selector->get_name = 'event_name';
            $event_name_selector->width = 300;
            $event_name_selector->label = 'Наименование мероприятия';
            $obj[] = $event_name_selector;

            //селектор статуса мероприятия
            $event_status_selector = new stdClass();
            $event_status_selector->db_table_name = 'ref_event_status';
            $event_status_selector->db_table_fields = ['name','name'];
            $event_status_selector->where = '';
            $event_status_selector->header = 'Статус мероприятия';
            $event_status_selector->get_name = 'status';
            $event_status_selector->width = 300;
            $event_status_selector->label = 'Статус мероприятия';
            $obj[] = $event_status_selector;


            //селектор места проведения
            $event_location_selector = new stdClass();
            $event_location_selector->db_table_name = $get_name;
            $event_location_selector->db_table_fields = ['event_location','event_location'];
            $event_location_selector->where = '';
            $event_location_selector->header = 'Место проведения мероприятия';
            $event_location_selector->get_name = 'event_location';
            $event_location_selector->width = 300;
            $event_location_selector->label = 'Место проведения мероприятия';
            $obj[] = $event_location_selector;
            break;

        case 'statistic':
            //селектор места проведения
            $country = new stdClass();
            $country->db_table_name = $get_name;
            $country->db_table_fields = ['country','country'];
            $country->where = '';
            $country->header = 'Страна';
            $country->get_name = 'country';
            $country->width = 300;
            $country->label = 'Страна';
            $country->gr_by = 'country';
            $obj[] = $country;
            break;

    }


    switch ($block) {

        case 'education':
            //селектор для учебного заведения
            $university = new stdClass();
            $university->db_table_name = 'users';
            $university->db_table_fields = ['login','fullname'];
            $university->where = '`is_science` = 1';
            $university->header = 'Учебное заведение';
            $university->get_name = 'author';
            $university->width = 500;
            $university->label = 'Учебное заведение';
            $obj[] = $university;
            break;


        case 'culture':

            //селектор для фильтра количества участников "Более"
            $big = new stdClass();
            $big->db_table_name = 'quated';
            $big->db_table_fields = ['qua','qua'];
            $big->where = '';
            $big->header = 'Больше';
            $big->get_name = 'qua';
            $big->width = 300;
            $big->label = 'Кол-во участников больше';
            $obj[] = $big;

            //селектор для фильтра количества участников "Более"
            $small = new stdClass();
            $small->db_table_name = 'quated';
            $small->db_table_fields = ['qua','qua'];
            $small->where = '';
            $small->header = 'Меньше';
            $small->get_name = 'qua2';
            $small->width = 300;
            $small->label = 'Кол-во участников меньше';
            $obj[] = $small;
            break;

    }

    switch ($get_name) {
        //Если текущая таблица в списке таблиц с количественными данными
        case in_array($get_name, $t):
            //селектор для фильтра количества студентов "Более"
            $date_start = new stdClass();
            $date_start->db_table_name = 'quated';
            $date_start->db_table_fields = ['qua', 'qua'];
            $date_start->where = '';
            $date_start->header = 'Больше';
            $date_start->get_name = 'qua';
            $date_start->width = 300;
            $date_start->label = 'Кол-во больше';
            $obj[] = $date_start;


            //селектор для фильтра количества студентов "Более"
            $date_stop = new stdClass();
            $date_stop->db_table_name = 'quated';
            $date_stop->db_table_fields = ['qua', 'qua'];
            $date_stop->where = '';
            $date_stop->header = 'Меньше';
            $date_stop->get_name = 'qua2';
            $date_stop->width = 300;
            $date_stop->label = 'Кол-во меньше';
            $obj[] = $date_stop;
            break;
    }

    switch ($get_name) {
        case 'change':
            //селектор для фильтра количества студентов "Более"
            $university_by_change = new stdClass();
            $university_by_change->db_table_name = 'change';
            $university_by_change->db_table_fields = ['company','company'];
            $university_by_change->where = '';
            $university_by_change->header = 'Организация для обмена';
            $university_by_change->get_name = 'company';
            $university_by_change->width = 300;
            $university_by_change->label = 'Организация для обмена';
            $university_by_change->gr_by = 'company';
            $obj[] = $university_by_change;
            break;


        case 'cult_doc':
            //селектор для фильтра количества студентов "Более"
            $culture_doc_organization = new stdClass();
            $culture_doc_organization->db_table_name = 'cult_doc';
            $culture_doc_organization->db_table_fields = ['country','country'];
            $culture_doc_organization->where = '';
            $culture_doc_organization->header = 'Страна';
            $culture_doc_organization->get_name = 'country';
            $culture_doc_organization->width = 300;
            $culture_doc_organization->label = 'Страна';
            $culture_doc_organization->gr_by = 'country';
            $obj[] = $culture_doc_organization;
            break;


        case 'work':
            //выборки для муниципального округа, гражданской принадлежности, сферы деятельности
            $work_munic_round = new stdClass();
            $work_munic_round->db_table_name = 'work';
            $work_munic_round->db_table_fields = ['munic_round','munic_round'];
            $work_munic_round->where = '';
            $work_munic_round->header = 'Муниципальный округ';
            $work_munic_round->get_name = 'munic_round';
            $work_munic_round->width = 300;
            $work_munic_round->label = 'Муниципальный округ';
            $work_munic_round->gr_by = 'munic_round';
            $obj[] = $work_munic_round;

            //выборки для муниципального округа, гражданской принадлежности, сферы деятельности
            $work_grazdanstvo = new stdClass();
            $work_grazdanstvo->db_table_name = 'work';
            $work_grazdanstvo->db_table_fields = ['grazhdanstvo','grazhdanstvo'];
            $work_grazdanstvo->where = '';
            $work_grazdanstvo->header = 'Гражданская принадлежность';
            $work_grazdanstvo->get_name = 'grazhdanstvo';
            $work_grazdanstvo->width = 300;
            $work_grazdanstvo->label = 'Гражданская принадлежность';
            $work_grazdanstvo->gr_by = 'grazhdanstvo';
            $obj[] = $work_grazdanstvo;

            //выборки для муниципального округа, гражданской принадлежности, сферы деятельности
            $work_sphera_d = new stdClass();
            $work_sphera_d->db_table_name = 'work';
            $work_sphera_d->db_table_fields = ['sphera_d','sphera_d'];
            $work_sphera_d->where = '';
            $work_sphera_d->header = 'Сфера деятельности';
            $work_sphera_d->get_name = 'sphera_d';
            $work_sphera_d->width = 300;
            $work_sphera_d->label = 'Сфера деятельности';
            $work_sphera_d->gr_by = 'sphera_d';
            $obj[] = $work_sphera_d;
            break;





    }

    return $obj;
}

function parseGetData() {
    //данный алгоритм парсит урл, отбирает самые последние значения для всех параметров ГЕТ и проводит сортировку данных в таблице по данным параметрам
    $parse = ($array = explode('&',parse_url($url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])['query']));
    unset($parse[0]);
    $parse = array_values($parse);

    for ($i = 0; $i < count($parse); $i++) {
        $parse_string = explode('=',$parse[$i]);
        $object = new stdClass();
        for ($g = 0; $g < count($parse_string); $g++) {
            $object->{$parse_string[0]} = $parse_string[1];
        }
        $obj[] = get_object_vars($object);
        unset($object);
    }

    for ($i = 0; $i < count($obj); $i++) {
        $parameters[] = array_keys($obj[$i])[0];
        $values[] = array_values($obj[$i])[0];
    }

    //pre($parameters,исх);
    for ($i = 0; $i < count($parameters); $i++) {
        $fixed = $parameters[$i];
        //echo 'Зафиксировал значение '.$fixed.'<br>';
        for ($g = $i+1; $g < count($parameters); $g++) {
            //временный массив
            $temporary_array[] = $parameters[$g];
        }
        if (!(in_array($fixed,$temporary_array) == TRUE)) {
            //echo 'удалил '.$parameters[$i].'<br>';
            $control[] = $i;
        }
        //удаляем временный массив
        unset($temporary_array);

    }
    //сопоставляем массивы и самые актуальные данные пишем в объект, который в итоге вернём
    //pre($control,1);
    $database_filter = new stdClass();
    for ($i = 0; $i < count($parameters); $i++) {
        if (in_array($i,$control)) {
            $database_filter->{$parameters[$i]} = $values[$i];
        }
    }
    return get_object_vars($database_filter);
//pre($parameters,2);
}


