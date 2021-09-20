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
    switch ($block) {

        case true:

            //селектор для учебного года
            $object0 = new stdClass();
            $object0->db_table_name = 'years';
            $object0->db_table_fields = ['year_calendar','year_calendar'];
            $object0->where = '';
            $object0->header = 'Учебный год';
            $object0->get_name = 'year';
            $object0->width = 500;
            $object0->label = 'Учебный год';

            //селектор для учебного заведения
            $object1 = new stdClass();
            $object1->db_table_name = 'users';
            $object1->db_table_fields = ['login','fullname'];
            $object1->where = '`is_science` = 1';
            $object1->header = 'Учебное заведение';
            $object1->get_name = 'author';
            $object1->width = 800;
            $object1->label = 'Учебное заведение';
            $obj[] = $object0;
            $obj[] = $object1;

            switch ($get_name) {
                case in_array($get_name,$t):
                    //селектор для фильтра количества студентов "Более"
                    $object2 = new stdClass();
                    $object2->db_table_name = 'quated';
                    $object2->db_table_fields = ['qua','qua'];
                    $object2->where = '';
                    $object2->header = 'Больше';
                    $object2->get_name = 'qua';
                    $object2->width = 300;
                    $object2->label = 'Кол-во больше';
                    $obj[] = $object2;
                    
                    //селектор для фильтра количества студентов "Более"
                    $object3 = new stdClass();
                    $object3->db_table_name = 'quated';
                    $object3->db_table_fields = ['qua','qua'];
                    $object3->where = '';
                    $object3->header = 'Меньше';
                    $object3->get_name = 'qua2';
                    $object3->width = 300;
                    $object3->label = 'Кол-во меньше';
                    $obj[] = $object3;
                    
                    
            }

            break;


        /*case 'zaoch':

            //селектор для учебного года
            $object2 = new stdClass();
            $object2->db_table_name = 'years';
            $object2->db_table_fields = ['year_calendar','year_calendar'];
            $object2->where = '';
            $object2->header = 'Учебный год';
            $object2->get_name = 'year';
            $object2->width = 500;
            $object2->label = 'Учебный год';

            //селектор для учебного заведения
            $object3 = new stdClass();
            $object3->db_table_name = 'users';
            $object3->db_table_fields = ['login','fullname'];
            $object3->where = '`is_science` = 1';
            $object3->header = 'Учебное заведение';
            $object3->get_name = 'author';
            $object3->width = 800;
            $object3->label = 'Учебное заведение';
            $obj[] = $object2;
            $obj[] = $object3;
            break;

        case 'aus':

            //селектор для учебного года
            $object4 = new stdClass();
            $object4->db_table_name = 'years';
            $object4->db_table_fields = ['year_calendar','year_calendar'];
            $object4->where = '';
            $object4->header = 'Учебный год';
            $object4->get_name = 'year';
            $object4->width = 500;
            $object4->label = 'Учебный год';

            //селектор для учебного заведения
            $object5 = new stdClass();
            $object5->db_table_name = 'users';
            $object5->db_table_fields = ['login','fullname'];
            $object5->where = '`is_science` = 1';
            $object5->header = 'Учебное заведение';
            $object5->get_name = 'vuz';
            $object5->width = 800;
            $object5->label = 'Учебное заведение';
            $obj[] = $object4;
            $obj[] = $object5;
            break;

        case 'international':

            //селектор для учебного года
            $object6 = new stdClass();
            $object6->db_table_name = 'years';
            $object6->db_table_fields = ['year_calendar','year_calendar'];
            $object6->where = '';
            $object6->header = 'Учебный год';
            $object6->get_name = 'year';
            $object6->width = 500;
            $object6->label = 'Учебный год';

            //селектор для учебного заведения
            $object7 = new stdClass();
            $object7->db_table_name = 'users';
            $object7->db_table_fields = ['login','fullname'];
            $object7->where = '`is_science` = 1';
            $object7->header = 'Учебное заведение';
            $object7->get_name = 'vuz';
            $object7->width = 800;
            $object7->label = 'Учебное заведение';
            $obj[] = $object6;
            $obj[] = $object7;
            break;*/




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


//Были получены значения контрольных элементов массива
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


