<?php
$task = 'backlog';

//$projects = ["Вхідні", "Навчання", "Робота", "Домашні справи", "Авто"];

//$tasks = [
//    [
//        'title' => 'Співбесіда в IT компанії',
//        'project' => 'Робота',
//        'status' => 'backlog',
//        'due_date' => null,
//    ],
//    [
//        'title' => 'Виконати тестове завдання',
//        'project' => 'Робота',
//        'status' => 'backlog',
//        'due_date' => '19.05.2023',
//    ],
//    [
//        'title' => 'Зробити завдання до першого уроку',
//        'project' => 'Навчання',
//        'status' => 'done',
//        'due_date' => '27.07.2023',
//    ],
//    [
//        'title' => 'Зустрітись з друзями',
//        'project' => 'Вхідні',
//        'status' => 'to-do',
//        'due_date' => '14.05.2023',
//    ],
//    [
//        'title' => 'Купити корм для кота',
//        'project' => 'Домашні справи',
//        'status' => 'in-progress',
//        'due_date' => null,
//    ],
//    [
//        'title' => 'Замовити піцу',
//        'project' => 'Домашні справи',
//        'status' => 'to-do',
//        'due_date' => null,
//    ],
//];

//function how_much ($tasks, $projects){
//    $count = 0;
//    foreach ($tasks as $tasks2) {
//        if ($tasks2['project'] === $projects) {
//            $count++;
//        }
//    }
//    return $count;
//}

function how_much_time ($tasks)
{

    $data_time = strtotime($tasks);
    $time_now = strtotime('now') + 10800;
    $difference = (($data_time - $time_now) / 60 / 60);

    if ($difference <= 24 && $difference >= -24) {
        return floor($difference) . ' годин';
    }
    return floor($difference / 24) . ' днів';
}

function bd() /* Ця функція підключається до сервера бд */
{
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = mysqli_connect('localhost', 'root', '', 'my_bd');
    if ($mysqli === false) {
        die('Fail to connect!');
    }
    mysqli_set_charset($mysqli, 'utf8mb4');
    return $mysqli;
}

function whitch_user() /* Ця функція виводить імʼя користувача */
{
    $id = $_GET['id'] ?? 1;
    $sql = 'SELECT name FROM users WHERE id = ? ';
    $mysqli = bd();
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt === false) {
        die('Fail to prepare query! Error: ' . mysqli_error($mysqli));
    }
    if (!mysqli_stmt_bind_param(
        $stmt,
        'i',
        $id
    )) {
        die('Error in params binding!');
    }
    if (!mysqli_stmt_execute($stmt)) {
        die('Cant execute query: ' . mysqli_stmt_error($mysqli));
    }

    $res = mysqli_stmt_get_result($stmt);
    if ($res === false) {
        die('Fail to request ' . $sql . ' With error: ' . mysqli_error($mysqli));
    }

    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    foreach ($rows as $row) {
        echo $row['name'];
    }
}





function get_projects($mysqli, $user_id) /* Ця функція виводить кількість проектів і задач користувача */
{
    $sql = 'SELECT name, projects.id,
       COUNT(tasks.id) as tasks
    FROM projects
        LEFT JOIN tasks ON projects.id=tasks.projects_id
    WHERE user_id = ?
    GROUP BY projects.id';
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt === false) {
        die('Fail to prepare query! Error: ' . mysqli_error($mysqli));
    }
    if (!mysqli_stmt_bind_param(
        $stmt,
        'i',
        $user_id
    )) {
        die('Error in params binding!');
    }
    if (!mysqli_stmt_execute($stmt)) {
        die('Cant execute query: ' . mysqli_stmt_error($mysqli));
    }

    $res = mysqli_stmt_get_result($stmt);
    if ($res === false) {
        die('Fail to request ' . $sql . ' With error: ' . mysqli_error($mysqli));
    }

    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return($rows);
}

function get_tasks($mysqli, $user_id)
{
    $sql = 'SELECT status,
       header AS title,
       deadline AS due_date
    FROM tasks
        LEFT JOIN projects ON tasks.projects_id=projects.id
WHERE user_id = ?
    GROUP BY tasks.id';
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt === false) {
        die('Fail to prepare query! Error: ' . mysqli_error($mysqli));
    }
    if (!mysqli_stmt_bind_param(
        $stmt,
        'i',
        $user_id
    )) {
        die('Error in params binding!');
    }
    if (!mysqli_stmt_execute($stmt)) {
        die('Cant execute query: ' . mysqli_stmt_error($mysqli));
    }

    $res = mysqli_stmt_get_result($stmt);
    if ($res === false) {
        die('Fail to request ' . $sql . ' With error: ' . mysqli_error($mysqli));
    }

    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return($rows);
}

$mysqli = bd();
$user_id = 1;
$name_title = 'Завдання та проекти | Дошка';
$name_image_src = 'static/img/user2-160x160.jpg';
//$name_user = 'Володимир';
$projects = get_projects($mysqli, $user_id);
$tasks = get_tasks($mysqli, $user_id);












require 'helpers.php';



/* Передаю до шаблону значення змінних */
$renderKandan = renderTemplate('kanban.php',
    [
    'tasks' => $tasks,
    ]);

$rendermain = renderTemplate('main.php',
    [
    'name_image_src' => $name_image_src,
//    'name_user' => $name_user,
    'projects' => $projects,
    'tasks' => $tasks,
    'renderKandan' => $renderKandan,
    ]);

print renderTemplate('layout.php',
    [
    'name_title' => $name_title,
    'rendermain' => $rendermain,
    ]);




