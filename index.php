<?php
require 'helpers.php';
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

function getConnection() /* Ця функція підключається до сервера бд */
{
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = mysqli_connect('localhost', 'root', '', 'my_bd');
    if ($mysqli === false) {
        die('Fail to connect!');
    }
    mysqli_set_charset($mysqli, 'utf8mb4');
    return $mysqli;
}

function whitch_user($mysqli, $id) /* Ця функція виводить імʼя користувача */
{

    $sql = 'SELECT name FROM users WHERE id = ? ';

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
        return $row['name'];
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

function get_tasks($mysqli, $user_id, $projects_id)
{
    $sql = 'SELECT status,
       header AS title,
       deadline AS due_date
    FROM tasks
        LEFT JOIN projects ON tasks.projects_id=projects.id
WHERE user_id = ?';

    if ($projects_id !== null) {
        $sql .= ' AND projects_id = ?';
    }
    $sql .= ' GROUP BY tasks.id';


    $stmt = mysqli_prepare($mysqli, $sql);
    if ($stmt === false) {
        die('Fail to prepare query! Error: ' . mysqli_error($mysqli));
    }
    if ($projects_id !== null) {
        if (!mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $user_id, $projects_id,
        )) {
            die('Error in params binding!');
        }

    }
    else {
        if (!mysqli_stmt_bind_param(
            $stmt,
            'i',
            $user_id,
        )) {
            die('Error in params binding!');
        }}








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
$mysqli = getConnection();

$user_id = 3; $id = $user_id;

$projects = get_projects($mysqli, $user_id);
$project_id = $_GET['project_id'] ?? null;




function isProjectExists($projects, $project_id)
{
if ($project_id === null){
    return true;
}
    $project_id = intval($project_id);
    foreach ($projects as $project) {
        if ($project['id'] === $project_id){
            return true;
        }
    }
    return false;
}




if (isProjectExists($projects, $project_id) === true) {
    $tasks = get_tasks($mysqli, $user_id, $project_id);
}
else{
    return http_response_code ( response_code: 404);

}


$whitch_user = whitch_user($mysqli, $id);
$name_title = 'Завдання та проекти | Дошка';
$name_image_src = 'static/img/user2-160x160.jpg';

/* Передаю до шаблону значення змінних */
$renderKandan = renderTemplate('kanban.php',
    [
    'tasks' => $tasks,
        'projects_id' => $project_id,
        'projects' => $projects,
    ]);

$rendermain = renderTemplate('main.php',
    [
    'name_image_src' => $name_image_src,
    'whitch_user' => $whitch_user,
    'projects' => $projects,
    'tasks' => $tasks,
    'renderKandan' => $renderKandan,
    'projects_id' => $project_id,

    ]);

print renderTemplate('layout.php',
    [
    'name_title' => $name_title,
    'rendermain' => $rendermain,
    ]);

