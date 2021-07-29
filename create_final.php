<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$count = $_POST['table_qua'];

$block_name = $_POST['block_name'];
$get_name   = $_POST['block_get_name'];

$new_role = new stdClass();
$new_role->name = $block_name;

$last_role_id = $DB->insert_record('roles',$new_role);

for ($i = 1; $i <= $count; $i++) {
    $block = new stdClass();
    $block->interface_table = $_POST['nametable_name_interface'.$i];
    $block->table_name_db = $_POST['nametable_name_db'.$i];
    $block->get_param_name = $_POST['nameget_param_name'.$i];
    $block->fields_for_db = $_POST['namefields_for_db'.$i];
    $block->headers_interface = $_POST['nameheaders_interface'.$i];
    $block->forms_data_types = $_POST['nameforms_data_types'.$i];
    $objects[] = $block;
    unset($block);
}

pre($objects);
foreach ($objects as $object) {
    //создаём данные для таблицы administration_table_link
    $std = new stdClass();
    $std->linkname = $object->interface_table;
    $std->link_get = $object->get_param_name;
    $std->header = $block_name;
    $std->status = 1;
    $std->get = $get_name;
    $std->role_id = $last_role_id;
    $DB->insert_record('administration_table_link',$std);
    $headers_DB = explode(', ',$object->fields_for_db);
    $types = explode(', ',$object->forms_data_types);
    $headers_interface = explode(', ',$object->headers_interface);
    //создаём таблицу для раздела
    $DB->CreateTable($object->get_param_name,$headers_DB,$types);
    unset($std);
    for ($i=0; $i < count($types); $i++) {
        $form_data_object = new stdClass();
        $form_data_object->get_name = $object->get_param_name;
        $form_data_object->type_name = $types[$i];
        $form_data_object->fn = $headers_DB[$i];
        $form_data_object->descriptor_n = $headers_interface[$i];
        $form_data_object->isused = 1;
        $form_data_object->requred = 1;
        $DB->insert_record('bsu_form_data',$form_data_object);
    }
}

//создаём саму таблицу для хранения данных


