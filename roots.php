<?php

echo '<h3 style="text-align: center">Настройка прав доступа</h3>';

$users = $DB->getRecordsByConditionFetchAssoc('users');

$headers = $DB->getTableFieldsName('users','');
$content = $DB->getRecordsForTableInterfaceArray('users');

for ($i = 0; $i < count($content); $i++) {
    for ($g = 0; $g < count($content[$i]); $g++) {
        if ($g==3) {
            $arr[$i][$g] = "<a href='index.php?roots=1&action_role=1&{$content[$i][1]}'>{$content[$i][$g]}</a>";
        }
        else {
            $arr[$i][$g] = $content[$i][$g];
        }
    }
}


$table = new html_table();
if ($_GET['action_role']==1) {
    $user = new user();
    $user_name = array_keys($_GET)[2];
    //массив ролей для выбранного пользователя
    $user_roles = $user->getRoleListByLogin($user_name);
    //массив всех ролей
    $all_roles = (array_keys($DB->getAllRoles()));

    //all только у админов, права которых
    // совпадают с полным списком прав, поэтому
    // переписываем в его список ролей массив полных прав
    if ($user_roles[0] == 'all') {
        $user_roles = $all_roles;
    }


    echo "<h5>Пользователю <b>$user_name</b> назначены следующие блоки для просмотра: 
            <h6 style='color: red'>(неверные чеки, назначение верное. Необходимо поправить)</h6>
            </h5>";
    //проходим все роли, проверяем наличие текущей роли у пользователя

    //форма для чекбоксов
    $form = new html_form();
    $form->openForm("setRole.php?$user_name",'post');

    for ($i = 0; $i < count($all_roles); $i++) {
        if (in_array($user_roles[$i],$all_roles)) {
            echo $form->getCheckBox($i,$DB->getAllRoles()[$i+1]);
        }

        else {
            echo $form->getCheckBox($i,$DB->getAllRoles()[$i+1],'1');
        }
    }
    $form->hidden($i,'count_checks');
    echo '<br>';
    echo '<br>';
    $form->closeForm('Переназначить роли','warning');

}
//выводим таблицу. В таблице можно щёлкнуть на пользователя и получить список его прав просмотра
echo $table->printUsers('Действующие пользователи',$headers,$arr);