<?php
//Класс для работы с таблицей
class html_table {
    function printTable($headers, $content) {
        include 'html/template.html';
        $table = '<table class="table table-dark table-bordered">';
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
            $table .= '</tr>';
        }
        $table .= '</table>';

        return $table;
    }
}

// Класс для работы с БД
class DB {
    public $db_host = 'localhost';
    public $db_user = 'root';
    public $db_password = '';
    public $db_base = 'administration2021';
    //метод устанавливает соединение с БД
    function setConnect() {
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
    function getTableFieldsName($table) {
        $mysqli = $this->setConnect();
        $fields = $mysqli->query("SHOW COLUMNS FROM $table");
        foreach ($fields as $fieldss) {
            $fields_list[] = $fieldss['Field'];
        }
        return $fields_list;
    }

    function getRecordsForTableInterfaceArray($table,$where='',$fieldss = '*') {
        $mysqli = $this->setConnect();
        // получаем поля в виде массива
        $fields = $this->getTableFieldsName($table);


        if ($where!='') {
            $condition = "WHERE $where";
        }
        else {
            $condition = '';
        }
        $records = $mysqli->query("SELECT $fieldss FROM $table $condition");
        //создаём из записей обычный массив
        foreach ($records as $records1) {
            for ($i = 0; $i < count($fields); $i++) {
                $array[] = $records1[$fields[$i]];
            }
            $array_result[] = $array;
            unset($array);
        }
        return $array_result;
    }

    //метод формирует excel файл из таблицы и заголовков
    function reportToExcel($content,$headers) {
        require_once 'Excel/Classes/PHPExcel.php';
        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        //Новый объект Excel
        $excel = new PHPExcel();
        //Определяем стартовую ячейку для формирования документа
        $excel->setActiveSheetIndex(0);

        for ($i = 0; $i <count($headers); $i++) {
            $excel->getActiveSheet()->setCellValue($cells[$i].'1',$headers[$i]);
        }

        for ($i = 0; $i < count($content); $i++) {
            $s = 1;
            $n = 1;
            for ($g = 0; $g < count($content[$i]); $g++) {
                $excel->getActiveSheet()->setCellValue($cells[$g].($i),$content[$i][$g]);
                $s++;
                $n++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="hello.xlsx');
        header('Cash-Control: max-age=0');
        $file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
        $file->save('php://output');
    }
    // метод вставляет запись в таблицу.
    // Запись передаётся в виде объекта, где свойства - поля таблицы
    // Возвращает идентификатор вставленной записи
    function insert_record($table,$record_object) {
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
        $last_id = $mysqli->query("SELECT MAX(`id`) FROM $table");

        $mysqli->query("INSERT INTO $table $string_fields VALUES $string_for_insert");
        // возвращаем вставленный ИД
        return (mysqli_fetch_assoc($last_id)["MAX(`id`)"]) + 1;
    }
    //Метод обновляет запись в таблице
    //Запись передаётся объектом, где свойства - поля БД
    //Для адресации записи передать ID этой записи
    function update_recordById($table,$object_upd,$id) {
        $mysqli = $this->setConnect();
        $keys = get_object_vars($object_upd);
        $keys1 = array_keys($keys);

        for ($i = 0; $i < count($keys); $i++) {
            $set =  $object_upd->{$keys1[$i]};
            $mysqli->query("UPDATE $table SET `{$keys1[$i]}` = '$set' WHERE id = $id");
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

}

class html_form {

    //Метод открывает форму. Параметры - файл обработки и метод ПД
    function openForm($action,$method='POST') {
        echo "<form action='$action' method='$method'>";
    }
    //Метод закрывает форму, можно передать цвет и текст кнопки. Если не передать, дефолтный цвет - success, текст - Отправить
    function closeForm($button_text="Отправить",$class='success') {
        echo "<button type='submit' class='btn btn-$class'>$button_text</button>";
    }

    //Метод выводит поле ввода по типу
    //Идентификатор используется для обозначения атрибутов name и for
    //Можно передать текст Bootstrap-метки для поля ввода. Если не передать, будет просто поле ввода
    //Можно указать ширину, если не указать, дефолт 600пкс
    function getFormByType($type,$id,$label='',$width=600) {
        include 'html/template.html';
        $width .= 'px';
        $f = '';
        if ($label!='') {
            $f .= "<label for='$id' class='form-label'>$label</label>";
        }
        return $f .= "<input name='name$id' type='$type' class='form-control' id='$id' style='width: $width;'>";
    }


}

class user {
    public $firstname = '';
    public $surname = '';
    public $name = '';
    public $login = '';
    public $user_md5 = '';
    public $user_status = '';
    //метод авторизует пользователя в системе
    function authUser($login,$password) {
        $DB = new DB;
        $DB->setConnect();
        $password = md5($password);
        $users = $DB->getRecordsByConditionFetchAssoc('users',"login = '$login' AND `password` = '$password' AND ban = 0",'*');
        if (count(mysqli_fetch_assoc($users)) > 0) {
            if (setcookie('user',$login,time() + 3600, "/")) {
//               header('Location: index.php?data=1');
            }
        }
    }
    //метод выкидывает пользователь из системы
    function unAuth_user() {
        setcookie('user','nahuy_otsyuda',time() - 10000, "/");
    }

}

//Класс для управления элементами интерфейса Bootstrap
class Bootstrap {
    function GetHeader() {
        echo '
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <a class="navbar-brand" href="#">ЭМОУ</a>
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
                  <form class="d-flex">
                    <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Поиск</button>
                  </form>
                </div>
              </div>
            </nav>';
    }

    //Метод создаёт контейнер BootStrap. Параметры:
    //массив - сетка ширин col
    //html_content - элементы для столбцов
    //fluid - обрезка или на весь монитор
    function setContainer($array_grid,$html_content,$fluid='') {
        if ($fluid!='') {
            $fluid = '-fluid';
        }
        echo "<div class='container$fluid'>
                <div class='row'>";
           for ($i = 0; $i < count($array_grid); $i++) {
               echo "<div class='col-$array_grid[$i]'>";
               $keys = array_keys($html_content);
               //если встречаем ключ со словом include - выполняем подключение файла, иначе выводим компонент на экран
               if (is_numeric(strpos($keys[$i],'include'))) {
                   include $html_content['for_include_content'];
               }
               else {
                   echo $html_content[$i];
               }

               echo "</div>";
           }
        echo '
                </div>
              </div>
            ';
    }

    function setListMenu($names_array) {
        $list = '<ul class="list-group">';
        for ($i = 0; $i < count($names_array); $i++) {
            $list .= "
            <li class='list-group-item'>$names_array[$i]</li>
            ";
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



