<?php

session_start();

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');
require_once(__DIR__ . '/Todo.php');

$taskApp = new \MyApp\Task();
$tasks = $taskApp->getAll();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Task List</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>
  <div id="container">
   <h1 class="tasklist">Task List</h1>
   <form action="" id="new_task_form" class="form_task">
     <input type="text" id="new_task" class="addtask" placeholder="Please Add Task!">
   </form>
   <div id="container_sub">
   <h2 class="title">Task</h2>
   <ul id="tasks">
   <?php foreach ($tasks as $task) : ?>
     <li id="task_<?= h($task->id); ?>" data-id="<?= h($task->id); ?>">
       <input type="checkbox" class="update_task" <?php if ($task->state === '1') { echo 'checked'; } ?>>
       <span class="task_title <?php if ($task->state === '1') {echo 'done';} ?>"><?= h($task->title); ?></span>
       <div class="delete_task"><i class="fas fa-trash-alt"><span class="none">x</span></i></div>
     </li>
   <?php endforeach; ?>
    <li id="task_template" data-id="">
      <input type="checkbox" class="update_todo">
      <span class="task_title"></span>
      <div class="delete_task"><i class="fas fa-trash-alt"><span class="none">x</span></i></div>
    </li>
   </ul>
 </div>
  </div>
  <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="todo.js"></script>
</body>
</html>
