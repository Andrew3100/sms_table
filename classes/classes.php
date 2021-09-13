<?php

//Класс для работы с таблицей
class html_table {

    //метод собирает содержимое таблицы по её имени и выводит заданные спец. колонки
    function getContentForInterFace($table_name,$spec_rows,$condition) {

        //$spec_rows - Ключ - заголовок, значение - поле, которое надо вывести
        $DB = new DB();
        $user = new user();
        $bootstrap = new Bootstrap();
        $user->setUserData();
        $spec_header = array_keys($spec_rows);
        $spec_field_db = array_values($spec_rows);
        $fields = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table_name'",'id,fn,descriptor_n');

        while ($field = mysqli_fetch_assoc($fields)) {
            $fs[] = $field['fn'];
            $descriptor_ns[] = $field['descriptor_n'];
        }

        $f_string = implode(',',$fs);
        $content = $DB->getRecordsForTableInterfaceArray($table_name,$condition,'',$f_string.',year');

        /*Выбираем идентификаторы для присвоения авторства записи в интерфейсе*/
        $ids = $DB->getRecordsForTableInterfaceArray($table_name,$condition,'','id');

        for ($i = 0; $i < count($ids); $i++) {
            $id[] = $ids[$i][0];
        }
        $table = '<table class="table table-bordered">';

        //выводим заголовки таблицы
        $table .= '<tr class="table-active text-center ">';
        for ($i = 0; $i < count($descriptor_ns); $i++) {
            $table .= '<td>';
            $table .= $descriptor_ns[$i];
            $table .= '</td>';
        }

        //выводим заголовки спец.колонок - для этого собираем ключи массива, переданного вторым параметром
        for ($i = 0; $i < count($spec_header); $i++) {
            $table .= '<td>';
            $table .= $spec_header[$i];
            $table .= '</td>';
        }

        $table .= '<td>';
        $table .= 'Действия';
        $table .= '</td>';
        $table .= '</tr>';

        $spec_val = array_values($spec_rows);
        for ($i=0; $i < count($content); $i++) {
            $table .= '<tr class="text-center">';
            for ($g = 0; $g < count($content[$i]); $g++) {
                $table .= '<td>';
                $table .= $content[$i][$g];
                $table .= '</td>';
            }


            $table .= '<td>';
            $table .= $DB->getRecordAuthorFullName($table_name,$id[$i]);
            $table .= '</td>';
            //Если пользователь админ системы или администрация Губернатора или же если он автор записи, делаем доступным набор действий
            if ($user->is_site_admin() OR $user->isGubernator() OR $DB->isRecordAuthor($table_name,$id[$i])) {
                $red = '<a href="update.php?red='.$id[$i].'&table='.$table_name.'">Редактировать</a>';
                $del = '<a href="delete.php?del='.$id[$i].'&table='.$table_name.'">Удалить</a>';
//                $red = '<a href="update.php?red='.$id[$i].'&table='.$table_name.'"><svg style="color: #ff9e00" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg></a>';
//                $del = '<a href="delete.php?del='.$id[$i].'&table='.$table_name.'"><svg onclick="return confirm(`Подтвердите удаление записи`)" style="color: red" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16"><path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/></svg></a>';
                $html = [$red,$del];
            }
            else {
                $html = ['<p style="text-align: center; color: red">Недоступно</p>'];
            }

            $actions = $bootstrap->setContainer([6,6],$html,'fluid');
            $table .= "<td>$actions</td>";
            $table .= '</tr>';

        }

        return $table;


    }

    function printTableWithAction($table_name_interface,$headers, $content) {
        include 'html/template.html';
        $bootstrap = new Bootstrap();

        $user = new user();
        $user->setUserData();

        $table = "<br><h4 style='text-align: center'>$table_name_interface</h4>";
        $table .= '<br><table class="table  table-bordered">';
        if ($table_name_interface == 'Действующие пользователи') {
            $if = 1;
        }
        else {
            $if = 0;
        }
        //цикл по заголовкам
        for ($i = $if; $i < count($headers); $i++) {
            $table .= "<td>{$headers[$i]}</td>";
        }
        $table .= "<td>Действия</td>";

        //цикл по контенту
        for ($i = 0; $i < count($content); $i++) {
            $table .= '<tr>';

            for ($g = 1; $g < count($content[$i])-1; $g++) {

                $table .= "<td>{$content[$i][$g]}</td>";
            }

            if ($user->is_site_admin() OR $user->isGubernator()) {
                $red = '<a href="update.php?red='.$content[$i][0].'&table='.array_keys($_GET)[0].'"><svg style="color: #ff9e00" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg></a>';
                $del = '<a href="delete.php?del='.$content[$i][0].'&table='.array_keys($_GET)[0].'"><svg onclick="return confirm(`Подтвердите удаление записи`)" style="color: red" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16"><path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/></svg></a>';
                $html = [$red,$del];
            }
        else {
                $html = ['<p style="text-align: center; color: red">Недоступно</p>'];
            }
            $actions = $bootstrap->setContainer([6,6],$html,'fluid');
            $table .= "<td>$actions</td>";
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }

    function printTable($table_name_interface,$headers, $content) {
//        pre($content);
//        pre($headers);
        $bootstrap = new Bootstrap();
        $user = new user();
        $user->setUserData();
        $war = 'Подтвердите удаление записи';

        include 'html/template.html';
        $table = "<br><h4 style='text-align: center'>$table_name_interface</h4>";
        $table .= '<br><table class="table  table-bordered">';
        //цикл по заголовкам
        for ($i = 0; $i < count($headers); $i++) {
            $table .= "<td>{$headers[$i]}</td>";
        }


        //цикл по контенту
        for ($i = 0; $i < count($content); $i++) {
            $table .= '<tr>';

            for ($g = 0; $g < count($content[$i]); $g++) {

                $table .= "<td>{$content[$i][$g]}</td>";
            }


        }
        $table .= '</table>';

        return $table;
    }


    function printUsers($table_name_interface,$headers, $content) {
//        pre($content);
//        pre($headers);
        $bootstrap = new Bootstrap();

        include 'html/template.html';
        $table = "<br><h4 style='text-align: center'>$table_name_interface</h4>";
        $table .= '<br><table class="table  table-bordered">';
        if ($table_name_interface == 'Действующие пользователи') {
            $if = 1;
        }
        else {
            $if = 0;
        }

        //цикл по заголовкам
        for ($i = $if; $i < count($headers); $i++) {
            $table .= "<td>{$headers[$i]}</td>";
        }

        //цикл по контенту
        for ($i = 0; $i < count($content); $i++) {
            $table .= '<tr>';

            for ($g = 1; $g < count($content[$i]); $g++) {

                $table .= "<td>{$content[$i][$g]}</td>";
            }
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }
}

// Класс для работы с БД
class DB {

    public $db_host;
    public $db_user;
    public $db_password;
    public $db_base;
    //свойство определяет находится ли проект на локальном сервере
    public $db_production;

    //определяем параметры подключения к базе данных в
    //зависимости сервера (локальный или продакшн)
    function db_param() {
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            $this->db_production = 0;
            $this->db_host = 'localhost';
            $this->db_user = 'root';
            $this->db_password = '';
            $this->db_base = 'administration2021';
            $this->db_production = 0;
        }
        else {
            //echo 'сервак';
            $this->db_production = 1;
            $this->db_host = 'bsu-do-sql-pegas.bsu.edu.ru';
            $this->db_user = 'ADMIN';
            $this->db_password = 'big#psKT';
            $this->db_base = 'administration2021';
            $this->db_production = 1;
        }
    }

    //метод собирает в массив названия всех стран
    function getCountryList() {
        $country_list = $this->getRecordsByConditionFetchAssoc('ref_country','','name,fullname');
        while ($country_lis = mysqli_fetch_assoc($country_list)) {
            $list[] = $country_lis['name'];
            $list[] = $country_lis['fullname'];
        }
        return $list;
    }
    //Метод выводит полное имя автора записи по имени таблицы и идентификатору записи
    function getRecordAuthorFullName($table_name,$record_id) {

        $record_logs = $this->getRecordsByConditionFetchAssoc($table_name,"`id` = '$record_id'",'author');
        while ($record_log = mysqli_fetch_assoc($record_logs)) {
            $author = $record_log['author'];
        }
        $author = $this->getRecordsByConditionFetchAssoc('users',"`login` = '$author'",'fullname');
        while ($autho = mysqli_fetch_assoc($author)) {
            $auth = $autho['fullname'];

        }
        return $auth;
    }


    function getDataTypes($table_name,$field) {
        $mysqli = $this->setConnect();

        //запрос на типы данных
        $sql = "sELECT DATA_TYPE FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA='administration2021' 
                AND TABLE_NAME='$table_name' AND COLUMN_NAME='$field'";
        $types = $mysqli->query($sql);
        while ($ty = mysqli_fetch_assoc($types)) {
            $type = $ty['DATA_TYPE'];
        }
        return $type;
    }

    //метод определяет являетс ли текущий пользователь автором записи
    function isRecordAuthor($table_name,$record_id) {
        $user = new user();
        $user->setUserData();
        $flag = false;
        $ids = $this->getRecordsByConditionFetchAssoc($table_name,"`id` = $record_id",'author');
        while ($id = mysqli_fetch_assoc($ids)) {
            $author = $id['author'];
        }
        if ($user->login == $author) {
            $flag = true;
        }
        return $flag;
    }


    //метод возвращает наименование раздела, к которому относится таблица
    function getBlockName($table) {

        $block = $this->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$table'",'get');
        while ($blocks = mysqli_fetch_assoc($block)) {
            $block_name = $blocks['get'];
        }
        return $block_name;
    }

    function getSelectorForDataBase($table_name,$fields,$where,$header,$get_name,$width=100,$label) {
        //текущий урл
        if (!isset($_GET[$get_name])) {
            $header = $label;
        }
        else {
            $header = $_GET[$get_name];
        }
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        //Ключ  значение массива, переданного в функцию
        $key = array_keys($get_name)[0];
        $value = array_values($get_name)[0];


        //разбор урла на компоненты
        /*pre*/($parse_url =  parse_url($url));
        $width .= 'px';
//        $variable = "<form style='border: none; width: $width; margin: auto' class='form-control' action='post'>";
//        $variable = "<label for='sel'>$label</label>";
        $variable = "<div style='text-align: center'>$label</div>";
        $variable .= "<select id='sel' style='width: $width; margin: auto' class='form-select' name='sel' onchange='document.location=this.options[this.selectedIndex].value'>";
//        $variable .= "<label class='form-label' for='sel'>$label</label>";
        $variable .=  "<option value='#'>$header</option>";
        $fields_string = implode(',',$fields);
        $records = $this->getRecordsByConditionFetchAssoc($table_name,$where,$fields_string);

        ($parse_url_query = explode('&',$parse_url["query"]));
        unset($parse_url_query[0]);

        //массив, где значения имеющиеся в урле гет параметры
        /*pre*/($parse_url_query = array_values($parse_url_query));

        for ($i = 0; $i < count($parse_url_query); $i++) {
            $key_val[] = explode('=',$parse_url_query[$i]);
        }

        for ($i = 0; $i < count($key_val); $i++) {
            for ($g = 0; $g < count($key_val[$i]); $g++) {
                $url_array[$key_val[$i][0]] = $key_val[$i][1];
            }
        }


        foreach ($records as $record) {
            //fields[1] - данные для списка
            //fields[0] - сопоставленный идентификатор
            if (isset($_POST['sel'])/* AND $_POST['sel'] == 1*/) {
                $selector_status = 'selected';
            }
            else {
                $selector_status = '';
            }

            $variable .=  "<option value='$url&$get_name={$record[$fields[0]]}' $selector_status>{$record[$fields[1]]}</option>";
        }
        $variable .=  "</select>";
        $variable .=  "</form>";

        return $variable;
    }

    function getDateFilterForm() {
        echo $f = '<input class="form-control" value="#" type="date" name="start" onclick="document.location=this.options[this.selectedIndex].value">';
    }

    function table_list() {
        $db = $this->setConnect();

    }

    //public $db_bas = 'administration2021';
    //метод устанавливает соединение с БД
    function setConnect() {
        $this->db_param();
        $mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_base);
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_error) {
            echo "Ошибка подключения к базе данных";
        }

        return $mysqli;
    }

    //метод возврашает ассоциативный массив
    //данных из заданной таблицы.
    //Можно передать условие отбора записей и отбираемые поля в строке через запятую
    //Если отбираемые поля и условие не переданы, то выберутся все записи по всем полям
    function getRecordsByConditionFetchAssoc($table,$where='',$fields = '*',$print='') {

        $mysqli = $this->setConnect();

        if ($where!='') {
            $condition = "WHERE $where";
        }
        else {
            $condition = '';
        }
        if ($print!='') {
            print_r("SELECT $fields FROM $table $condition");
        }
        return $records = $mysqli->query("SELECT $fields FROM $table $condition");
    }

    // Метод возвращает массив полей заданной таблицы
    function getTableFieldsName($table,$print='') {
        $mysqli = $this->setConnect();
        $fields = $mysqli->query("SHOW COLUMNS FROM $table");
        foreach ($fields as $fieldss) {
            $fields_list[] = $fieldss['Field'];
        }
        if ($print!='') {
            print_r("SHOW COLUMNS FROM $table");
        }
        return $fields_list;
    }

    function getRecordsForTableInterfaceArray($table,$where='',$order='',$fieldss = '*',$print='') {
        $mysqli = $this->setConnect();
        // получаем поля в виде массива
        $fields = $this->getTableFieldsName($table);


        if ($where!='') {
            $condition = "WHERE $where";
        }
        else {
            $condition = '';
        }
        if ($order!='') {
            $order_method = "ORDER BY $order";
        }
        $records = $mysqli->query("SELECT $fieldss FROM `$table` $condition $order_method");
        if ($print != '') {
            pre("SELECT $fieldss FROM $table $condition $order_method","Текст запроса к таблице $table");
        }
        //создаём из записей обычный массив
        foreach ($records as $array) {

            for ($i = 0; $i < count($fields); $i++) {
                if ($array[$i] == NULL) {
                   unset($array[$i]);
                }
            }

            $array_result[] = array_values($array);
            unset($array);
        }

        return $array_result;
    }

    //метод формирует excel файл из таблицы и заголовков
    function reportToExcel($content,$headers,$filename = 'Отчёт') {
        require_once 'Excel/Classes/PHPExcel.php';
        $cells = ['A', 'B', 'C', 'D', 'E',
                  'F', 'G', 'H', 'I', 'J',
                  'K', 'L', 'M', 'N', 'O',
                  'P', 'Q', 'R', 'S', 'T',
                  'U', 'V', 'W', 'X', 'Y', 'Z'];
        //Новый объект Excel
        $excel = new PHPExcel();

        $excel->setActiveSheetIndex(0);
        for ($i = 0; $i <count($headers); $i++) {
            $excel->getActiveSheet()->getColumnDimension()->setAutoSize(true);
            $excel->getActiveSheet()->setCellValue($cells[$i].'1',$headers[$i]);
        }

        for ($i = 1; $i <= count($content); $i++) {
            $s = 1;
            $n = 1;
            for ($g = 0; $g < count($content[$i]); $g++) {
                $excel->getActiveSheet()->setCellValue($cells[$g].($i),$content[$i][$g]);
                $s++;
                $n++;
            }
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx');
        header('Cash-Control: max-age=0');
        $file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
        $file->save('php://output');
    }

    // метод вставляет запись в таблицу.
    // Запись передаётся в виде объекта, где свойства - поля таблицы
    // Возвращает идентификатор вставленной записи
    function insert_record($table,$record_object,$print = '') {
        $mysqli = $this->setConnect();
        $keys = get_object_vars($record_object);
        $keys1 = array_keys($keys);

        $string_fields = '(`';
        for ($i = 0; $i < count($keys); $i++) {
            $string_fields .= $keys1[$i];
            $string_fields .= '`';
            if ($i!=count($keys)-1) {
                $string_fields .= ', ';
                $string_fields .= '`';
            }
        }
        $string_fields .= ')';

        $string_for_insert = "('";
        for ($i = 0; $i < count($keys); $i++) {
            $string_for_insert .= ($record_object->{$keys1[$i]});
            $string_for_insert .= "'";
            if ($i!=count($keys)-1) {
                $string_for_insert .= ', ';
                $string_for_insert .= "'";
            }
        }
        $string_for_insert .= ')';
        //считаем крайний ИД в таблице

        $ins = $mysqli->query("INSERT INTO `$table` $string_fields VALUES $string_for_insert");
        if (!$ins) {
            echo 'запись не вставлена';
            echo '<pre>';
            echo("INSERT INTO `$table` $string_fields VALUES $string_for_insert;");
            echo '</pre>';
        }
        if ($print!='') {
            echo '<pre>';
            echo("INSERT INTO $table $string_fields VALUES $string_for_insert;");
            echo '</pre>';
        }
        $last_id = $mysqli->query("SELECT MAX(`id`) FROM $table");
        // возвращаем вставленный ИД
        return (mysqli_fetch_assoc($last_id)["MAX(`id`)"]);
    }

    //Метод обновляет запись в таблице
    //Запись передаётся объектом, где свойства - поля БД
    //Для адресации записи передать ID этой записи
    function update_recordById($table,$object_upd,$id,$print='') {
        $mysqli = $this->setConnect();
        $keys = get_object_vars($object_upd);
        $keys1 = array_keys($keys);

        for ($i = 0; $i < count($keys); $i++) {
            $set =  $object_upd->{$keys1[$i]};
            $mysqli->query("UPDATE $table SET `{$keys1[$i]}` = '$set' WHERE id = $id");
        }
        if ($print!='') {
            print_r("UPDATE $table SET `{$keys1[$i]}` = '$set' WHERE id = $id");
        }
        return $id;
    }

    //метод удаляет данные из заданной таблицы
    //по идентификатору, который затем возвращает
    function deleteRecordById($table,$id) {
        $mysqli = $this->setConnect();
        $mysqli->query("DELETE FROM $table WHERE id = $id");
        return $id;
    }

    //метод создаёт таблицу. Параметры - имя, массив названий полей, массив типов данных полей
    //если в названии полей находится id, то он по дефолту создаётся автоинкриментным
    // с параметром not null (а также первичный ключ)
    function CreateTable($name,$headers_DB,$type) {
        $datas_string = '(';
        for ($i = 0; $i < count($headers_DB); $i++) {
            $set = '';
            if ($headers_DB[$i]=='id') {
                $set = 'not null auto_increment';
            }
            $datas_string .= "$headers_DB[$i] $type[$i] collate 'UTF8_general_ci' $set";
            $datas_string .= ', ';

        }
        $datas_string .= 'PRIMARY KEY  (`id`))';

        $DB = new DB;
        $mysqli = $DB->setConnect();

        $create = $mysqli->query("
        CREATE TABLE $name $datas_string
        ");
    }

    //метод получает имена полей БД для данной таблицы с учётом специфики системы
    function getFormFields($table) {
       $fields = $this->getTableFieldsName($table);

       for ($i = 0; $i < count($fields); $i++) {
           if ($fields[$i] != 'id' AND $fields[$i] != 'author' AND $fields[$i] != 'status') {
               $fieldss[] = $fields[$i];
           }
       }

       return $fieldss;

    }

    //метод получает интерфейсные имена полей (пользовательские)
    function getInterfaceFields($table) {
        $fields = $this->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'");
        foreach ($fields as $field) {
            $fieldd[] = $field['descriptor_n'];
        }
        return $fieldd;
    }



    function getAllRoles() {
        $roles = $this->getRecordsByConditionFetchAssoc('roles');
        foreach ($roles as $role) {
            $role_list[$role['id']] = $role['name'];
        }
        return $role_list;
    }

}

class html_form {


    function GetDynamicalSelectorForDate($get_param_name,$table_name,$id,$label) {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $form =  $this->hidden($url,'url');
        if ($get_param_name == 'start') {
            $form .=  $this->hidden($get_param_name,'get_name');
        }
        else {
            $form .=  $this->hidden($get_param_name,'get_name2');
        }
        $form .=  $this->hidden($table_name,'table_name');
        if ($get_param_name == 'start') {
            $form .= "<div style='text-align: center'>$label</div>";
            return $form .= '<input style="width: 200px; margin: auto" class="form-control" type="date" id="val" name="vale" onchange="window.location.replace(`${document.getElementById(`url`).value}&${document.getElementById(`get_name`).value}=${document.getElementById(`val`).value}`)">';
        }
        else {
            $form .= "<div style='text-align: center'>$label</div>";
            return $form .= '<input style="width: 200px; margin: auto" class="form-control" type="date" id="val1" name="vale1" onchange="window.location.replace(`${document.getElementById(`url`).value}&${document.getElementById(`get_name2`).value}=${document.getElementById(`val1`).value}`)">';
        }

        //http://cms/table.php?${document.getElementById(`table_name`).value}
        /*&${document.getElementById(`get_name`).value}=${document.getElementById(`date`).value}`)*/
    }


    function getDataFilter($id,$table_name,$label) {

        $form  = $this->openForm("table.php?$table_name",'post');
        $form .= $this->hidden($table_name,'get_d');
        $form .= $this->getFormByType('date',$id[0],$label[0],200);
        $form .= $this->getFormByType('date',$id[1],$label[1],200);
        $form .= $this->closeForm('Выбрать','btn btn-outline-warning');
        return $form;

    }


    function getSelectYear($name,$label='', $options, $width = 100) {
        $width .= 'px';
        $sel = '';
        if ($label != '') {
            $sel .= "<label class='form-label' for='select' style='margin-left: 10px; margin-top: 5px;'>$label</label>";
        }
        $sel .= "<select id='select' style = 'width: $width; float: left' class='form-control' name='$name'";
        for ($i = 0; $i < count($options); $i++) {
            $sel .= "
        <option value='$options[$i]'>$options[$i]</option>
        ";
        }
        return $sel .= '</select>';
    }

    //метод создаёт формы для удалённого автокомплита
    function get_country_autocomplete($id,$label,$width=600,$height=60) {

        include 'html/template.html';
        echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
        echo '<script src="/js/jquery/js/jquery-1.9.1.js"></script>';
        echo '<script src="/js/jquery/js/jquery-ui-1.10.3.custom.js"></script>';
        echo '<script src="/js/complete.js"></script>';

        $width .= 'px';
        $height.= 'px';
        $f = '';
        if ($label!='') {
            $f = "<label for='$id' class='form-label'>$label</label>";
        }
        return $f .= "<div class='ui-widget'>
                <input class='form-control' name='autocomplete' id='country_list' style='width: $width; height: $height'>
              </div>";

    }

    //метод создаёт скрытую форму
    function hidden($value,$name,$show='') {
        if ($show=='') {
            $type = 'hidden';
        }
        else {
            $type = 'text';
        }
        return "<input type=$type value='$value' id='$name' name='$name'>";
    }

    //Метод открывает форму. Параметры - файл обработки и метод ПД
    function openForm($action,$method='POST',$attr = '') {
        if ($attr != '') {
            /*атрибут для формы передачи файла*/
            $attr = "enctype='multipart/form-data'";
        }
        return "<form $attr action='$action' method='$method'>";
    }
    //Метод закрывает форму, можно передать цвет и текст кнопки. Если не передать, дефолтный цвет - success, текст - Отправить
    function closeForm($button_text="Отправить",$class='success') {
        return "<button type='submit' class='btn btn-$class'>$button_text</button>";
    }

    //Метод выводит поле ввода по типу
    //Идентификатор используется для обозначения атрибутов name и for
    //Можно передать текст Bootstrap-метки для поля ввода. Если не передать, будет просто поле ввода
    //Можно указать ширину, если не указать, дефолт 600пкс
    function getFormByType($type,$id,$label='',$width=600,$value='') {
        include 'html/template.html';
        $width .= 'px';
        $f = '';

        if ($label!='') {
            $f .= "<label for='$id' class='form-label'>$label</label>";
        }
        return $f .= "<input name='name$id' type='$type' value='$value' class='form-control' id='$id' style='width: $width;'>";
    }

    function getCheckBox($id,$label='',$value,$status='checked') {
        include 'html/template.html';
        if ($status != 'checked') {
            $cheked = '';
        }
        else {
            $cheked = 'checked';
        }
        $f = "<input name='name$id' value='$value' $cheked type='checkbox' class='form-check-input' id='$id'>";
        if ($label!='') {
            $f .= "<label for='$id' style='margin-left: 10px; margin-bottom: 5px;' class='form-check-label'>$label</label>";
        }
        return $f;
    }
}

class user {
    public $id;
    public $name;
    public $login;

    //метод заполняет поля public заданного пользователя
    function setUserData() {
        $DB = new DB();
        $datas = $DB->getRecordsByConditionFetchAssoc('users',"`login` = '{$_COOKIE['user']}'");
        foreach ($datas as $data) {
            $this->id =    $data['id'];
            $this->login = $data['login'];
            $this->name =  $data['fullname'];
        }
    }

    function isGubernator() {
        $flag = false;
        if ($this->name == 'Администрация Губератора Белгородской области') {
            $flag = true;
        }
        return $flag;
    }

    function is_site_admin() {
        $flag = false;
        if ($this->name == 'Администратор информационной системы') {
            $flag = true;
        }
        return $flag;
    }



    //Метод возвращает список ролей пользователя
    function getRoleListByLogin($login) {
        $DB = new DB();
        $role_list = $DB->getRecordsByConditionFetchAssoc('users',"`login` = '$login'");
        foreach ($role_list as $roles) {
            $role = $roles['role_list'];
            if ($roles['role_list'] == null) {
                $role = 'Учётная запись заблокирована';
            }
            else {
                if (strlen($role) > 1) {
                    $role = explode(',',$role);
                }
                else {
                    if (strlen($role) == 1) {
                        $role = $role;
                    }
                    else {
                        if (strlen($role) == 'all') {
                            $alls = $DB->getRecordsByConditionFetchAssoc('roles');
                            foreach ($alls as $all) {
                                $role[] = $all['id'];
                            }
                        }
                    }
                }
            }
        }


        return $role;
    }


    //метод авторизует пользователя в системе
    function authUser($login,$password) {
        $DB = new DB;
        $DB->setConnect();

//        $password = md5($password);

        $users = $DB->getRecordsByConditionFetchAssoc('users',"`login` = '$login' AND `password` = '$password' AND `ban` = 0",'*');
//        if (count(mysqli_fetch_assoc($users)) > 0) {
            foreach ($users as $user) {
                $this->name = $user['fullname'];
            }
            if (setcookie('user',$login,time() + 3600*24, "/")) {

                $log = new log();
                $log->fixed($_COOKIE,'Авторизация в системе');
                header('Location: index.php?data=1');
            }
//        }


    }
    //метод выкидывает пользователя из системы
    function unAuth_user() {
        setcookie('user','nahuy_otsyuda',time() - 10000*24, "/");
        header('Location: auth_form.php');
    }

}

//Класс для управления элементами интерфейса Bootstrap
class Bootstrap {

    function getTab($names,$content) {
        $tab_n = '<div class="tabs">';
        $form = new html_form();

        for ($i = 0; $i < count($names); $i++) {
            $tab_n .= '<input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
    <label for="tab-btn-1">Вкладка 1</label>';
        }
        $tab_n .= '</div>';
        $tab_c = '';

        for ($i = 0; $i < count($content); $i++) {
             $tab_c .= '<div id="content-'.$i.'">';
                $tab_c .= $content[$i];
             $tab_c .= '</div>';
        }
        return $tab_n.$tab_c;
    }

    //шапка
    function GetHeader() {
        echo '
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <a class="navbar-brand" href="#">Мониторинг деятельности по международному сотрудничеству</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#">Главная</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Документация</a>
                    </li>
                  </ul>
                  
                </div>
              </div>
            </nav>';
    }

    //Метод создаёт хлебную крошку
    function getBreadcrumb($links,$active) {
//        pre($links);
        $keys = array_keys($links);

//        pre($keys);
        echo '<div class="container-fluid">
                <nav style="background-color: #e9ecef; height: auto" aria-label="breadcrumb">
                <ol class="breadcrumb" style=" margin-left: 16px;">';
        for ($i = 0; $i < count($keys); $i++) {
            if ($active[$i] != '') {
                $open_act_tag = '<b>';
                $close_act_tag = '</b>';
            }
            else {
                $open_act_tag  = '';
                $close_act_tag = '';
            }
            echo "<li class='breadcrumb-item $active[$i]' style='margin-top: 9px; margin-bottom: 12px;'>$open_act_tag<a href='$keys[$i]'>{$links[$keys[$i]]}</a>$close_act_tag</li>";
        }
        echo '</div></ol></nav>';
    }

    //Метод создаёт контейнер BootStrap. Параметры:
    //массив - сетка ширин col
    //html_content - элементы для столбцов
    //fluid - обрезка или на весь монитор
    function setContainer($array_grid,$html_content,$fluid='') {
        if ($fluid!='') {
            $fluid = '-fluid';
        }
        $container = "<div class='container$fluid'>
                <div class='row'>";

        for ($i = 0; $i < count($array_grid); $i++) {
            $container.= "<div class='col-$array_grid[$i]'>";
            $keys = array_keys($html_content);
            //если встречаем ключ со словом include - выполняем подключение файла, иначе выводим компонент на экран
            if (is_numeric(strpos($keys[$i],'include'))) {
                include $html_content['for_include_content'];
            }
            else {
                $container.= $html_content[$i];
            }

            $container.= "</div>";
        }
        $container.= '
                </div>
              </div>
            ';
        return $container;
    }

    function setListMenu($names_array,$width='') {
        if ($width!='') {
            $width .= 'px';
        }
        $list = "<ul class='list-group' style='width: $width'>";
        for ($i = 0; $i < count($names_array); $i++) {
            if ($names_array[$i] != '') {
                $list .= "
            <li class='list-group-item'>$names_array[$i]</li>
            ";
            }
        }
        $list .= '</ul>';
        return $list;
    }

}

class block {

    //функция создаёт таблицу в разделе. Параметры:
    //$block_name - имя раздела, в котором создаётся таблица
    //$table_name - имя таблицы
    //$DB_table_name - имя таблицы для базы данных
    //$types_data_arr - массив типов данных для таблицы
    //$headers_DB - поля этой таблицы для базы данных
    //$headers_interface - заголовки для интерфейса
    //метод создаёт новую таблицу в разделе
    function addTablesByBlock($block_name,$get,$table_name,$DB_table_name,$types_data_arr,$headers_DB,$headers_interface) {
        $DB = new DB();
        $DB->setConnect();
        //создаём таблицу для базы данных
        $DB->CreateTable($DB_table_name,$headers_DB,$types_data_arr);
        for ($i = 0; $i < count($headers_DB); $i++) {
            $object = new stdClass();
            $object->get_name = $get;
            $object->type_name = $types_data_arr[$i];
            $object->fn = $headers_DB[$i];
            $object->descriptor_n = $headers_interface[$i];
            $object->isused = 1;
            $object->requred = 1;
            $DB->insert_record('bsu_form_data',$object);
        }
        $object = new stdClass();
        $object->linkname = $block_name;
        $object->link_get = $get;
        $object->header = $table_name;
        $object->status = 1;
        $DB->insert_record('administration_table_link',$object);


    }

    //пример работы с методом:
    /*$block = new block();
    $types = ['int','text','date','text','text','int'];
    $headers = ['id','name','surname','parazh','age','status'];
    $headers_interface = ['Идентификатор','Имя','Фамилия','Поражение','Возраст','Статус'];
    $block->addTablesByBlock(
        'Новый раздел',
        'get',
        'Таблица нового раздела',
        'new_block_table',
        $types,
        $headers,
        $headers_interface
);*/
}

class log {

    function fixed($user,$event) {
        $DB = new DB();
        $obj = new stdClass();
        $obj->event = $event;
        $obj->date = date('d-m-Y',time());
        $obj->time = date('H:i:s',time());
        $obj->username = $user;
        $obj->status = 1;
        $obj->field = null;
        $DB->insert_record('logs',$obj);

    }
}

